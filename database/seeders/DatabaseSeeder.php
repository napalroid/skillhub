<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Admin user
        $admin = User::firstOrCreate(
            ['email' => 'naufalnail58@gmail.com'],
            [
                'name' => 'Admin SkillHub',
                'first_name' => 'Admin',
                'last_name' => 'SkillHub',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // 2. Regular user (seller)
        $seller = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Joko Seller',
                'first_name' => 'Joko',
                'last_name' => 'Seller',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081234567891',
                'email_verified_at' => now(),
            ]
        );

        // 3. Regular user (buyer)
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@example.com'],
            [
                'name' => 'Budi Buyer',
                'first_name' => 'Budi',
                'last_name' => 'Buyer',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081234567892',
                'email_verified_at' => now(),
            ]
        );

        // 4. Categories & Subcategories
        $categoriesData = [
            'Desain & Grafis' => ['Desain Logo', 'Desain Poster', 'Desain Sosial Media', 'Ilustrasi'],
            'Website & Teknologi' => ['Website Landing Page', 'Website Company Profile', 'E-commerce', 'Aplikasi Mobile'],
            'Foto & Video' => ['Fotografi Produk', 'Video Editing', 'Videografi Acara'],
            'Tulis & Terjemah' => ['Article Writing', 'Copywriting', 'Terjemah Bahasa Inggris'],
            'Bisnis & Marketing' => ['Social Media Management', 'Content Planning', 'Email Marketing'],
        ];

        foreach ($categoriesData as $catName => $subcats) {
            $category = Category::firstOrCreate(['name' => $catName]);
            foreach ($subcats as $subName) {
                Subcategory::firstOrCreate([
                    'category_id' => $category->id,
                    'name' => $subName,
                ]);
            }
        }

        // 5. Services (approved, by seller)
        $subcats = Subcategory::all();
        $servicesData = [
            [
                'title' => 'Desain Logo Profesional Modern',
                'subcategory' => 'Desain Logo',
                'price' => 150000,
                'description' => 'Desain logo minimalis, modern, cocok untuk brand UMKM/startup. Include 3 konsep, revisi unlimited, file source AI/EPS/PNG.',
            ],
            [
                'title' => 'Website Landing Page React + Tailwind',
                'subcategory' => 'Website Landing Page',
                'price' => 500000,
                'description' => 'Landing page responsive, SEO friendly, animasi smooth. Stack: React, Tailwind, Vite. Deploy ke Vercel/Netlify gratis.',
            ],
            [
                'title' => 'Edit Video Reels/TikTok Viral',
                'subcategory' => 'Video Editing',
                'price' => 100000,
                'description' => 'Editing video pendek 30-60 detik, caption auto, transisi trend, musik bebas hak cipta. Joki 5 video/bulan.',
            ],
            [
                'title' => 'Article SEO Friendly 1000+ Kata',
                'subcategory' => 'Article Writing',
                'price' => 75000,
                'description' => 'Artikel blog/website 1000+ kata, riset keyword, struktur H1-H3, meta description, plagiarism check. Topik bebas.',
            ],
            [
                'title' => 'Jasa Social Media Manager Bulanan',
                'subcategory' => 'Social Media Management',
                'price' => 1000000,
                'description' => 'Kelola IG/TikTok 1 bulan: 12 konten feed + 12 reels, jadwal posting, balas komentar, laporan insight mingguan.',
            ],
            [
                'title' => 'Desain Poster Kegiatan Sekolah/OSIS',
                'subcategory' => 'Desain Poster',
                'price' => 50000,
                'description' => 'Poster acara sekolah/OSIS/UKM: ukuran A3/A4, desain menarik, revisi 2x, file PDF + JPG siap cetak.',
            ],
        ];

        foreach ($servicesData as $data) {
            $subcat = $subcats->where('name', $data['subcategory'])->first();
            if (! $subcat) continue;

            Service::firstOrCreate(
                ['title' => $data['title'], 'user_id' => $seller->id],
                [
                    'subcategory_id' => $subcat->id,
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'status' => 'approved',
                    'image' => null,
                    'portfolio_images' => null,
                ]
            );
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: naufalnail58@gmail.com / password123');
        $this->command->info('Seller: seller@example.com / password123');
        $this->command->info('Buyer: buyer@example.com / password123');
    }
}