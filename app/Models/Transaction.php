<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = false;

    protected $fillable = [
        'trx_code', 'user_id', 'product_id',
        'rental_start', 'rental_end', 'total_days',
        'total_amount', 'paid_amount', 'payment_method',
        'trx_status', 'notes',
        'company_code', 'status', 'is_deleted',
        'created_by', 'created_date',
        'last_updated_by', 'last_updated_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'trx_id');
    }
}