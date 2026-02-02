<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessful;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Log Payload (Sangat penting untuk audit jika ada komplain)
        Log::info('Midtrans Callback Masuk:', $request->all());
    
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        
        // Pastikan pengambilan Server Key konsisten dengan config
        $serverKey = config('services.midtrans.server_key'); 
    
        // 2. Validasi Signature (Pagar Keamanan Utama)
        $signature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signature !== ($payload['signature_key'] ?? '')) {
            Log::error("Midtrans Callback: Signature Invalid untuk Order " . $orderId);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }
    
        // 3. Cari Data Transaksi dengan Relasi User
        $transaction = Transaction::with('user')->where('order_id', $orderId)->first();
    
        if (!$transaction) {
            Log::error("Midtrans Callback: Transaksi tidak ditemukan untuk Order " . $orderId);
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
    
        $transactionStatus = $payload['transaction_status'];
        $paymentType = $payload['payment_type'];
    
        // 4. Logika Lunas (Settlement / Capture)
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            
            // Idempotency: Jika sudah lunas, jangan proses lagi
            if ($transaction->transaction_status === 'settlement') {
                return response()->json(['message' => 'Sudah pernah diproses'], 200);
            }
    
            // Tentukan Paket
            $packageName = strtolower($transaction->package_name);
            $packageId = 0; 
            if (str_contains($packageName, 'pemula') || str_contains($packageName, 'growth')) {
                $packageId = 1;
            } elseif (str_contains($packageName, 'pro') || str_contains($packageName, 'business')) {
                $packageId = 2;
            }
    
            $user = $transaction->user;
            if ($user) {
                $user->update([
                    'package' => $packageId,
                    'package_expired_at' => Carbon::now()->addMonth(),
                ]);
    
                try {
                    $user->notify(new PaymentSuccessful($transaction));
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email: ' . $e->getMessage());
                }
            }
    
            $transaction->update([
                'transaction_status' => 'settlement',
                'payment_type' => $paymentType,
                'expired_at' => Carbon::now()->addMonth(),
            ]);
    
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $transaction->update([
                'transaction_status' => $transactionStatus == 'expire' ? 'expired' : 'cancelled'
            ]);
        }
    
        return response()->json(['status' => 'OK']);
    }
}