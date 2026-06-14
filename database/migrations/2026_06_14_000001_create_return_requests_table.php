<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            // ── PRIMARY ──────────────────────────────────────
            $table->id();

            // ── RELASI ───────────────────────────────────────
            $table->unsignedBigInteger('transaction_id');
            $table->string('trx_code');

            // ── KONDISI BARANG ────────────────────────────────
            $table->enum('return_condition', [
                'Good',
                'Minor Damage',
                'Major Damage',
                'Lost',
            ]);
            $table->text('condition_notes')->nullable();
            $table->string('photo_proof')->nullable();

            // ── STATUS PENGAJUAN ──────────────────────────────
            $table->enum('status', [
                'Pending',
                'Approved',
                'Rejected',
            ])->default('Pending');
            $table->text('rejection_reason')->nullable();

            // ── WAKTU ─────────────────────────────────────────
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            // ── AUDIT (konsisten dengan tabel lain) ───────────
            $table->string('company_code')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->string('created_by')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->string('last_updated_by')->nullable();
            $table->timestamp('last_updated_date')->nullable();

            // ── INDEX (tanpa foreign key constraint) ──────────
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};