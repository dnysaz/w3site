<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessful;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        /**
         * PAYLOAD PAYMENKU V1.0
         * reference_id: ID unik dari sistem w3site
         * status: 'paid', 'pending', 'expired', 'cancelled'
         */
        $referenceId = $request->input('reference_id');
        $status = $request->input('status');
        $paymentChannel = $request->input('payment_channel');

        // 1. Validasi Keamanan (Double Check ke API Paymenku)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYMENKU_API_KEY'),
            'Accept' => 'application/json',
        ])->get("https://paymenku.com/api/v1/check-status/{$referenceId}");

        if ($response->status() !== 200 || $response->json('data.status') !== $status) {
            return response()->json(['message' => 'Data callback tidak valid'], 403);
        }

        // 2. Cari data transaksi
        $transaction = Transaction::where('order_id', $referenceId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // 3. LOGIKA BERDASARKAN STATUS
        if ($status === 'paid') {
            
            // Cek jika sudah pernah diproses agar tidak double update
            if (in_array($transaction->transaction_status, ['settlement', 'capture'])) {
                return response()->json(['message' => 'Sudah diproses'], 200);
            }
        
            // Tentukan ID paket
            $packageName = strtolower($transaction->package_name);
            $packageId = 0; 
        
            if (str_contains($packageName, 'pemula') || str_contains($packageName, 'growth')) {
                $packageId = 1;
            } elseif (str_contains($packageName, 'pro') || str_contains($packageName, 'business')) {
                $packageId = 2;
            }
        
            // Update data User (Untuk akses fitur)
            $user = $transaction->user;
            $user->update([
                'package' => $packageId,
                'package_expired_at' => Carbon::now()->addMonth(),
            ]);
        
            // Update Data Transaksi (Untuk tampilan Billing History)
            $transaction->update([
                'transaction_status' => 'settlement', // Set ke settlement agar Blade otomatis "Lunas"
                'payment_type' => $paymentChannel,
                'expired_at' => Carbon::now()->addMonth(), // INI PENTING: Agar masa aktif muncul di tabel
            ]);

            // Kirim Notifikasi Email
            try {
                $user->notify(new PaymentSuccessful($transaction));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email pembayaran: ' . $e->getMessage());
            }

        } elseif (in_array($status, ['expired', 'cancelled'])) {
            $transaction->update([
                'transaction_status' => $status
            ]);
        }

        return response()->json(['status' => 'OK', 'message' => 'Callback processed successfully']);
    }
}