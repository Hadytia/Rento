<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Produk;
use App\Models\User;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomerCodeHelper;

class TransaksiController extends Controller
{
    use CheckEditAccess;

    // ── INDEX (dialihkan ke reports) ──────────────────────────────────────────
    public function index()
    {
        return redirect()->route('reports.index');
    }

    // ── CREATE ────────────────────────────────────────────────────────────────
    public function create()
    {
        if ($deny = $this->checkEditAccess()) return $deny;

        $produks = Produk::where('is_deleted', 0)
                         ->where('status', 1)
                         ->orderBy('product_name')
                         ->get();

        $users = User::where('is_deleted', 0)
                     ->where('status', 1)
                     ->orderBy('name')
                     ->get();

        return view('transaksi.create', compact('produks', 'users'));
    }

    // ── STORE ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if ($deny = $this->checkEditAccess()) return $deny;

        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'product_id'     => 'required|exists:products,id',
            'rental_start'   => 'required|date',
            'rental_end'     => 'required|date|after_or_equal:rental_start',
            'paid_amount'    => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'notes'          => 'nullable|string',
        ]);

        $produk     = Produk::findOrFail($request->product_id);
        $start      = \Carbon\Carbon::parse($request->rental_start);
        $end        = \Carbon\Carbon::parse($request->rental_end);
        $totalDays  = $start->diffInDays($end) + 1;
        $totalAmt   = $totalDays * $produk->rental_price;

        do {
            $trxCode = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        } while (Transaction::where('trx_code', $trxCode)->exists());

        $transaction = Transaction::create([
            'trx_code'          => $trxCode,
            'user_id'           => $request->user_id,
            'product_id'        => $request->product_id,
            'rental_start'      => $request->rental_start,
            'rental_end'        => $request->rental_end,
            'total_days'        => $totalDays,
            'total_amount'      => $totalAmt,
            'paid_amount'       => $request->paid_amount ?? 0,
            'payment_method'    => $request->payment_method,
            'trx_status'        => 'Active',
            'notes'             => $request->notes,
            'status'            => 1,
            'is_deleted'        => 0,
            'created_by'        => Auth::user()->name,
            'created_date'      => now(),
            'last_updated_by'   => Auth::user()->name,
            'last_updated_date' => now(),
        ]);

        // Auto-assign CUST code ke user jika belum punya
        CustomerCodeHelper::assignIfNeeded($transaction->user_id);

        return redirect()->route('reports.index')
                         ->with('success', "Transaksi {$trxCode} berhasil dibuat.");
    }

    // ── EDIT ──────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        if ($deny = $this->checkEditAccess()) return $deny;

        $trx = Transaction::where('is_deleted', 0)->findOrFail($id);

        $produks = Produk::where('is_deleted', 0)
                         ->where('status', 1)
                         ->orderBy('product_name')
                         ->get();

        $users = User::where('is_deleted', 0)
                     ->where('status', 1)
                     ->orderBy('name')
                     ->get();

        return view('transaksi.edit', compact('trx', 'produks', 'users'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        if ($deny = $this->checkEditAccess()) return $deny;

        $trx = Transaction::where('is_deleted', 0)->findOrFail($id);

        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'product_id'     => 'required|exists:products,id',
            'rental_start'   => 'required|date',
            'rental_end'     => 'required|date|after_or_equal:rental_start',
            'paid_amount'    => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'trx_status'     => 'required|in:Active,Completed,Overdue,Cancelled',
            'notes'          => 'nullable|string',
        ]);

        $produk    = Produk::findOrFail($request->product_id);
        $start     = \Carbon\Carbon::parse($request->rental_start);
        $end       = \Carbon\Carbon::parse($request->rental_end);
        $totalDays = $start->diffInDays($end) + 1;
        $totalAmt  = $totalDays * $produk->rental_price;

        $trx->update([
            'user_id'           => $request->user_id,
            'product_id'        => $request->product_id,
            'rental_start'      => $request->rental_start,
            'rental_end'        => $request->rental_end,
            'total_days'        => $totalDays,
            'total_amount'      => $totalAmt,
            'paid_amount'       => $request->paid_amount ?? 0,
            'payment_method'    => $request->payment_method,
            'trx_status'        => $request->trx_status,
            'notes'             => $request->notes,
            'last_updated_by'   => Auth::user()->name,
            'last_updated_date' => now(),
        ]);

        return redirect()->route('reports.index')
                         ->with('success', "Transaksi {$trx->trx_code} berhasil diperbarui.");
    }

    // ── RETURN (ubah status → Completed) ─────────────────────────────────────
    public function returnItem(Request $request, $id)
    {
        if ($deny = $this->checkEditAccess()) return $deny;

        $trx = Transaction::where('is_deleted', 0)->findOrFail($id);

        $today   = \Carbon\Carbon::today();
        $rentEnd = \Carbon\Carbon::parse($trx->rental_end);
        $overdue = $today->gt($rentEnd) ? $today->diffInDays($rentEnd) : 0;

        $trx->update([
            'trx_status'        => 'Completed',
            'last_updated_by'   => Auth::user()->name,
            'last_updated_date' => now(),
        ]);

        if ($overdue > 0) {
            $penaltyAmt = $overdue * ($trx->product->rental_price ?? 0);

            \App\Models\Penalty::create([
                'transaction_id'    => $trx->id,
                'penalty_type'      => 'Overdue',
                'penalty_amount'    => $penaltyAmt,
                'overdue_days'      => $overdue,
                'description'       => "Keterlambatan {$overdue} hari untuk transaksi {$trx->trx_code}",
                'resolved'          => 0,
                'status'            => 1,
                'is_deleted'        => 0,
                'created_by'        => Auth::user()->name,
                'created_date'      => now(),
                'last_updated_by'   => Auth::user()->name,
                'last_updated_date' => now(),
            ]);

            return redirect()->route('reports.index')
                             ->with('success', "Barang dikembalikan. Penalty {$overdue} hari (Rp " . number_format($penaltyAmt, 0, ',', '.') . ") telah dibuat.");
        }

        return redirect()->route('reports.index')
                         ->with('success', "Transaksi {$trx->trx_code} berhasil dikembalikan.");
    }

    // ── DESTROY (soft delete) ─────────────────────────────────────────────────
    public function destroy($id)
    {
        if ($deny = $this->checkEditAccess()) return $deny;

        $trx = Transaction::where('is_deleted', 0)->findOrFail($id);

        $trx->update([
            'is_deleted'        => 1,
            'last_updated_by'   => Auth::user()->name,
            'last_updated_date' => now(),
        ]);

        return redirect()->route('reports.index')
                         ->with('success', "Transaksi {$trx->trx_code} berhasil dihapus.");
    }
}