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
            'Foto & Video' => ['Edit Reels/TikTok', 'Videografi Acara', 'Dokumentasi Event', 'Foto Acara Sekolah'],
            'Konten & Media' => ['Bantu Presentasi', 'Desain Slides', 'Content Creator'],
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
                'title' => 'Desain Poster Kegiatan Sekolah/OSIS',
                'subcategory' => 'Desain Poster',
                'price' => 50000,
                'description' => 'Poster acara sekolah/OSIS/UKM: ukuran A3/A4, desain menarik, revisi 2x, file PDF + JPG siap cetak.',
            ],
            [
                'title' => 'Buat Poster Acara Sekolah/OSIS',
                'subcategory' => 'Desain Poster',
                'price' => 30000,
                'description' => 'Poster acara sekolah/OSIS/UKM: ukuran A3/A4, desain menarik, revisi 2x, file PDF + JPG siap cetak.',
            ],
            [
                'title' => 'Desain Logo Sekolah/UKM',
                'subcategory' => 'Desain Logo',
                'price' => 100000,
                'description' => 'Desain logo minimalis modern untuk UKM/sekolah. 3 konsep, revisi unlimited, file AI/EPS/PNG.',
            ],
            [
                'title' => 'Edit Video Reels/TikTok',
                'subcategory' => 'Edit Reels/TikTok',
                'price' => 50000,
                'description' => 'Editing video pendek 30-60 detik, caption auto, transisi trend, musik bebas hak cipta. Joki 3 video/bulan.',
            ],
            [
                'title' => 'Video Promosi Sekolah/OSIS',
                'subcategory' => 'Videografi Acara',
                'price' => 150000,
                'description' => 'Video promosi acara sekolah/OSIS: dokumentasi + editing + musik. Durasi 2-3 menit, siap share ke sosmed.',
            ],
            [
                'title' => 'Jasa Desain Sosial Media',
                'subcategory' => 'Desain Sosial Media',
                'price' => 75000,
                'description' => 'Desain IG Story/Feed/TikTok template untuk OSIS/UKM. 5 template + 3 revisi. Estetik kece.',
            ],
            [
                'title' => 'Jasa Bantu Presentasi',
                'subcategory' => 'Bantu Presentasi',
                'price' => 40000,
                'description' => 'Bantu buat slides PowerPoint/Google Slides presentasi sekolah. Desain menarik, konten rapi, siap ngajar.',
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