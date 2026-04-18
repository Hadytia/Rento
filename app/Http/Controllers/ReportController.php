<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'product'])
                        ->where('is_deleted', 0)
                        ->latest('created_date')
                        ->get();

        $dateStart = now()->startOfMonth()->format('d M');
        $dateEnd   = now()->endOfMonth()->format('d M');

        return view('reports.index', compact('transactions', 'dateStart', 'dateEnd'));
    }

    public function export()
    {
        $transactions = Transaction::with(['user', 'product'])
                        ->where('is_deleted', 0)
                        ->latest('created_date')
                        ->get();

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['TRX ID', 'Customer', 'Item', 'Period Start', 'Period End', 'Amount', 'Status']);

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->trx_code,
                    $trx->user->name        ?? $trx->user_id,
                    $trx->product->product_name ?? $trx->product_id,
                    $trx->rental_start,
                    $trx->rental_end,
                    $trx->total_amount,
                    $trx->trx_status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function download($id)
    {
        $trx = Transaction::with(['user', 'product'])
                ->where('is_deleted', 0)
                ->findOrFail($id);

        $filename = 'transaction_' . $trx->trx_code . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($trx) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['TRX ID', 'Customer', 'Item', 'Period Start', 'Period End', 'Amount', 'Status']);
            fputcsv($file, [
                $trx->trx_code,
                $trx->user->name            ?? $trx->user_id,
                $trx->product->product_name ?? $trx->product_id,
                $trx->rental_start,
                $trx->rental_end,
                $trx->total_amount,
                $trx->trx_status,
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}