<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default: bulan dan tahun sekarang
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // ── SUMMARY CARDS ──
        $totalRevenue = Transaction::with('payment')
            ->whereHas('payment', fn($q) => $q->where('transaction_status', 'settlement'))
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->where('is_deleted', 0)
            ->sum('total_amount');

        $totalTransaksi = Transaction::where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->count();

        $transaksiLunas = Transaction::with('payment')
            ->whereHas('payment', fn($q) => $q->where('transaction_status', 'settlement'))
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->where('is_deleted', 0)
            ->count();

        $transaksiPending = Transaction::whereDoesntHave('payment', fn($q) =>
            $q->where('transaction_status', 'settlement'))
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->where('is_deleted', 0)
            ->count();

        // ── REVENUE PER BULAN (12 bulan terakhir) ──
        $revenuePerBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->format('M Y');
            $total = Transaction::with('payment')
                ->whereHas('payment', fn($q) => $q->where('transaction_status', 'settlement'))
                ->whereYear('rental_start', $date->year)
                ->whereMonth('rental_start', $date->month)
                ->where('is_deleted', 0)
                ->sum('total_amount');
            $revenuePerBulan[] = ['bulan' => $label, 'total' => (int) $total];
        }

        // ── TOP PRODUK ──
        $topProduk = Transaction::with('product')
            ->select('product_id', \DB::raw('COUNT(*) as total_sewa'), \DB::raw('SUM(total_amount) as total_revenue'))
            ->where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderByDesc('total_sewa')
            ->limit(5)
            ->get();

        // ── TABEL TRANSAKSI PERIODE INI ──
        $transaksi = Transaction::with(['user', 'product', 'payment'])
            ->where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->orderBy('rental_start', 'desc')
            ->get();

        // ── STATUS BREAKDOWN ──
        $statusBreakdown = Transaction::where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->selectRaw('trx_status, COUNT(*) as total')
            ->groupBy('trx_status')
            ->pluck('total', 'trx_status');

        // ── DAFTAR TAHUN untuk filter ──
        $tahunList = range(now()->year, now()->year - 4);

        return view('laporan.index', compact(
            'totalRevenue', 'totalTransaksi', 'transaksiLunas', 'transaksiPending',
            'revenuePerBulan', 'topProduk', 'transaksi', 'statusBreakdown',
            'bulan', 'tahun', 'tahunList', 'startDate', 'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $totalRevenue = Transaction::with('payment')
            ->whereHas('payment', fn($q) => $q->where('transaction_status', 'settlement'))
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->where('is_deleted', 0)
            ->sum('total_amount');

        $totalTransaksi = Transaction::where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->count();

        $transaksiLunas = Transaction::with('payment')
            ->whereHas('payment', fn($q) => $q->where('transaction_status', 'settlement'))
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->where('is_deleted', 0)
            ->count();

        $transaksi = Transaction::with(['user', 'product', 'payment'])
            ->where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->orderBy('rental_start', 'desc')
            ->get();

        $topProduk = Transaction::with('product')
            ->select('product_id', \DB::raw('COUNT(*) as total_sewa'), \DB::raw('SUM(total_amount) as total_revenue'))
            ->where('is_deleted', 0)
            ->whereBetween('rental_start', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderByDesc('total_sewa')
            ->limit(5)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact(
            'totalRevenue', 'totalTransaksi', 'transaksiLunas',
            'transaksi', 'topProduk', 'startDate', 'endDate', 'bulan', 'tahun'
        ))->setPaper('a4', 'portrait');

        $filename = 'Laporan-' . Carbon::createFromDate($tahun, $bulan, 1)->format('F-Y') . '.pdf';

        return $pdf->download($filename);
    }
}