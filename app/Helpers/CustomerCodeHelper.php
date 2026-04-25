<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CustomerCodeHelper
{
    /**
     * Assign CUST-xxx ke user jika belum punya kode,
     * atau skip kalau sudah dapat kode sebelumnya.
     */
    public static function assignIfNeeded(int $userId): void
    {
        $user = DB::table('users')->where('id', $userId)->first();

        // Sudah punya kode → skip
        if ($user && $user->company_code && str_starts_with($user->company_code, 'CUST-')) {
            return;
        }

        // Hitung berapa user yang sudah punya CUST-xxx → nomor berikutnya
        $count = DB::table('users')
            ->where('company_code', 'like', 'CUST-%')
            ->where('is_deleted', 0)
            ->count();

        $newCode = 'CUST-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        DB::table('users')->where('id', $userId)->update([
            'company_code'      => $newCode,
            'last_updated_date' => now(),
        ]);
    }
}