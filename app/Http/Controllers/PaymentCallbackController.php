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
    
            if (in_array($transaction->transaction_status, ['settlement', 'paid'])) {
                return response()->json(['message' => 'Sudah diproses'], 200);
            }
        
            // Tentukan ID paket berdasarkan Nama Paket yang ada di DB Kakak
            // Sesuaikan string ini dengan apa yang Kakak simpan saat user klik beli
            $packageName = strtolower($transaction->package_name);
            $packageId = 0; // Default gratis
        
            if (str_contains($packageName, 'pemula') || str_contains($packageName, 'growth')) {
                $packageId = 1;
            } elseif (str_contains($packageName, 'pro') || str_contains($packageName, 'business')) {
                $packageId = 2;
            }
        
            // Update data User
            $user = $transaction->user;
            $user->update([
                'package' => $packageId,
                'package_expired_at' => Carbon::now()->addMonth(),
            ]);
        
            // Update Status Transaksi
            $transaction->update([
                'transaction_status' => 'paid',
                'payment_type' => $request->input('payment_channel'),
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