<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $search       = (string) $request->get('search', '');
        $category     = (string) $request->get('category', '');
        $statusFilter = (string) $request->get('status_filter', '');
        $sortBy       = (string) $request->get('sort', '');

        $data       = $this->getStockData($search, $category, $statusFilter, $sortBy);
        $categories = DB::table('categories')->where('is_deleted', 0)->orderBy('category_name')->get();

        $summary = [
            'total_produk'    => $data->count(),
            'total_stok'      => $data->sum('stock'),
            'total_on_rent'   => $data->sum('on_rent'),
            'total_available' => $data->sum('available'),
            'total_penalty'   => $data->sum('in_penalty'),
            'produk_kritis'   => $data->where('stock_status', 'Kritis')->count(),
            'produk_kurang'   => $data->where('stock_status', 'Kurang')->count(),
        ];

        return view('stock-opname.index', compact(
            'data', 'categories', 'summary', 'search', 'category', 'statusFilter', 'sortBy'
        ));
    }

    public function exportPdf(Request $request)
    {
        $search       = (string) $request->get('search', '');
        $category     = (string) $request->get('category', '');
        $statusFilter = (string) $request->get('status_filter', '');
        $preview      = $request->boolean('preview');
        $sortBy       = (string) $request->get('sort', '');

        $data = $this->getStockData($search, $category, $statusFilter, $sortBy);

        $summary = [
            'total_produk'    => $data->count(),
            'total_stok'      => $data->sum('stock'),
            'total_on_rent'   => $data->sum('on_rent'),
            'total_available' => $data->sum('available'),
            'total_penalty'   => $data->sum('in_penalty'),
            'produk_kritis'   => $data->where('stock_status', 'Kritis')->count(),
            'produk_kurang'   => $data->where('stock_status', 'Kurang')->count(),
        ];

        $tanggal = now()->format('d F Y H:i');

        $pdf = Pdf::loadView('stock-opname.pdf', compact(
            'data', 'summary', 'tanggal', 'search', 'category', 'statusFilter'
        ))->setPaper('a4', 'landscape');

        if ($preview) {
            return $pdf->stream('stock-opname-preview.pdf');
        }

        return $pdf->download('stock-opname-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $search       = (string) $request->get('search', '');
        $category     = (string) $request->get('category', '');
        $statusFilter = (string) $request->get('status_filter', '');
        $sortBy       = (string) $request->get('sort', '');

        $data     = $this->getStockData($search, $category, $statusFilter, $sortBy);
        $filename = 'stock-opname-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'No', 'Nama Produk', 'Kategori', 'Kondisi',
                'Stok Total', 'Stok Awal (Estimasi)',
                'Sedang Disewa', 'Tersedia', 'Dalam Penalty', 'Selisih', 'Status Stok',
            ]);
            foreach ($data as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row->product_name,
                    $row->category_name ?? '-',
                    $row->condition ?? '-',
                    $row->stock,
                    $row->stock_initial,
                    $row->on_rent,
                    $row->available,
                    $row->in_penalty,
                    $row->selisih,
                    $row->stock_status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getStockData(
        string $search = '',
        string $category = '',
        string $statusFilter = '',
        string $sortBy = ''
    ) {
        // Subquery on_rent menggunakan string literal SQL langsung
        $onRentSql = "
            SELECT product_id, COUNT(*) AS on_rent_count
            FROM transactions
            WHERE trx_status IN ('Active', 'Overdue')
              AND is_deleted = 0
            GROUP BY product_id
        ";

        // Subquery penalty
        $penaltySql = "
            SELECT t.product_id, COUNT(pen.id) AS penalty_count
            FROM penalties pen
            JOIN transactions t ON pen.transaction_id = t.id
            WHERE pen.resolved = 0
              AND pen.is_deleted = 0
            GROUP BY t.product_id
        ";

        $query = DB::table('products as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin(DB::raw("({$onRentSql}) as ort"), 'ort.product_id', '=', 'p.id')
            ->leftJoin(DB::raw("({$penaltySql}) as plt"), 'plt.product_id', '=', 'p.id')
            ->select([
                'p.id',
                'p.product_name',
                'p.stock',
                'p.condition',
                'p.status',
                'c.category_name',
                DB::raw('COALESCE(ort.on_rent_count, 0) AS on_rent'),
                DB::raw('COALESCE(plt.penalty_count, 0) AS in_penalty'),
                DB::raw('(p.stock + COALESCE(ort.on_rent_count, 0) + COALESCE(plt.penalty_count, 0)) AS stock_initial'),
                DB::raw('GREATEST(p.stock - COALESCE(ort.on_rent_count, 0) - COALESCE(plt.penalty_count, 0), 0) AS available'),
                DB::raw('(COALESCE(ort.on_rent_count, 0) + COALESCE(plt.penalty_count, 0)) AS selisih'),
            ])
            ->where('p.is_deleted', 0);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('p.product_name', 'like', "%{$search}%")
                  ->orWhere('c.category_name', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('p.category_id', $category);
        }

        $results = $query->orderBy('p.product_name')->get();

        // Tambah kolom stock_status
        $results = $results->map(function ($row) {
            $available = (int) $row->available;
            $total     = (int) $row->stock;

            if ($total === 0) {
                $row->stock_status = 'Habis';
            } elseif ($available === 0) {
                $row->stock_status = 'Kritis';
            } elseif ($available <= max(1, intval($total * 0.2))) {
                $row->stock_status = 'Kurang';
            } else {
                $row->stock_status = 'Normal';
            }

            return $row;
        });

        // Filter status stok
        if ($statusFilter !== '') {
            $results = $results->filter(fn($row) => $row->stock_status === $statusFilter);
        }

        // Sort
        $results = match($sortBy) {
            'name_asc'       => $results->sortBy('product_name', SORT_NATURAL | SORT_FLAG_CASE),
            'name_desc'      => $results->sortByDesc('product_name'),
            'stock_asc'      => $results->sortBy('stock'),
            'stock_desc'     => $results->sortByDesc('stock'),
            'available_asc'  => $results->sortBy('available'),
            'available_desc' => $results->sortByDesc('available'),
            default          => $results->sortBy('product_name', SORT_NATURAL | SORT_FLAG_CASE),
        };

        return $results->values();
    }
}