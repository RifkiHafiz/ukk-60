<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReturnItem;
use Carbon\Carbon;

class ReturnItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReturnItem::create([
            'loan_id' => 6,
            'staff_id' => 3,
            'return_date' => Carbon::now()->subDays(3),
            'condition' => 'good',
            'notes' => 'Semua raket dalam kondisi baik',
        ]);

        ReturnItem::create([
            'loan_id' => 7,
            'staff_id' => 4,
            'return_date' => Carbon::now()->subDays(5),
            'condition' => 'good',
            'notes' => 'Printer berfungsi normal',
        ]);

        ReturnItem::create([
            'loan_id' => 8,
            'staff_id' => 5,
            'return_date' => Carbon::now()->subDays(1),
            'condition' => 'good',
            'notes' => 'Bor dikembalikan dalam kondisi bersih',
        ]);

        ReturnItem::create([
            'loan_id' => 9,
            'staff_id' => 3,
            'return_date' => Carbon::now()->subDays(13),
            'condition' => 'good',
            'notes' => 'Scanner tidak ada masalah',
        ]);

        ReturnItem::create([
            'loan_id' => 10,
            'staff_id' => 4,
            'return_date' => Carbon::now()->subDays(8),
            'condition' => 'damaged',
            'notes' => 'Satu pipet retak, sudah dicatat untuk penggantian',
        ]);
    }
}
