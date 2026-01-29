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
        // 1. Ambil Payload dari Midtrans
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $serverKey = config('services.midtrans.server_key'); // Ambil dari config/services.php atau .env

        // 2. VALIDASI KEAMANAN (Gantinya double check API Paymenku)
        // Midtrans menggunakan Signature Key untuk memastikan data asli
        $signature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signature !== ($payload['signature_key'] ?? '')) {
            Log::warning("Midtrans Callback: Signature Invalid for Order " . $orderId);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 3. Cari Data Transaksi
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // 4. LOGIKA BERDASARKAN STATUS TRANSAKSI MIDTRANS
        $transactionStatus = $payload['transaction_status'];
        $paymentType = $payload['payment_type'];

        // Status 'settlement' atau 'capture' (untuk kartu kredit) berarti LUNAS
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            
            // Proteksi agar tidak double update (Idempotency)
            if (in_array($transaction->transaction_status, ['settlement', 'capture'])) {
                return response()->json(['message' => 'Sudah diproses'], 200);
            }

            // LOGIKA PAKET (Sesuai kode lama Kakak)
            $packageName = strtolower($transaction->package_name);
            $packageId = 0; 
        
            if (str_contains($packageName, 'pemula') || str_contains($packageName, 'growth')) {
                $packageId = 1;
            } elseif (str_contains($packageName, 'pro') || str_contains($packageName, 'business')) {
                $packageId = 2;
            }

            // Update data User
            $user = $transaction->user;
            if ($user) {
                $user->update([
                    'package' => $packageId,
                    'package_expired_at' => Carbon::now()->addMonth(),
                ]);

                // Kirim Notifikasi Email
                try {
                    $user->notify(new PaymentSuccessful($transaction));
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email pembayaran: ' . $e->getMessage());
                }
            }

            // Update Data Transaksi
            $transaction->update([
                'transaction_status' => 'settlement',
                'payment_type' => $paymentType,
                'expired_at' => Carbon::now()->addMonth(),
            ]);

        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            // Mapping status expired/cancelled
            $transaction->update([
                'transaction_status' => $transactionStatus == 'expire' ? 'expired' : 'cancelled'
            ]);
        }

        return response()->json(['status' => 'OK', 'message' => 'Midtrans callback processed']);
    }
}