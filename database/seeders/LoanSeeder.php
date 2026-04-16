<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Updated to reflect new features:
     * - staff_id is null for submitted/cancelled loans (not yet processed)
     * - staff_id filled when approved/rejected by staff or admin
     * - rejected_reason filled for rejected loans
     * - rejected_reason filled for cancelled loans (as cancellation reason)
     * - New 'cancelled' and 'borrowed' statuses included
     */
    public function run(): void
    {
        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd') . '-001',
            'borrower_id' => 6,
            'staff_id'    => null,
            'item_id'     => 1,
            'quantity'    => 2,
            'loan_date'   => Carbon::now()->addDays(1),
            'return_date' => Carbon::now()->addDays(8),
            'notes'       => 'Untuk keperluan presentasi project',
            'status'      => 'submitted',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd') . '-002',
            'borrower_id' => 7,
            'staff_id'    => null,
            'item_id'     => 10,
            'quantity'    => 1,
            'loan_date'   => Carbon::now()->addDays(2),
            'return_date' => Carbon::now()->addDays(5),
            'notes'       => 'Dokumentasi acara kampus',
            'status'      => 'submitted',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-5 days')) . '-003',
            'borrower_id' => 8,
            'staff_id'    => 3,
            'item_id'     => 3,
            'quantity'    => 3,
            'loan_date'   => Carbon::now()->subDays(5),
            'return_date' => Carbon::now()->addDays(2),
            'notes'       => 'Untuk latihan tim futsal',
            'status'      => 'approved',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-3 days')) . '-004',
            'borrower_id' => 9,
            'staff_id'    => 4,
            'item_id'     => 9,
            'quantity'    => 2,
            'loan_date'   => Carbon::now()->subDays(3),
            'return_date' => Carbon::now()->addDays(4),
            'notes'       => 'Seminar nasional pendidikan',
            'status'      => 'approved',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-7 days')) . '-005',
            'borrower_id' => 10,
            'staff_id'    => 5,
            'item_id'     => 5,
            'quantity'    => 1,
            'loan_date'   => Carbon::now()->subDays(7),
            'return_date' => Carbon::now()->addDays(7),
            'notes'       => 'Penelitian biologi',
            'status'      => 'borrowed',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-10 days')) . '-006',
            'borrower_id' => 11,
            'staff_id'    => 3,
            'item_id'     => 4,
            'quantity'    => 2,
            'loan_date'   => Carbon::now()->subDays(10),
            'return_date' => Carbon::now()->subDays(3),
            'notes'       => 'Turnamen badminton antar fakultas',
            'status'      => 'waiting',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-12 days')) . '-007',
            'borrower_id' => 12,
            'staff_id'    => 4,
            'item_id'     => 7,
            'quantity'    => 1,
            'loan_date'   => Carbon::now()->subDays(12),
            'return_date' => Carbon::now()->subDays(5),
            'notes'       => 'Print makalah ujian akhir',
            'status'      => 'waiting',
        ]);

        Loan::create([
            'loan_code'       => 'LOAN-' . date('Ymd', strtotime('-8 days')) . '-008',
            'borrower_id'     => 13,
            'staff_id'        => 3,
            'item_id'         => 11,
            'quantity'        => 1,
            'loan_date'       => Carbon::now()->subDays(8),
            'return_date'     => Carbon::now()->subDays(1),
            'notes'           => 'Proyek furniture mahasiswa',
            'rejected_reason' => 'Stok item sedang tidak tersedia, semua unit sedang dipinjam oleh peminjam lain.',
            'status'          => 'rejected',
        ]);

        Loan::create([
            'loan_code'       => 'LOAN-' . date('Ymd', strtotime('-4 days')) . '-009',
            'borrower_id'     => 14,
            'staff_id'        => 2,
            'item_id'         => 8,
            'quantity'        => 1,
            'loan_date'       => Carbon::now()->addDays(1),
            'return_date'     => Carbon::now()->addDays(6),
            'notes'           => 'Digitalisasi dokumen arsip',
            'rejected_reason' => 'Pengajuan tidak menyertakan surat izin dari kepala departemen. Harap lengkapi dokumen dan ajukan ulang.',
            'status'          => 'rejected',
        ]);

        Loan::create([
            'loan_code'       => 'LOAN-' . date('Ymd', strtotime('-6 days')) . '-010',
            'borrower_id'     => 15,
            'staff_id'        => null,
            'item_id'         => 6,
            'quantity'        => 2,
            'loan_date'       => Carbon::now()->addDays(1),
            'return_date'     => Carbon::now()->addDays(7),
            'notes'           => 'Praktikum kimia organik',
            'rejected_reason' => 'Praktikum ditunda oleh dosen, barang tidak jadi diperlukan untuk saat ini.',
            'status'          => 'cancelled',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-20 days')) . '-011',
            'borrower_id' => 6,
            'staff_id'    => 5,
            'item_id'     => 2,
            'quantity'    => 1,
            'loan_date'   => Carbon::now()->subDays(20),
            'return_date' => Carbon::now()->subDays(13),
            'notes'       => 'Presentasi tugas akhir semester',
            'status'      => 'returned',
        ]);

        Loan::create([
            'loan_code'   => 'LOAN-' . date('Ymd', strtotime('-15 days')) . '-012',
            'borrower_id' => 7,
            'staff_id'    => 3,
            'item_id'     => 12,
            'quantity'    => 1,
            'loan_date'   => Carbon::now()->subDays(15),
            'return_date' => Carbon::now()->subDays(8),
            'notes'       => 'Pemasangan dekorasi aula kampus',
            'status'      => 'returned',
        ]);
    }
}
