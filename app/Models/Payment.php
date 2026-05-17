<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'trx_id',
        'midtrans_transaction_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'signature_key',
        'fraud_status',
        'bank',
        'va_number',
        'currency',
        'snap_token',
        'snap_redirect_url',
        'transaction_time',
        'settlement_time',
        'status',
        'is_deleted',
        'created_by',
        'created_date',
        'last_updated_by',
        'last_updated_date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'trx_id');
    }
}