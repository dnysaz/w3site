<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        /**
         * Proteksi tambahan: Struk hanya bisa diakses jika pembayaran lunas.
         * Kita tambahkan 'paid' ke dalam array karena di database PostgreSQL 
         * status transaksi Kakak tercatat sebagai 'paid'.
         */
        $statusLunas = ['settlement', 'capture', 'success', 'paid', 'lunas'];
        $statusSaatIni = strtolower($transaction->transaction_status);

        if (!in_array($statusSaatIni, $statusLunas)) {
            abort(403, 'Struk belum tersedia untuk transaksi dengan status: ' . $statusSaatIni);
        }

        return view('user-dashboard.user_invoice', compact('transaction'));
    }
}