<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'category_id' => 1,
            'item_image' => 'img/laptop-dell.jpg',
            'item_code' => 'ELEC-001',
            'item_name' => 'Laptop Dell Latitude 5420',
            'total_quantity' => 10,
            'available_quantity' => 7,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 1,
            'item_image' => 'img/tablet-samsung.jpg',
            'item_code' => 'ELEC-002',
            'item_name' => 'Tablet Samsung Galaxy Tab S8',
            'total_quantity' => 8,
            'available_quantity' => 8,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 2,
            'item_image' => 'img/bola-adidas.jpg',
            'item_code' => 'SPORT-001',
            'item_name' => 'Bola Sepak Adidas',
            'total_quantity' => 15,
            'available_quantity' => 12,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 2,
            'item_image' => 'img/raket-yonex.jpg',
            'item_code' => 'SPORT-002',
            'item_name' => 'Raket Badminton Yonex',
            'total_quantity' => 20,
            'available_quantity' => 18,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 3,
            'item_image' => 'img/mikroskop.jpg',
            'item_code' => 'LAB-001',
            'item_name' => 'Mikroskop Digital Olympus',
            'total_quantity' => 5,
            'available_quantity' => 4,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 3,
            'item_image' => 'img/pipet-volume.jpg',
            'item_code' => 'LAB-002',
            'item_name' => 'Pipet Volumetrik Set',
            'total_quantity' => 12,
            'available_quantity' => 10,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 4,
            'item_image' => 'img/printer-laserjet.jpg',
            'item_code' => 'OFF-001',
            'item_name' => 'Printer HP LaserJet Pro',
            'total_quantity' => 6,
            'available_quantity' => 5,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 4,
            'item_image' => 'img/scanner.jpg',
            'item_code' => 'OFF-002',
            'item_name' => 'Scanner Canon LiDE 400',
            'total_quantity' => 4,
            'available_quantity' => 3,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 5,
            'item_image' => 'img/projector.jpg',
            'item_code' => 'AV-001',
            'item_name' => 'Projector Epson EB-X05',
            'total_quantity' => 8,
            'available_quantity' => 6,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 5,
            'item_image' => 'img/kamera-canon.jpg',
            'item_code' => 'AV-002',
            'item_name' => 'Kamera DSLR Canon EOS 90D',
            'total_quantity' => 3,
            'available_quantity' => 2,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 6,
            'item_image' => 'img/bor-listrik.jpg',
            'item_code' => 'TOOL-001',
            'item_name' => 'Bor Listrik Makita HP1630',
            'total_quantity' => 7,
            'available_quantity' => 6,
            'condition' => 'Good',
        ]);

        Item::create([
            'category_id' => 6,
            'item_image' => 'img/gerinda.jpg',
            'item_code' => 'TOOL-002',
            'item_name' => 'Mesin Gerinda Bosch GWS 060',
            'total_quantity' => 5,
            'available_quantity' => 4,
            'condition' => 'Damaged',
        ]);
    }
}
