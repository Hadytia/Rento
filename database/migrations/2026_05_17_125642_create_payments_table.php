<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->bigInteger('trx_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('gross_amount', 15, 2)->nullable();
            $table->string('signature_key', 512)->nullable();
            $table->string('fraud_status')->nullable();
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->string('snap_token')->nullable();
            $table->string('snap_redirect_url', 512)->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->string('created_by', 32)->nullable();
            $table->datetime('created_date')->useCurrent();
            $table->string('last_updated_by', 32)->nullable();
            $table->datetime('last_updated_date')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};