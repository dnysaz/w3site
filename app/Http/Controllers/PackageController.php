<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // Pastikan Model Transaction sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Exception;

class PackageController extends Controller
{
    public function index()
    {
        return view('user-dashboard.pricing'); 
    }

    public function select(Request $request)
    {
        $request->validate([
            'package_id' => 'required|in:0,1,2',
            'channel_code' => 'nullable|string' // Menangkap metode bayar dari frontend
        ]);

        $user = Auth::user();
        $packageId = (int) $request->package_id;

        if ($user->package == $packageId) {
            return response()->json(['error' => 'Anda sudah menggunakan paket ini.'], 422);
        }

        // 1. Logika Paket Gratis (Tetap Sama)
        if ($packageId === 0) {
            $user->update(['package' => 0, 'package_expired_at' => null]);
            return response()->json(['status' => 'success', 'redirect' => route('dashboard')]);
        }

        // 2. Persiapkan Data Transaksi
        $orderId = 'INV-' . time() . '-' . $user->id;
        $prices = [1 => 29000, 2 => 49000];
        $packageNames = [1 => 'Growth Pack', 2 => 'Business Pro'];

        try {
            // 3. Panggil API Paymenku
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PAYMENKU_API_KEY'),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post('https://paymenku.com/api/v1/transaction/create', [
                'reference_id'   => $orderId,
                'amount'         => $prices[$packageId],
                'customer_name'  => $user->name,
                'customer_email' => $user->email,
                'channel_code'   => $request->channel_code ?? 'qris2', // Default QRIS jika tidak pilih
                'return_url'     => route('dashboard'), // URL setelah bayar selesai
            ]);

            $result = $response->json();

            if ($response->status() !== 200 || $result['status'] !== 'success') {
                throw new Exception($result['message'] ?? 'Gagal menghubungi Paymenku');
            }

            // 4. SIMPAN DATA KE DATABASE (PENTING!)
            // Agar saat callback, kita tahu order_id ini untuk package_id yang mana
            Transaction::create([
                'user_id'            => $user->id,
                'order_id'           => $orderId,
                'package_id'         => $packageId, // Simpan ini agar callback mudah
                'package_name'       => $packageNames[$packageId],
                'amount'             => $prices[$packageId],
                'transaction_status' => 'pending',
                'payment_type'       => $request->channel_code ?? 'qris2',
            ]);

            // 5. Kirim pay_url ke Frontend
            return response()->json([
                'status'  => 'success',
                'pay_url' => $result['data']['pay_url']
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}