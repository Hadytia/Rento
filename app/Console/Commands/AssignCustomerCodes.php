<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignCustomerCodes extends Command
{
    protected $signature   = 'rento:assign-customer-codes';
    protected $description = 'Assign CUST-001, CUST-002, ... ke user yang sudah punya transaksi, urut by user_id ASC';

    public function handle()
    {
        // Ambil distinct user_id yang punya transaksi, urut user_id ASC
        $userIds = DB::table('transactions')
            ->whereNotNull('user_id')
            ->where('is_deleted', 0)
            ->distinct()
            ->orderBy('user_id', 'asc')
            ->pluck('user_id');

        $counter = 1;
        foreach ($userIds as $userId) {
            $code = 'CUST-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            DB::table('users')->where('id', $userId)->update([
                'company_code'      => $code,
                'last_updated_date' => now(),
            ]);
            $this->info("User ID {$userId} → {$code}");
            $counter++;
        }

        $this->info("Selesai. Total {$userIds->count()} user diberi kode.");
    }
}