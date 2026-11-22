<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['code' => 'AGT-0001', 'name' => 'Andi Pratama', 'email' => 'andi@example.com', 'phone' => '081234567890', 'address' => 'Jl. Merdeka No. 10'],
            ['code' => 'AGT-0002', 'name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081234567891', 'address' => 'Jl. Sudirman No. 25'],
            ['code' => 'AGT-0003', 'name' => 'Citra Lestari', 'email' => 'citra@example.com', 'phone' => '081234567892', 'address' => 'Jl. Diponegoro No. 8'],
            ['code' => 'AGT-0004', 'name' => 'Dewi Anggraini', 'email' => 'dewi@example.com', 'phone' => '081234567893', 'address' => 'Jl. Pahlawan No. 15'],
            ['code' => 'AGT-0005', 'name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'phone' => '081234567894', 'address' => 'Jl. Gajah Mada No. 42'],
        ];

        foreach ($members as $data) {
            Member::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
