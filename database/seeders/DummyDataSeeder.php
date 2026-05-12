<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── 20 Pelanggan Baru ─────────────────────────────────────────────
        $users = [
            ['name'=>'Budi Santoso',    'email'=>'budi.santoso@gmail.com',    'phone'=>'081211110001', 'address'=>'Jl. Mawar No. 1, Jakarta',    'id_card_number'=>'3171010101010001'],
            ['name'=>'Siti Rahayu',     'email'=>'siti.rahayu@gmail.com',     'phone'=>'081211110002', 'address'=>'Jl. Melati No. 2, Bandung',   'id_card_number'=>'3271010101010002'],
            ['name'=>'Ahmad Fauzi',     'email'=>'ahmad.fauzi@gmail.com',     'phone'=>'081211110003', 'address'=>'Jl. Kenanga No. 3, Surabaya', 'id_card_number'=>'3571010101010003'],
            ['name'=>'Dewi Lestari',    'email'=>'dewi.lestari@gmail.com',    'phone'=>'081211110004', 'address'=>'Jl. Anggrek No. 4, Medan',    'id_card_number'=>'1271010101010004'],
            ['name'=>'Rizky Pratama',   'email'=>'rizky.pratama@gmail.com',   'phone'=>'081211110005', 'address'=>'Jl. Dahlia No. 5, Yogyakarta','id_card_number'=>'3471010101010005'],
            ['name'=>'Nurul Hidayah',   'email'=>'nurul.hidayah@gmail.com',   'phone'=>'081211110006', 'address'=>'Jl. Tulip No. 6, Semarang',   'id_card_number'=>'3371010101010006'],
            ['name'=>'Fajar Setiawan',  'email'=>'fajar.setiawan@gmail.com',  'phone'=>'081211110007', 'address'=>'Jl. Flamboyan No. 7, Bogor',  'id_card_number'=>'3201010101010007'],
            ['name'=>'Rina Wulandari',  'email'=>'rina.wulandari@gmail.com',  'phone'=>'081211110008', 'address'=>'Jl. Bougenville No. 8, Depok','id_card_number'=>'3276010101010008'],
            ['name'=>'Hendra Gunawan',  'email'=>'hendra.gunawan@gmail.com',  'phone'=>'081211110009', 'address'=>'Jl. Cempaka No. 9, Tangerang','id_card_number'=>'3603010101010009'],
            ['name'=>'Maya Anggraini',  'email'=>'maya.anggraini@gmail.com',  'phone'=>'081211110010', 'address'=>'Jl. Seroja No. 10, Bekasi',   'id_card_number'=>'3275010101010010'],
            ['name'=>'Doni Firmansyah', 'email'=>'doni.firmansyah@gmail.com', 'phone'=>'081211110011', 'address'=>'Jl. Teratai No. 11, Malang',  'id_card_number'=>'3573010101010011'],
            ['name'=>'Putri Handayani', 'email'=>'putri.handayani@gmail.com', 'phone'=>'081211110012', 'address'=>'Jl. Lavender No. 12, Solo',   'id_card_number'=>'3372010101010012'],
            ['name'=>'Wahyu Hidayat',   'email'=>'wahyu.hidayat@gmail.com',   'phone'=>'081211110013', 'address'=>'Jl. Sakura No. 13, Makassar', 'id_card_number'=>'7371010101010013'],
            ['name'=>'Indah Permata',   'email'=>'indah.permata@gmail.com',   'phone'=>'081211110014', 'address'=>'Jl. Kamboja No. 14, Palembang','id_card_number'=>'1671010101010014'],
            ['name'=>'Rudi Hartono',    'email'=>'rudi.hartono@gmail.com',    'phone'=>'081211110015', 'address'=>'Jl. Nusa Indah No. 15, Pekanbaru','id_card_number'=>'1471010101010015'],
            ['name'=>'Fitri Yanti',     'email'=>'fitri.yanti@gmail.com',     'phone'=>'081211110016', 'address'=>'Jl. Melati No. 16, Padang',   'id_card_number'=>'1371010101010016'],
            ['name'=>'Agus Hermawan',   'email'=>'agus.hermawan@gmail.com',   'phone'=>'081211110017', 'address'=>'Jl. Mawar No. 17, Banjarmasin','id_card_number'=>'6371010101010017'],
            ['name'=>'Lina Marlina',    'email'=>'lina.marlina@gmail.com',    'phone'=>'081211110018', 'address'=>'Jl. Anggrek No. 18, Balikpapan','id_card_number'=>'6471010101010018'],
            ['name'=>'Eko Prasetyo',    'email'=>'eko.prasetyo@gmail.com',    'phone'=>'081211110019', 'address'=>'Jl. Dahlia No. 19, Manado',   'id_card_number'=>'7171010101010019'],
            ['name'=>'Yuli Astuti',     'email'=>'yuli.astuti@gmail.com',     'phone'=>'081211110020', 'address'=>'Jl. Kenanga No. 20, Denpasar','id_card_number'=>'5171010101010020'],
        ];

        $userIds = [];
        foreach ($users as $i => $u) {
            $custCode = 'CUST-' . str_pad(100 + $i + 1, 3, '0', STR_PAD_LEFT);
            $id = DB::table('users')->insertGetId([
                'name'              => $u['name'],
                'email'             => $u['email'],
                'password'          => bcrypt('password123'),
                'phone'             => $u['phone'],
                'address'           => $u['address'],
                'id_card_number'    => $u['id_card_number'],
                'emergency_contact' => 'Keluarga ' . explode(' ', $u['name'])[0],
                'no_emergency_contact' => '0812111' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'company_code'      => $custCode,
                'status'            => 1,
                'is_deleted'        => 0,
                'created_by'        => 'seeder',
                'created_date'      => now()->subDays(rand(30, 180)),
                'last_updated_by'   => 'seeder',
                'last_updated_date' => now(),
            ]);
            $userIds[] = $id;
        }

        // ── Ambil product IDs yang ada ────────────────────────────────────
        $productIds = DB::table('products')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();

        // ── Generate 80 Transaksi tersebar Jan - Mei 2026 ─────────────────
        $statuses        = ['Active', 'Completed', 'Completed', 'Completed', 'Overdue', 'Cancelled'];
        $paymentMethods  = ['Cash', 'Transfer', 'QRIS', 'Debit/Kredit'];
        $deliveryMethods = ['Pickup', 'Delivery', 'COD'];

        // Distribusi tanggal per bulan untuk laporan beragam
        $dateRanges = [
            // Januari 2026
            ['start' => '2026-01-03', 'end' => '2026-01-28'],
            // Februari 2026
            ['start' => '2026-02-02', 'end' => '2026-02-25'],
            // Maret 2026
            ['start' => '2026-03-01', 'end' => '2026-03-29'],
            // April 2026
            ['start' => '2026-04-01', 'end' => '2026-04-28'],
            // Mei 2026
            ['start' => '2026-05-01', 'end' => '2026-05-11'],
        ];

        $trxCount = 0;
        foreach ($dateRanges as $range) {
            // Jumlah transaksi per bulan: Jan-Apr ~16 transaksi, Mei ~8
            $perMonth = ($range['start'] >= '2026-05-01') ? 8 : 16;

            for ($i = 0; $i < $perMonth; $i++) {
                $userId    = $userIds[array_rand($userIds)];
                $productId = $productIds[array_rand($productIds)];
                $product   = DB::table('products')->where('id', $productId)->first();

                // Random tanggal dalam range bulan
                $startDate = Carbon::parse($range['start'])->addDays(rand(0, 25));
                $days      = rand(1, 7);
                $endDate   = $startDate->copy()->addDays($days - 1);

                $totalAmt  = $days * $product->rental_price;
                $status    = $statuses[array_rand($statuses)];

                // Transaksi Mei kebanyakan Active
                if ($range['start'] >= '2026-05-01') {
                    $status = rand(0, 3) === 0 ? 'Completed' : 'Active';
                }

                // Transaksi lama (Jan-Mar) kebanyakan Completed
                if ($range['start'] <= '2026-03-01') {
                    $status = rand(0, 5) === 0 ? 'Overdue' : (rand(0, 5) === 0 ? 'Cancelled' : 'Completed');
                }

                $paidAmt = $status === 'Cancelled' ? 0 : ($status === 'Active' ? rand(0, 1) * $totalAmt : $totalAmt);

                do {
                    $trxCode = 'TRX-' . $startDate->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
                } while (DB::table('transactions')->where('trx_code', $trxCode)->exists());

                DB::table('transactions')->insert([
                    'trx_code'          => $trxCode,
                    'user_id'           => $userId,
                    'product_id'        => $productId,
                    'rental_start'      => $startDate->toDateString(),
                    'rental_end'        => $endDate->toDateString(),
                    'total_days'        => $days,
                    'total_amount'      => $totalAmt,
                    'paid_amount'       => $paidAmt,
                    'payment_method'    => $paymentMethods[array_rand($paymentMethods)],
                    'delivery_method'   => $deliveryMethods[array_rand($deliveryMethods)],
                    'trx_status'        => $status,
                    'notes'             => null,
                    'company_code'      => '',
                    'status'            => 1,
                    'is_deleted'        => 0,
                    'created_by'        => 'seeder',
                    'created_date'      => $startDate->copy()->subHours(rand(1, 5)),
                    'last_updated_by'   => 'seeder',
                    'last_updated_date' => now(),
                ]);

                $trxCount++;
            }
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('👤 ' . count($userIds) . ' pelanggan baru ditambahkan.');
        $this->command->info('📋 ' . $trxCount . ' transaksi baru ditambahkan.');
    }
}