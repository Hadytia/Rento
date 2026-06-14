<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
{
    // ── GET ALL ──────────────────────────────────────────────────────────────
    public function apiIndex(Request $request)
    {
        try {
            $query = DB::table('return_requests as rr')
                ->join('transactions as t', 'rr.transaction_id', '=', 't.id')
                ->join('users as u', 't.user_id', '=', 'u.id')
                ->join('products as p', 't.product_id', '=', 'p.id')
                ->where('rr.is_deleted', 0)
                ->select([
                    'rr.id',
                    'rr.transaction_id',
                    'rr.trx_code',
                    'rr.return_condition',
                    'rr.condition_notes',
                    'rr.photo_proof',
                    'rr.status',
                    'rr.rejection_reason',
                    'rr.requested_at',
                    'rr.returned_at',
                    'rr.approved_at',
                    'rr.created_date',
                    'u.name as customer_name',
                    'u.email as customer_email',
                    'p.product_name',
                    't.rental_start',
                    't.rental_end',
                    't.trx_status',
                ]);

            // Filter by status: /api/return-requests?status=Pending
            if ($request->has('status')) {
                $query->where('rr.status', $request->status);
            }

            // Filter by transaction_id: /api/return-requests?transaction_id=1
            if ($request->has('transaction_id')) {
                $query->where('rr.transaction_id', $request->transaction_id);
            }

            $data = $query->orderBy('rr.created_date', 'desc')->get();

            return response()->json([
                'success' => true,
                'total'   => $data->count(),
                'data'    => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pengajuan pengembalian.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ── GET DETAIL ───────────────────────────────────────────────────────────
    public function apiShow($id)
    {
        try {
            $data = DB::table('return_requests as rr')
                ->join('transactions as t', 'rr.transaction_id', '=', 't.id')
                ->join('users as u', 't.user_id', '=', 'u.id')
                ->join('products as p', 't.product_id', '=', 'p.id')
                ->where('rr.id', $id)
                ->where('rr.is_deleted', 0)
                ->select([
                    'rr.id',
                    'rr.transaction_id',
                    'rr.trx_code',
                    'rr.return_condition',
                    'rr.condition_notes',
                    'rr.photo_proof',
                    'rr.status',
                    'rr.rejection_reason',
                    'rr.requested_at',
                    'rr.returned_at',
                    'rr.approved_at',
                    'rr.created_date',
                    'u.name as customer_name',
                    'u.email as customer_email',
                    'p.product_name',
                    't.rental_start',
                    't.rental_end',
                    't.total_amount',
                    't.trx_status',
                ])
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan pengembalian tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pengajuan pengembalian.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ── CREATE ───────────────────────────────────────────────────────────────
    public function apiStore(Request $request)
    {
        try {
            $request->validate([
                'transaction_id'   => 'required|exists:transactions,id',
                'return_condition' => 'required|in:Good,Minor Damage,Major Damage,Lost',
                'condition_notes'  => 'nullable|string',
                'photo_proof'      => 'nullable|string',
            ]);

            // Cek transaksi valid dan statusnya Active/Overdue
            $trx = DB::table('transactions')
                ->where('id', $request->transaction_id)
                ->where('is_deleted', 0)
                ->first();

            if (!$trx) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            if (!in_array($trx->trx_status, ['Active', 'Overdue'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak dapat diajukan pengembalian. Status harus Active atau Overdue.',
                ], 422);
            }

            // Cek apakah sudah ada pengajuan pending untuk transaksi ini
            $existing = DB::table('return_requests')
                ->where('transaction_id', $request->transaction_id)
                ->where('status', 'Pending')
                ->where('is_deleted', 0)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah ada pengajuan pengembalian yang sedang menunggu persetujuan untuk transaksi ini.',
                ], 422);
            }

            $id = DB::table('return_requests')->insertGetId([
                'transaction_id'   => $request->transaction_id,
                'trx_code'         => $trx->trx_code,
                'return_condition' => $request->return_condition,
                'condition_notes'  => $request->condition_notes,
                'photo_proof'      => $request->photo_proof,
                'status'           => 'Pending',
                'rejection_reason' => null,
                'requested_at'     => now(),
                'returned_at'      => null,
                'approved_at'      => null,
                'company_code'     => $trx->company_code,
                'is_deleted'       => 0,
                'created_by'       => 'api',
                'created_date'     => now(),
                'last_updated_by'  => 'api',
                'last_updated_date'=> now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan pengembalian berhasil dibuat.',
                'data'    => [
                    'id'               => $id,
                    'transaction_id'   => $request->transaction_id,
                    'trx_code'         => $trx->trx_code,
                    'return_condition' => $request->return_condition,
                    'status'           => 'Pending',
                    'requested_at'     => now(),
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pengajuan pengembalian.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ── UPDATE STATUS (Approved / Rejected) ──────────────────────────────────
    public function apiUpdate(Request $request, $id)
    {
        try {
            $returnRequest = DB::table('return_requests')
                ->where('id', $id)
                ->where('is_deleted', 0)
                ->first();

            if (!$returnRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan pengembalian tidak ditemukan.',
                ], 404);
            }

            $request->validate([
                'status'           => 'required|in:Approved,Rejected',
                'rejection_reason' => 'nullable|string|required_if:status,Rejected',
                'condition_notes'  => 'nullable|string',
                'return_condition' => 'nullable|in:Good,Minor Damage,Major Damage,Lost',
            ]);

            // Jika Approved — update status transaksi jadi Completed & stok bertambah
            if ($request->status === 'Approved') {
                // Update transaksi jadi Completed
                DB::table('transactions')
                    ->where('id', $returnRequest->transaction_id)
                    ->update([
                        'trx_status'        => 'Completed',
                        'last_updated_by'   => 'api',
                        'last_updated_date' => now(),
                    ]);

                // Tambah stok produk kembali
                $trx = DB::table('transactions')->where('id', $returnRequest->transaction_id)->first();
                if ($trx) {
                    DB::table('products')
                        ->where('id', $trx->product_id)
                        ->increment('stock', 1);
                }
            }

            // Update return_request
            DB::table('return_requests')
                ->where('id', $id)
                ->update([
                    'status'            => $request->status,
                    'rejection_reason'  => $request->rejection_reason,
                    'condition_notes'   => $request->condition_notes ?? $returnRequest->condition_notes,
                    'return_condition'  => $request->return_condition ?? $returnRequest->return_condition,
                    'approved_at'       => $request->status === 'Approved' ? now() : null,
                    'returned_at'       => $request->status === 'Approved' ? now() : null,
                    'last_updated_by'   => 'api',
                    'last_updated_date' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => "Pengajuan pengembalian berhasil di-{$request->status}.",
                'data'    => [
                    'id'               => $id,
                    'transaction_id'   => $returnRequest->transaction_id,
                    'trx_code'         => $returnRequest->trx_code,
                    'status'           => $request->status,
                    'approved_at'      => $request->status === 'Approved' ? now() : null,
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pengajuan pengembalian.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}