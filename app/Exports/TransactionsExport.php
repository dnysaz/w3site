<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaction::with('user')->latest()->get();
    }

    // Header Tabel Excel
    public function headings(): array
    {
        return ['ID Order', 'Nama User', 'Email', 'Jumlah', 'Status', 'Tanggal'];
    }

    // Mapping data agar tidak berantakan
    public function map($trx): array
    {
        return [
            $trx->order_id,
            $trx->user->name ?? 'N/A',
            $trx->user->email ?? 'N/A',
            $trx->amount,
            $trx->transaction_status,
            $trx->created_at->format('d-m-Y H:i'),
        ];
    }
}