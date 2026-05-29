<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RevenueKategoriController extends Controller
{
    public function index(Request $request)
    {
        $year     = (string) $request->get('year', date('Y'));
        $month    = (string) $request->get('month', '');
        $category = (string) $request->get('category', '');

        $data        = $this->getRevenueData($year, $month, $category);
        $chartData   = $this->getChartData($year, $month);
        $monthlyData = $this->getMonthlyTrend($year, $category);
        $categories  = DB::table('categories')->where('is_deleted', 0)->orderBy('category_name')->get();
        $years       = $this->getAvailableYears();

        $summary = [
            'total_revenue'     => $data->sum('total_revenue'),
            'total_transaksi'   => $data->sum('total_transaksi'),
            'total_completed'   => $data->sum('completed'),
            'total_active'      => $data->sum('active'),
            'rata_per_kategori' => $data->count() > 0 ? $data->sum('total_revenue') / $data->count() : 0,
            'kategori_teratas'  => $data->sortByDesc('total_revenue')->first(),
        ];

        return view('revenue-kategori.index', compact(
            'data', 'chartData', 'monthlyData', 'categories', 'years',
            'summary', 'year', 'month', 'category'
        ));
    }

    public function exportPdf(Request $request)
    {
        $year     = (string) $request->get('year', date('Y'));
        $month    = (string) $request->get('month', '');
        $category = (string) $request->get('category', '');
        $preview  = $request->boolean('preview');

        $data      = $this->getRevenueData($year, $month, $category);
        $monthlyData = $this->getMonthlyTrend($year, $category);
        $categories  = DB::table('categories')->where('is_deleted', 0)->orderBy('category_name')->get();

        $summary = [
            'total_revenue'     => $data->sum('total_revenue'),
            'total_transaksi'   => $data->sum('total_transaksi'),
            'total_completed'   => $data->sum('completed'),
            'total_active'      => $data->sum('active'),
            'rata_per_kategori' => $data->count() > 0 ? $data->sum('total_revenue') / $data->count() : 0,
            'kategori_teratas'  => $data->sortByDesc('total_revenue')->first(),
        ];

        $tanggal   = now()->format('d F Y H:i');
        $bulanName = $month ? \Carbon\Carbon::create()->month((int)$month)->format('F') : 'Semua Bulan';

        $pdf = Pdf::loadView('revenue-kategori.pdf', compact(
            'data', 'monthlyData', 'summary', 'tanggal', 'year', 'month', 'bulanName', 'category'
        ))->setPaper('a4', 'landscape');

        if ($preview) {
            return $pdf->stream('revenue-kategori-preview.pdf');
        }

        return $pdf->download('revenue-kategori-' . $year . '-' . now()->format('Ymd') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $year     = (string) $request->get('year', date('Y'));
        $month    = (string) $request->get('month', '');
        $category = (string) $request->get('category', '');

        $data     = $this->getRevenueData($year, $month, $category);
        $filename = 'revenue-kategori-' . $year . '-' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'No', 'Kategori', 'Total Transaksi', 'Completed',
                'Active', 'Overdue', 'Cancelled',
                'Total Revenue (Rp)', 'Revenue Completed (Rp)',
                'Kontribusi (%)',
            ]);
            $grandTotal = $data->sum('total_revenue');
            foreach ($data as $i => $row) {
                $pct = $grandTotal > 0 ? round($row->total_revenue / $grandTotal * 100, 2) : 0;
                fputcsv($file, [
                    $i + 1,
                    $row->category_name,
                    $row->total_transaksi,
                    $row->completed,
                    $row->active,
                    $row->overdue,
                    $row->cancelled,
                    $row->total_revenue,
                    $row->revenue_completed,
                    $pct . '%',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Core Query ──────────────────────────────────────────────────────────

    private function getRevenueData(string $year, string $month, string $category)
    {
        $query = DB::table('transactions as t')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select([
                'c.id as category_id',
                'c.category_name',
                DB::raw('COUNT(t.id) AS total_transaksi'),
                DB::raw("SUM(CASE WHEN t.trx_status = 'Completed' THEN 1 ELSE 0 END) AS completed"),
                DB::raw("SUM(CASE WHEN t.trx_status = 'Active'    THEN 1 ELSE 0 END) AS active"),
                DB::raw("SUM(CASE WHEN t.trx_status = 'Overdue'   THEN 1 ELSE 0 END) AS overdue"),
                DB::raw("SUM(CASE WHEN t.trx_status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled"),
                // Total revenue: completed pakai total_amount, active/overdue pakai paid_amount
                DB::raw("SUM(
                    CASE
                        WHEN t.trx_status = 'Completed' THEN COALESCE(t.total_amount, 0)
                        WHEN t.trx_status IN ('Active','Overdue') THEN COALESCE(t.paid_amount, 0)
                        ELSE 0
                    END
                ) AS total_revenue"),
                DB::raw("SUM(CASE WHEN t.trx_status = 'Completed' THEN COALESCE(t.total_amount, 0) ELSE 0 END) AS revenue_completed"),
            ])
            ->where('t.is_deleted', 0)
            ->where('p.is_deleted', 0);

        if ($year !== '') {
            $query->whereYear('t.created_date', $year);
        }
        if ($month !== '') {
            $query->whereMonth('t.created_date', $month);
        }
        if ($category !== '') {
            $query->where('c.id', $category);
        }

        return $query->groupBy('c.id', 'c.category_name')
                     ->orderByDesc('total_revenue')
                     ->get();
    }

    private function getChartData(string $year, string $month)
    {
        $query = DB::table('transactions as t')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select([
                'c.category_name',
                DB::raw("SUM(
                    CASE
                        WHEN t.trx_status = 'Completed' THEN COALESCE(t.total_amount, 0)
                        WHEN t.trx_status IN ('Active','Overdue') THEN COALESCE(t.paid_amount, 0)
                        ELSE 0
                    END
                ) AS total_revenue"),
            ])
            ->where('t.is_deleted', 0)
            ->where('p.is_deleted', 0);

        if ($year !== '') $query->whereYear('t.created_date', $year);
        if ($month !== '') $query->whereMonth('t.created_date', $month);

        return $query->groupBy('c.category_name')
                     ->orderByDesc('total_revenue')
                     ->get();
    }

    private function getMonthlyTrend(string $year, string $category)
    {
        $query = DB::table('transactions as t')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select([
                DB::raw('MONTH(t.created_date) AS bulan'),
                'c.category_name',
                DB::raw("SUM(
                    CASE
                        WHEN t.trx_status = 'Completed' THEN COALESCE(t.total_amount, 0)
                        WHEN t.trx_status IN ('Active','Overdue') THEN COALESCE(t.paid_amount, 0)
                        ELSE 0
                    END
                ) AS revenue"),
            ])
            ->where('t.is_deleted', 0)
            ->where('p.is_deleted', 0)
            ->whereYear('t.created_date', $year);

        if ($category !== '') $query->where('c.id', $category);

        return $query->groupBy('bulan', 'c.category_name')
                     ->orderBy('bulan')
                     ->get();
    }

    private function getAvailableYears()
    {
        return DB::table('transactions')
            ->where('is_deleted', 0)
            ->selectRaw('YEAR(created_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
    }
}