<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(10);
        
        // Data Grafik Pendapatan 7 Hari Terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[] = Transaction::whereIn('transaction_status', ['settlement', 'capture'])
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount');
        }

        $stats = [
            'total_revenue' => Transaction::whereIn('transaction_status', ['settlement', 'capture'])->sum('amount'),
            'pending_count' => Transaction::where('transaction_status', 'pending')->count(),
            'success_count' => Transaction::whereIn('transaction_status', ['settlement', 'capture'])->count(),
        ];

        return view('admin-dashboard.transactions.index', compact('transactions', 'chartLabels', 'chartData', 'stats'));
    }

    public function export() 
    {
        return Excel::download(new TransactionsExport, 'laporan-transaksi-' . now()->format('Y-m-d') . '.xlsx');
    }
}