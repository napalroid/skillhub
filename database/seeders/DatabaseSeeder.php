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
            [
                'name' => 'Raffie',
                'email' => 'raffie@gmail.com',
                'password' => 'raffie',
                'phone' => '081234567900',
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
            'Lifestyle' => ['Barber'],
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

        // 6. Services (1 service: Barber Potong Raffie)
        $subcats = Subcategory::all();
        $servicesData = [
            [
                'title' => 'Barber Potong Raffie',
                'subcategory' => 'Barber',
                'price' => 20000,
                'description' => 'Potong rambut model Raffie Ahmad style yang keren dan rapi. Cocok untuk anak muda, pelajar, dan mahasiswa. Includes: cuci rambut, potong profesional, styling modern. Durasi 30-45 menit. Barber berpengalaman dengan alat modern. Harga terjangkau, hasil maksimal!',
                'user' => $users[7],
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
        $this->command->info('- raffie@gmail.com / raffie');
        $this->command->info('');
        $this->command->info('Service: Barber Potong Raffie (Rp 20.000) - Owner: Raffie');
        
        // Run DummyReportsSeeder
        $this->call(DummyReportsSeeder::class);
        
        // Run BarberTimeSlotSeeder
        $this->call(BarberTimeSlotSeeder::class);
    }
}