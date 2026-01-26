<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
// Import Notification di sini
use App\Notifications\PaymentSuccessful;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        /**
         * PAYLOAD PAYMENKU V1.0
         * event: 'payment.status_updated'
         * reference_id: ID unik dari sistem w3site
         * status: 'paid', 'pending', 'expired', 'cancelled'
         */
        $referenceId = $request->input('reference_id');
        $status = $request->input('status');
        $trxId = $request->input('trx_id');

        // 1. Validasi Keamanan (Double Check ke API Paymenku)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYMENKU_API_KEY'),
            'Accept' => 'application/json',
        ])->get("https://paymenku.com/api/v1/check-status/{$referenceId}");

        if ($response->status() !== 200 || $response->json('data.status') !== $status) {
            return response()->json(['message' => 'Data callback tidak valid atau tidak cocok dengan server'], 403);
        }

        // 2. Cari data transaksi di database lokal
        $transaction = Transaction::where('order_id', $referenceId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan di database kami'], 404);
        }

        // Ambil data User lewat relasi
        $user = $transaction->user;

        // 3. LOGIKA BERDASARKAN STATUS PAYMENKU
        if ($status === 'paid') {
            
            // Cek jika sudah pernah diproses (idempotency) agar tidak kirim email berkali-kali
            if (in_array($transaction->transaction_status, ['settlement', 'paid'])) {
                return response()->json(['message' => 'Transaksi ini sudah diproses sebelumnya'], 200);
            }

            // Ambil package_id dari transaksi
            $packageId = $transaction->package_id; 

            // Hitung Masa Aktif
            $currentExpiredAt = $user->package_expired_at ? Carbon::parse($user->package_expired_at) : null;
            
            if ($user->package == $packageId && $currentExpiredAt && $currentExpiredAt->isFuture()) {
                $newExpiredDate = $currentExpiredAt->addMonth();
            } else {
                $newExpiredDate = Carbon::now()->addMonth();
            }

            // Update data User
            $user->update([
                'package' => $packageId,
                'package_expired_at' => $newExpiredDate,
            ]);

            // Update Status Transaksi di DB Lokal
            $transaction->update([
                'transaction_status' => 'paid',
                'payment_type' => $request->input('payment_channel') ?? $transaction->payment_type,
                'expired_at' => $newExpiredDate,
                'trx_id' => $trxId 
            ]);

            // --- PENGIRIMAN EMAIL NOTIFIKASI ---
            try {
                $user->notify(new PaymentSuccessful($transaction));
            } catch (\Exception $e) {
                // Kita log jika gagal kirim email, tapi tetap kembalikan respons OK ke Paymenku
                \Log::error('Gagal mengirim email pembayaran: ' . $e->getMessage());
            }

        } elseif (in_array($status, ['expired', 'cancelled'])) {
            $transaction->update([
                'transaction_status' => $status
            ]);
        }

        return response()->json(['status' => 'OK', 'message' => 'Callback processed successfully']);
    }
}