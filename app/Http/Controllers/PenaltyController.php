<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\CheckEditAccess;

class PenaltyController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        $penalties = DB::table('penalties as p')
            ->join('transactions as t', 'p.transaction_id', '=', 't.id')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as pr', 't.product_id', '=', 'pr.id')
            ->where('p.is_deleted', 0)
            ->where('p.status', 1)
            ->select(
                'p.id',
                'p.transaction_id',
                'p.penalty_type',
                'p.penalty_amount',
                'p.overdue_days',
                'p.description',
                'p.resolved',
                'p.created_date',
                't.trx_code',
                't.rental_start',
                't.rental_end',
                't.trx_status',
                'u.name as customer_name',
                'u.email as customer_email',
                'pr.product_name'
            )
            ->orderBy('p.created_date', 'desc')
            ->get();

        $overdueTransactions = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as pr', 't.product_id', '=', 'pr.id')
            ->leftJoin('penalties as p', 't.id', '=', 'p.transaction_id')
            ->where('t.trx_status', 'Overdue')
            ->where('t.is_deleted', 0)
            ->where('t.status', 1)
            ->select(
                't.id',
                't.trx_code',
                't.rental_end',
                't.total_days',
                'u.name as customer_name',
                'u.email as customer_email',
                'pr.product_name',
                DB::raw('DATEDIFF(CURDATE(), t.rental_end) as days_late'),
                DB::raw('COUNT(p.id) as penalty_count')
            )
            ->groupBy(
                't.id', 't.trx_code', 't.rental_end', 't.total_days',
                'u.name', 'u.email', 'pr.product_name'
            )
            ->get();

        $stats = [
            'total_penalties'  => DB::table('penalties')->where('is_deleted', 0)->count(),
            'unpaid_penalties' => DB::table('penalties')->where('resolved', 0)->where('is_deleted', 0)->count(),
            'total_amount'     => DB::table('penalties')->where('is_deleted', 0)->sum('penalty_amount'),
            'unpaid_amount'    => DB::table('penalties')->where('resolved', 0)->where('is_deleted', 0)->sum('penalty_amount'),
        ];

        return view('penalties.index', compact('penalties', 'overdueTransactions', 'stats'));
    }

    public function markResolved($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        DB::table('penalties')
            ->where('id', $id)
            ->update(['resolved' => 1, 'status' => 1]);

        return redirect()->route('penalties.index')->with('success', 'Penalty marked as resolved.');
    }

    public function markFinished($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        // ✅ Ambil data DULU sebelum di-update
        $penalty = DB::table('penalties')->where('id', $id)->first();

        DB::table('penalties')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);

        if ($penalty) {
            DB::table('transactions')
                ->where('id', $penalty->transaction_id)
                ->update(['trx_status' => 'Completed']);
        }

        return redirect()->route('penalties.index')->with('success', 'Penalty marked as finished.');
    }

    public function sendReminder(Request $request)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $transactionId = $request->input('transaction_id');

        $transaction = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->where('t.id', $transactionId)
            ->select('t.*', 'u.name', 'u.email')
            ->first();

        if (!$transaction) {
            return redirect()->route('penalties.index')->with('error', 'Transaction not found.');
        }

        // TODO: Mail::to($transaction->email)->send(new ReminderMail($transaction));
        return redirect()->route('penalties.index')->with('success', "Reminder sent to {$transaction->name} ({$transaction->email}).");
    }

        public function apiIndex(Request $request)
    {
        $query = DB::table('penalties as p')
            ->join('transactions as t', 'p.transaction_id', '=', 't.id')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as pr', 't.product_id', '=', 'pr.id')
            ->where('p.is_deleted', 0)
            ->where('p.status', 1)
            ->select(
                'p.id',
                'p.penalty_type',
                'p.penalty_amount',
                'p.overdue_days',
                'p.description',
                'p.resolved',
                'p.created_date',
                't.trx_code',
                't.rental_start',
                't.rental_end',
                't.trx_status',
                'u.name as customer_name',
                'u.email as customer_email',
                'pr.product_name'
            )
            ->orderBy('p.created_date', 'desc');

        // Filter by resolved (opsional)
        // contoh: /api/penalties?resolved=0
        if ($request->has('resolved')) {
            $query->where('p.resolved', $request->resolved);
        }

        $penalties = $query->get();

        return response()->json([
            'success' => true,
            'data'    => $penalties,
        ]);
    }

        public function apiStore(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'penalty_type'   => 'required|in:Overdue,Damage,Other',
            'penalty_amount' => 'required|numeric|min:0',
            'overdue_days'   => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        $id = DB::table('penalties')->insertGetId([
            'transaction_id'    => $request->transaction_id,
            'penalty_type'      => $request->penalty_type,
            'penalty_amount'    => $request->penalty_amount,
            'overdue_days'      => $request->overdue_days ?? 0,
            'description'       => $request->description,
            'resolved'          => 0,
            'status'            => 1,
            'is_deleted'        => 0,
            'created_by'        => 'api',
            'created_date'      => now(),
            'last_updated_by'   => 'api',
            'last_updated_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penalty berhasil ditambahkan.',
            'data'    => [
                'id'             => $id,
                'transaction_id' => $request->transaction_id,
                'penalty_type'   => $request->penalty_type,
                'penalty_amount' => $request->penalty_amount,
            ],
        ], 201);
    }
}