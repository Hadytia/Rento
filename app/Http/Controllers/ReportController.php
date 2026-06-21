<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'product', 'payment'])
                        ->where('is_deleted', 0)
                        ->orderBy('created_date', 'desc')
                        ->get();

        $dateStart = now()->startOfMonth()->format('d M');
        $dateEnd   = now()->endOfMonth()->format('d M');

        return view('reports.index', compact('transactions', 'dateStart', 'dateEnd'));
    }

    // ── Export CSV semua (existing) ──
    public function export()
    {
        $transactions = Transaction::with(['user', 'product', 'payment'])
                        ->where('is_deleted', 0)
                        ->orderBy('created_date', 'desc')
                        ->get();

        return $this->streamCsv($transactions, 'transactions_all');
    }

    // ── Export CSV sesuai filter ──
    public function exportFiltered(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Transaction::with(['user', 'product', 'payment'])
                    ->where('is_deleted', 0)
                    ->orderBy('created_date', 'desc');

        if ($status !== 'all') {
            $query->whereRaw('LOWER(trx_status) = ?', [strtolower($status)]);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('trx_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($p) => $p->where('product_name', 'like', "%{$search}%"));
            });
        }

        $transactions = $query->get();
        $suffix = $status !== 'all' ? '_' . $status : '_filtered';

        return $this->streamCsv($transactions, 'transactions' . $suffix);
    }

    // ── Export PDF sesuai filter ──
    public function exportPdf(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Transaction::with(['user', 'product', 'payment'])
                    ->where('is_deleted', 0)
                    ->orderBy('created_date', 'desc');

        if ($status !== 'all') {
            $query->whereRaw('LOWER(trx_status) = ?', [strtolower($status)]);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('trx_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($p) => $p->where('product_name', 'like', "%{$search}%"));
            });
        }

        $transactions = $query->get();

        $statusLabel = match(strtolower($status)) {
            'active'    => 'Aktif',
            'completed' => 'Selesai',
            'overdue'   => 'Terlambat',
            'cancelled' => 'Dibatalkan',
            default     => 'Semua',
        };

        // PASS 1: render untuk hitung total halaman
        $pdfCounter = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact(
            'transactions', 'status', 'statusLabel', 'search'
        ))->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'Helvetica',
        ]);
        $pdfCounter->render();
        $totalPages = $pdfCounter->getDomPDF()->get_canvas()->get_page_count();

        // PASS 2: render final dengan total halaman yang sudah diketahui
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact(
            'transactions', 'status', 'statusLabel', 'search', 'totalPages'
        ))->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'Helvetica',
        ]);

        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->get_canvas();
        $font   = $dompdf->getFontMetrics()->getFont('Helvetica');
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($totalPages, $font) {
            $text = "Halaman {$pageNumber} dari {$totalPages}";
            $canvas->text(420, 800, $text, $font, 9, [0.4, 0.45, 0.51]);
        });

        $suffix   = $status !== 'all' ? '-' . ucfirst($status) : '';
        $filename = 'Transaksi' . $suffix . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    // ── Helper stream CSV ──
    private function streamCsv($transactions, $name)
    {
        $filename = $name . '_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['TRX ID', 'Customer', 'Item', 'Period Start', 'Period End', 'Amount', 'Status', 'Payment']);

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->trx_code,
                    $trx->user->name            ?? $trx->user_id,
                    $trx->product->product_name ?? $trx->product_id,
                    $trx->rental_start,
                    $trx->rental_end,
                    $trx->total_amount,
                    $trx->trx_status,
                    $trx->payment->transaction_status ?? '-',
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
        $headers  = [
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

    public function invoice($id)
    {
        $trx = Transaction::with(['user', 'product', 'payment'])
                ->where('is_deleted', 0)
                ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice.pdf', compact('trx'));

        return $pdf->download('Invoice-' . $trx->trx_code . '.pdf');
    }
}