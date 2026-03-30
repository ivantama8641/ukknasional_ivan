<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        // 2. Create Guru Template
        User::create([
            'name' => 'Budi Guru',
            'email' => 'guru@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'nis_nip' => '198001012010011001',
        ]);

        // 3. Create Categories
        $categories = [
            ['name' => 'Fasilitas Sekolah', 'description' => 'Kerusakan sarana dan prasarana', 'icon' => 'fas fa-building', 'color' => '#3b82f6'],
            ['name' => 'Bullying', 'description' => 'Kasus perundungan di sekolah', 'icon' => 'fas fa-users-slash', 'color' => '#ef4444'],
            ['name' => 'Akademik', 'description' => 'Terkait KBM dan nilai', 'icon' => 'fas fa-book-reader', 'color' => '#10b981'],
            ['name' => 'Pelanggaran', 'description' => 'Siswa melanggar aturan', 'icon' => 'fas fa-exclamation-triangle', 'color' => '#f59e0b'],
            ['name' => 'Lainnya', 'description' => 'Topik lain-lain', 'icon' => 'fas fa-ellipsis-h', 'color' => '#6b7280'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
