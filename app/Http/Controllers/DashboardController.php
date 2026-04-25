<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ──
        $totalRevenue = DB::table('transactions')
            ->where('trx_status', 'Completed')
            ->where('is_deleted', 0)
            ->sum('total_amount');

        $activeRentals = DB::table('transactions')
            ->where('trx_status', 'Active')
            ->where('is_deleted', 0)
            ->count();

        $totalCustomers = DB::table('users')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->count();

        $pendingPenalties = DB::table('penalties')
            ->where('resolved', 0)
            ->where('is_deleted', 0)
            ->count();

        // ── Recent Transactions ──
        $recentTransactions = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->where('t.is_deleted', 0)
            ->select('t.trx_code', 't.total_amount', 't.trx_status', 'u.name as customer_name', 'p.product_name')
            ->orderBy('t.created_date', 'desc')
            ->limit(5)
            ->get();

        // ── Chart 1: Pendapatan per bulan (12 bulan terakhir) ──
        $revenueChart = DB::table('transactions')
            ->where('is_deleted', 0)
            ->where('trx_status', 'Completed')
            ->whereRaw('created_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
            ->select(
                DB::raw("DATE_FORMAT(created_date, '%b %Y') as month_label"),
                DB::raw("DATE_FORMAT(created_date, '%Y-%m') as month_key"),
                DB::raw("SUM(total_amount) as total")
            )
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->get();

        // Fill bulan kosong dengan 0
        $revenueLabels = [];
        $revenueData   = [];
        $keyed = $revenueChart->keyBy('month_key');
        for ($i = 11; $i >= 0; $i--) {
            $key   = now()->subMonths($i)->format('Y-m');
            $label = now()->subMonths($i)->format('M Y');
            $revenueLabels[] = $label;
            $revenueData[]   = (float) ($keyed[$key]->total ?? 0);
        }

        // ── Chart 2: Status transaksi (pie) ──
        $statusChart = DB::table('transactions')
            ->where('is_deleted', 0)
            ->select('trx_status', DB::raw('COUNT(*) as total'))
            ->groupBy('trx_status')
            ->get()
            ->keyBy('trx_status');

        $statusData = [
            'Active'    => (int) ($statusChart['Active']->total    ?? 0),
            'Completed' => (int) ($statusChart['Completed']->total ?? 0),
            'Overdue'   => (int) ($statusChart['Overdue']->total   ?? 0),
            'Cancelled' => (int) ($statusChart['Cancelled']->total ?? 0),
        ];

        return view('dashboard.index', compact(
            'totalRevenue', 'activeRentals', 'totalCustomers', 'pendingPenalties',
            'recentTransactions', 'revenueLabels', 'revenueData', 'statusData'
        ));
    }
}