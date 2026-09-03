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

        // 4. Dummy Users (7 users)
        $dummyUsers = [
            [
                'name' => 'Bani',
                'email' => 'bani@gmail.com',
                'password' => 'bani',
                'phone' => '081234567893',
            ],
            [
                'name' => 'Siti',
                'email' => 'siti@gmail.com',
                'password' => 'siti',
                'phone' => '081234567894',
            ],
            [
                'name' => 'Andi',
                'email' => 'andi@gmail.com',
                'password' => 'andi',
                'phone' => '081234567895',
            ],
            [
                'name' => 'Rina',
                'email' => 'rina@gmail.com',
                'password' => 'rina',
                'phone' => '081234567896',
            ],
            [
                'name' => 'Doni',
                'email' => 'doni@gmail.com',
                'password' => 'doni',
                'phone' => '081234567897',
            ],
            [
                'name' => 'Mira',
                'email' => 'mira@gmail.com',
                'password' => 'mira',
                'phone' => '081234567898',
            ],
            [
                'name' => 'Rudi',
                'email' => 'rudi@gmail.com',
                'password' => 'rudi',
                'phone' => '081234567899',
            ],
        ];

        $users = [];
        foreach ($dummyUsers as $userData) {
            $users[] = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'first_name' => $userData['name'],
                    'last_name' => '',
                    'password' => Hash::make($userData['password']),
                    'role' => 'user',
                    'phone' => $userData['phone'],
                    'email_verified_at' => now(),
                ]
            );
        }

        // 5. Categories & Subcategories
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

        // 6. Services (20 dummy services)
        $subcats = Subcategory::all();
        $servicesData = [
            [
                'title' => 'Desain Logo Profesional Modern',
                'subcategory' => 'Desain Logo',
                'price' => 150000,
                'description' => 'Desain logo minimalis, modern, cocok untuk brand UMKM/startup. Include 3 konsep, revisi unlimited, file source AI/EPS/PNG.',
                'user' => $users[0],
            ],
            [
                'title' => 'Desain Logo Sekolah/UKM',
                'subcategory' => 'Desain Logo',
                'price' => 100000,
                'description' => 'Desain logo minimalis modern untuk UKM/sekolah. 3 konsep, revisi unlimited, file AI/EPS/PNG.',
                'user' => $users[1],
            ],
            [
                'title' => 'Desain Logo Startup Tech',
                'subcategory' => 'Desain Logo',
                'price' => 200000,
                'description' => 'Logo untuk startup teknologi. Modern, futuristik, cocok untuk IT/software. Include mockup & brand guideline.',
                'user' => $users[2],
            ],
            [
                'title' => 'Desain Poster Kegiatan Sekolah/OSIS',
                'subcategory' => 'Desain Poster',
                'price' => 50000,
                'description' => 'Poster acara sekolah/OSIS/UKM: ukuran A3/A4, desain menarik, revisi 2x, file PDF + JPG siap cetak.',
                'user' => $users[3],
            ],
            [
                'title' => 'Buat Poster Acara Sekolah/OSIS',
                'subcategory' => 'Desain Poster',
                'price' => 30000,
                'description' => 'Poster acara sekolah/OSIS/UKM: ukuran A3/A4, desain menarik, revisi 2x, file PDF + JPG siap cetak.',
                'user' => $users[4],
            ],
            [
                'title' => 'Poster Event Kampus & Organisasi',
                'subcategory' => 'Desain Poster',
                'price' => 75000,
                'description' => 'Poster profesional untuk event kampus, seminar, webinar. Ukuran custom, revisi 3x, format digital & cetak.',
                'user' => $users[5],
            ],
            [
                'title' => 'Jasa Desain Sosial Media',
                'subcategory' => 'Desain Sosial Media',
                'price' => 75000,
                'description' => 'Desain IG Story/Feed/TikTok template untuk OSIS/UKM. 5 template + 3 revisi. Estetik kece.',
                'user' => $users[6],
            ],
            [
                'title' => 'Desain Feed Instagram Aesthetic',
                'subcategory' => 'Desain Sosial Media',
                'price' => 100000,
                'description' => 'Paket 9 feed IG kohesif & aesthetic. Cocok untuk personal branding, UMKM, bisnis online. Include template Canva.',
                'user' => $users[0],
            ],
            [
                'title' => 'Content Planning Social Media',
                'subcategory' => 'Desain Sosial Media',
                'price' => 120000,
                'description' => 'Perencanaan konten sosmed 1 bulan: 30 desain post, caption, hashtag. Siap auto-pilot!',
                'user' => $users[1],
            ],
            [
                'title' => 'Ilustrasi Digital Character Design',
                'subcategory' => 'Ilustrasi',
                'price' => 200000,
                'description' => 'Ilustrasi karakter digital custom untuk mascot, avatar, NFT. Full color, revisi 2x, file PNG HD.',
                'user' => $users[2],
            ],
            [
                'title' => 'Ilustrasi Cover Buku/Novel',
                'subcategory' => 'Ilustrasi',
                'price' => 250000,
                'description' => 'Cover buku profesional dengan ilustrasi custom. Cocok untuk self-publishing, Wattpad, novel indie.',
                'user' => $users[3],
            ],
            [
                'title' => 'Edit Video Reels/TikTok',
                'subcategory' => 'Edit Reels/TikTok',
                'price' => 50000,
                'description' => 'Editing video pendek 30-60 detik, caption auto, transisi trend, musik bebas hak cipta. Joki 3 video/bulan.',
                'user' => $users[4],
            ],
            [
                'title' => 'Edit Reels Viral dengan Transisi Smooth',
                'subcategory' => 'Edit Reels/TikTok',
                'price' => 75000,
                'description' => 'Editing reels/TikTok pro: transisi keren, color grading, sound design. Durasi 15-60 detik, dijamin FYP!',
                'user' => $users[5],
            ],
            [
                'title' => 'Video Promosi Sekolah/OSIS',
                'subcategory' => 'Videografi Acara',
                'price' => 150000,
                'description' => 'Video promosi acara sekolah/OSIS: dokumentasi + editing + musik. Durasi 2-3 menit, siap share ke sosmed.',
                'user' => $users[6],
            ],
            [
                'title' => 'Videografi & Editing Event Kampus',
                'subcategory' => 'Videografi Acara',
                'price' => 300000,
                'description' => 'Dokumentasi video event kampus, seminar, wisuda. Cinematic, color grading pro, durasi 5-10 menit.',
                'user' => $users[0],
            ],
            [
                'title' => 'Dokumentasi Acara Pernikahan',
                'subcategory' => 'Dokumentasi Event',
                'price' => 500000,
                'description' => 'Dokumentasi foto & video pernikahan. Full day coverage, editing profesional, album digital + cetak.',
                'user' => $users[1],
            ],
            [
                'title' => 'Foto Produk UMKM/Online Shop',
                'subcategory' => 'Foto Acara Sekolah',
                'price' => 100000,
                'description' => 'Fotografi produk untuk UMKM/online shop. 20 foto edited, background putih/custom, siap upload marketplace.',
                'user' => $users[2],
            ],
            [
                'title' => 'Jasa Bantu Presentasi',
                'subcategory' => 'Bantu Presentasi',
                'price' => 40000,
                'description' => 'Bantu buat slides PowerPoint/Google Slides presentasi sekolah. Desain menarik, konten rapi, siap ngajar.',
                'user' => $users[3],
            ],
            [
                'title' => 'Desain Presentasi PPT Profesional',
                'subcategory' => 'Desain Slides',
                'price' => 80000,
                'description' => 'Desain slide PPT/Google Slides profesional untuk pitch deck, proposal, laporan. Modern & clean design.',
                'user' => $users[4],
            ],
            [
                'title' => 'Jasa Content Creator TikTok/IG',
                'subcategory' => 'Content Creator',
                'price' => 150000,
                'description' => 'Jasa buat konten TikTok/IG Reels. Konsep ide, shooting, editing. Paket 5 video/minggu, auto viral!',
                'user' => $users[5],
            ],
        ];

        foreach ($servicesData as $data) {
            $subcat = $subcats->where('name', $data['subcategory'])->first();
            if (! $subcat) continue;

            Service::firstOrCreate(
                ['title' => $data['title'], 'user_id' => $data['user']->id],
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
        $this->command->info('');
        $this->command->info('Dummy Users (password = email username):');
        $this->command->info('- bani@gmail.com / bani');
        $this->command->info('- siti@gmail.com / siti');
        $this->command->info('- andi@gmail.com / andi');
        $this->command->info('- rina@gmail.com / rina');
        $this->command->info('- doni@gmail.com / doni');
        $this->command->info('- mira@gmail.com / mira');
        $this->command->info('- rudi@gmail.com / rudi');
        
        // Run DummyReportsSeeder
        $this->call(DummyReportsSeeder::class);
    }
}