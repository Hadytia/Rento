<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');
    }

    // ── CREATE SNAP TOKEN ─────────────────────────────────────────────
    public function createSnapToken(Request $request)
    {
        $request->validate([
            'trx_id' => 'required|exists:transactions,id',
        ]);

        $trx = Transaction::with(['user', 'product'])
            ->where('is_deleted', 0)
            ->findOrFail($request->trx_id);

        // Cek apakah sudah ada payment yang pending/success
        // $existingPayment = Payment::where('trx_id', $trx->id)
        //     ->whereIn('transaction_status', ['pending', 'settlement', 'capture'])
        //     ->first();

        // if ($existingPayment && $existingPayment->snap_token) {
        //     return response()->json([
        //         'success'          => true,
        //         'snap_token'       => $existingPayment->snap_token,
        //         'snap_redirect_url'=> $existingPayment->snap_redirect_url,
        //         'message'          => 'Menggunakan token yang sudah ada.',
        //     ]);
        // }

        // Buat order_id unik
        $orderId = 'RENTO-' . $trx->trx_code . '-' . time();

        // Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $trx->total_amount,
            ],
            'customer_details' => [
                'first_name' => $trx->user?->name ?? 'Customer',
                'email'      => $trx->user?->email ?? '',
                'phone'      => $trx->user?->phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => $trx->product?->id,
                    'price'    => (int) $trx->product?->rental_price,
                    'quantity' => $trx->total_days,
                    'name'     => $trx->product?->product_name ?? 'Rental Item',
                ],
            ],
        ];

        try {
            $snapToken       = \Midtrans\Snap::getSnapToken($params);
            $snapRedirectUrl = \Midtrans\Snap::getSnapUrl($params);

            // Simpan ke tabel payments
            Payment::create([
                'order_id'           => $orderId,
                'trx_id'             => $trx->id,
                'transaction_status' => 'pending',
                'gross_amount'       => $trx->total_amount,
                'snap_token'         => $snapToken,
                'snap_redirect_url'  => $snapRedirectUrl,
                'currency'           => 'IDR',
                'created_by'         => 'api',
                'created_date'       => now(),
                'last_updated_by'    => 'api',
                'last_updated_date'  => now(),
            ]);

            return response()->json([
                'success'           => true,
                'snap_token'        => $snapToken,
                'snap_redirect_url' => $snapRedirectUrl,
                'order_id'          => $orderId,
                'gross_amount'      => $trx->total_amount,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi Midtrans: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── NOTIFICATION CALLBACK dari Midtrans ───────────────────────────
    public function notification(Request $request)
    {
        $data = $request->all();

        // Validasi signature key
        $orderId     = $data['order_id']     ?? '';
        $statusCode  = $data['status_code']  ?? '';
        $grossAmount = $data['gross_amount'] ?? '';
        $serverKey   = config('midtrans.server_key');

        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== ($data['signature_key'] ?? '')) {
            Log::warning('Midtrans: Invalid signature for order ' . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari payment berdasarkan order_id
        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $data['transaction_status'] ?? '';
        $fraudStatus       = $data['fraud_status'] ?? '';
        $paymentType       = $data['payment_type'] ?? '';

        // Tentukan status final
        $finalStatus = $transactionStatus;
        if ($transactionStatus === 'capture') {
            $finalStatus = $fraudStatus === 'accept' ? 'settlement' : 'deny';
        }

        // Update tabel payments
        $payment->update([
            'midtrans_transaction_id' => $data['transaction_id']  ?? null,
            'transaction_status'      => $finalStatus,
            'payment_type'            => $paymentType,
            'fraud_status'            => $fraudStatus,
            'bank'                    => $data['va_numbers'][0]['bank'] ?? ($data['bank'] ?? null),
            'va_number'               => $data['va_numbers'][0]['va_number'] ?? ($data['permata_va_number'] ?? null),
            'transaction_time'        => $data['transaction_time']  ?? null,
            'settlement_time'         => $data['settlement_time']   ?? null,
            'last_updated_by'         => 'midtrans',
            'last_updated_date'       => now(),
        ]);

        // Update status transaksi di tabel transactions
        if ($payment->trx_id) {
            $trx = Transaction::find($payment->trx_id);
            if ($trx) {
                if (in_array($finalStatus, ['settlement', 'capture'])) {
                    $trx->update([
                        'trx_status'        => 'Active',
                        'paid_amount'       => $data['gross_amount'] ?? $trx->paid_amount,
                        'payment_method'    => $paymentType,
                        'last_updated_by'   => 'midtrans',
                        'last_updated_date' => now(),
                    ]);
                } elseif (in_array($finalStatus, ['cancel', 'expire', 'failure'])) {
                    $trx->update([
                        'trx_status'        => 'Cancelled',
                        'last_updated_by'   => 'midtrans',
                        'last_updated_date' => now(),
                    ]);
                }
            }
        }

        Log::info('Midtrans notification processed: ' . $orderId . ' → ' . $finalStatus);

        return response()->json(['message' => 'OK'], 200);
    }

    // ── CEK STATUS PAYMENT ────────────────────────────────────────────
    public function checkStatus($orderId)
    {
        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'order_id'           => $payment->order_id,
                'transaction_status' => $payment->transaction_status,
                'payment_type'       => $payment->payment_type,
                'gross_amount'       => $payment->gross_amount,
                'bank'               => $payment->bank,
                'va_number'          => $payment->va_number,
                'settlement_time'    => $payment->settlement_time,
                'snap_token'         => $payment->snap_token,
                'snap_redirect_url'  => $payment->snap_redirect_url,
            ],
        ]);
    }
}