<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar riwayat pembayaran user sesungguhnya.
     */
    public function index()
    {
        // Mengambil data real dari database berdasarkan user yang login
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Kita tidak lagi menggunakan collect([]) dummy di sini.
        // Jika kosong, view akan otomatis menangani dengan @forelse
        return view('user-dashboard.payment_history', compact('transactions'));
    }

    /**
     * Menampilkan struk pembayaran asli berdasarkan order_id.
     */
    public function printInvoice($order_id)
    {
        /**
         * Mengambil data asli. 
         * Menggunakan firstOrFail agar otomatis 404 jika order_id tidak ada 
         * atau bukan milik user tersebut.
         */
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('order_id', $order_id)
            ->firstOrFail();

        // Proteksi tambahan: Struk hanya bisa diakses jika pembayaran lunas
        $statusLunas = ['settlement', 'capture', 'success'];
        if (!in_array(strtolower($transaction->transaction_status), $statusLunas)) {
            abort(403, 'Struk belum tersedia untuk transaksi ini.');
        }

        return view('user-dashboard.user_invoice', compact('transaction'));
    }
}