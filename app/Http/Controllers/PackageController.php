<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class PackageController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        return view('user-dashboard.pricing'); 
    }

    public function select(Request $request)
    {
        $request->validate(['package_id' => 'required|in:0,1,2']);

        $user = Auth::user();
        $packageId = (int) $request->package_id;

        if ($user->package == $packageId) {
            return response()->json(['error' => 'Anda sudah menggunakan paket ini.'], 422);
        }

        // 1. Logika Paket Gratis
        if ($packageId === 0) {
            $user->update(['package' => 0, 'package_expired_at' => null]);
            return response()->json(['status' => 'success', 'message' => 'Paket berhasil diubah ke Gratis']);
        }

        // 2. Persiapkan Data Transaksi
        $orderId = 'W3-' . time() . '-' . $user->id;
        $prices = [1 => 29000, 2 => 49000];
        $packageNames = [1 => 'Growth Pack', 2 => 'Business Pro'];

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $prices[$packageId],
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => [
                    [
                        'id' => 'PKG-' . $packageId,
                        'price' => $prices[$packageId],
                        'quantity' => 1,
                        'name' => "Upgrade Ke " . $packageNames[$packageId],
                    ]
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            Transaction::create([
                'user_id'            => $user->id,
                'order_id'           => $orderId,
                'package_id'         => $packageId,
                'package_name'       => $packageNames[$packageId],
                'amount'             => $prices[$packageId],
                'transaction_status' => 'pending',
                'payment_type'       => 'midtrans',
                'snap_token'         => $snapToken,
            ]);

            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken
            ]);

        } catch (Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat sesi pembayaran.'], 500);
        }
    }
}