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
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('order_id', $order_id)
            ->firstOrFail();
    
        // Mapping status Midtrans yang dianggap LUNAS
        $statusLunas = ['settlement', 'capture', 'success'];
        $statusSaatIni = strtolower($transaction->transaction_status);
    
        // Jika statusnya 'pending', kita arahkan user untuk menyelesaikan pembayaran
        if ($statusSaatIni === 'pending') {
            return back()->with('info', 'Pembayaran Anda masih tertunda. Silakan selesaikan pembayaran terlebih dahulu.');
        }
    
        if (!in_array($statusSaatIni, $statusLunas)) {
            abort(403, 'Struk belum tersedia. Status saat ini: ' . $statusSaatIni);
        }
    
        return view('user-dashboard.user_invoice', compact('transaction'));
    }
}