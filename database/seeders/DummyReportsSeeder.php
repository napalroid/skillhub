<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class DummyReportsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::limit(10)->get();
        
        if ($users->count() < 2) {
            $this->command->warn('Minimal butuh 2 user terdaftar.');
            return;
        }

        $categories = Report::getCategories();
        
        $reasons = [
            'Seller tidak merespon chat selama 3 hari setelah pembayaran dikonfirmasi. Saya sudah mencoba menghubungi berkali-kali.',
            'Hasil pekerjaan tidak sesuai dengan deskripsi yang dijanjikan. Kualitas sangat jauh dari yang diharapkan.',
            'Saya sudah membayar tapi seller tidak mau mulai mengerjakan pesanan. Sudah 1 minggu tidak ada progress.',
            'Seller memberikan file hasil akhir yang copy-paste dari template, tidak original seperti yang dijanjikan.',
            'Komunikasi sangat buruk, seller sering membalas chat dengan kasar dan tidak profesional.',
            'File yang dikirimkan corrupt dan tidak bisa dibuka. Saya sudah minta revisi tapi tidak direspon.',
            'Pembayaran sudah saya lakukan via transfer tapi status masih pending, padahal uang sudah terpotong.',
            'Seller meminta tambahan biaya di luar harga yang disepakati di awal dengan alasan yang tidak jelas.',
            'Hasil revisi pertama masih sama saja dengan hasil awal, tidak ada perubahan yang berarti.',
            'Waktu pengerjaan molor jauh dari deadline yang dijanjikan, seller tidak memberikan update progress.',
        ];

        $statuses = ['open', 'open', 'open', 'reviewed', 'closed'];
        $roles = ['buyer', 'seller'];
        
        $count = 0;
        for ($i = 0; $i < 25; $i++) {
            $reporter = $users->random();
            $reported = $users->where('id', '!=', $reporter->id)->random();
            
            Report::create([
                'reporter_id' => $reporter->id,
                'reported_user_id' => $reported->id,
                'order_id' => null,
                'reporter_role' => $roles[array_rand($roles)],
                'category' => $categories[array_rand($categories)],
                'reason' => $reasons[array_rand($reasons)],
                'status' => $statuses[array_rand($statuses)],
                'admin_notes' => rand(0, 1) ? 'Sudah ditinjau, sedang dalam investigasi tim.' : null,
                'created_at' => Carbon::now()->subDays(rand(0, 180)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
            $count++;
        }

        $this->command->info("Berhasil membuat {$count} laporan dummy.");
    }
}
