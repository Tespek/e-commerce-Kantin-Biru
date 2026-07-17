<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KantinBiruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Isi Data Akun User Default
        DB::table('users')->insert([
            [
                'nama' => 'Admin Kantin Biru',
                'email' => 'admin@kantinbiru.com',
                'password' => Hash::make('admin123'), // Mengamankan password dengan bcrypt Laravel
                'no_hp' => '081234567890',
                'alamat' => 'Kantin Utama',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Setiawan',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('pembeli123'),
                'no_hp' => '085277112233',
                'alamat' => 'Jalan Merdeka No. 12',
                'role' => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Isi Data Kategori Makanan
        $idRisol = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Risol',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idBolu = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Bolu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Isi Data Katalog Menu Awal
        DB::table('produk')->insert([
            [
                'id_kategori' => $idRisol,
                'nama_produk' => 'Risol Ayam Suwir Mayo',
                'harga' => 3000,
                'stok' => 45,
                'deskripsi' => 'Risol renyah dengan isian ayam suwir pedas manis dicampur mayones lezat.',
                'foto' => 'risol_mayo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $idBolu,
                'nama_produk' => 'Bolu Pandan Kukus',
                'harga' => 35000,
                'stok' => 10,
                'deskripsi' => 'Bolu kukus rasa pandan asli, tekstur sangat lembut dan wangi.',
                'foto' => 'bolu_pandan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $idRisol,
                'nama_produk' => 'Risol Ragout Sapi',
                'harga' => 4000,
                'stok' => 30,
                'deskripsi' => 'Risol premium dengan isian wortel, kentang, dan daging sapi cincang gurih.',
                'foto' => 'risol_ragout.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}