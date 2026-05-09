<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_emergency_contact', 20)->nullable()->after('emergency_contact');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('delivery_method', 100)->nullable()->after('payment_method');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_emergency_contact');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('delivery_method');
        });
    }
};
