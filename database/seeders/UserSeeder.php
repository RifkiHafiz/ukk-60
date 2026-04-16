<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'full_name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
            'address' => 'Jl. Merdeka No. 1, Jakarta Pusat',
            'role' => 'Admin',
        ]);

        User::create([
            'username' => 'Rifki',
            'full_name' => 'Muhammad Rifki Al Hafiz',
            'email' => 'rifki@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567891',
            'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            'role' => 'Admin',
        ]);

        User::create([
            'username' => 'staff1',
            'full_name' => 'Budi Santoso',
            'email' => 'budi.staff@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567892',
            'address' => 'Jl. Gatot Subroto No. 23, Jakarta Barat',
            'role' => 'Staff',
        ]);

        User::create([
            'username' => 'staff2',
            'full_name' => 'Ani Wijaya',
            'email' => 'ani.staff@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567893',
            'address' => 'Jl. Asia Afrika No. 12, Bandung',
            'role' => 'Staff',
        ]);

        User::create([
            'username' => 'staff3',
            'full_name' => 'Dedi Kurniawan',
            'email' => 'dedi.staff@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567894',
            'address' => 'Jl. Diponegoro No. 67, Surabaya',
            'role' => 'Staff',
        ]);

        User::create([
            'username' => 'borrower1',
            'full_name' => 'Muhammad Rizki',
            'email' => 'rizki@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567895',
            'address' => 'Jl. Pemuda No. 45, Semarang',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower2',
            'full_name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567896',
            'address' => 'Jl. Pahlawan No. 78, Malang',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower3',
            'full_name' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567897',
            'address' => 'Jl. Veteran No. 34, Yogyakarta',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower4',
            'full_name' => 'Rina Kartika',
            'email' => 'rina@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567898',
            'address' => 'Jl. Gajah Mada No. 56, Solo',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower5',
            'full_name' => 'Hendra Gunawan',
            'email' => 'hendra@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567899',
            'address' => 'Jl. Ahmad Yani No. 89, Medan',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower6',
            'full_name' => 'Lina Marlina',
            'email' => 'lina@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567800',
            'address' => 'Jl. Kartini No. 12, Palembang',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower7',
            'full_name' => 'Eko Prasetyo',
            'email' => 'eko@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567801',
            'address' => 'Jl. Imam Bonjol No. 23, Denpasar',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower8',
            'full_name' => 'Fitri Handayani',
            'email' => 'fitri@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567802',
            'address' => 'Jl. Proklamasi No. 45, Makassar',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower9',
            'full_name' => 'Agus Setiawan',
            'email' => 'agus@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567803',
            'address' => 'Jl. Kemerdekaan No. 67, Balikpapan',
            'role' => 'Borrower',
        ]);

        User::create([
            'username' => 'borrower10',
            'full_name' => 'Maya Sari',
            'email' => 'maya@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567804',
            'address' => 'Jl. Cendrawasih No. 90, Pontianak',
            'role' => 'Borrower',
        ]);
    }
}
