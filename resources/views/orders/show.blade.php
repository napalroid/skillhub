@extends('layouts.app')

@section('title', 'Pesanan #{{ $order->id }}')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&display=swap');
    
    :root {
        --adidas-black: #080808;
        --adidas-white: #ffffff;
        --adidas-line: #e5e5e5;
        --adidas-cream: #f7f7f7;
    }
    
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background-color: var(--adidas-cream);
        color: var(--adidas-black);
        -webkit-font-smoothing: antialiased;
    }
    
    .font-display {
        font-family: 'Archivo', sans-serif;
    }
    
    .card {
        background: var(--adidas-white);
        border: 1px solid var(--adidas-line);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    
    @php
        $statusStyles = [
            'menunggu' => 'bg-gray-100 text-gray-900',
            'diproses' => 'bg-gray-100 text-gray-900',
            'berlangsung' => 'bg-black text-white',
            'selesai' => 'bg-emerald-100 text-emerald-900',
            'dibatalkan' => 'bg-red-100 text-red-900',
        ];
        
        $statusClass = $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-900';
    @endphp
</style>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            {{-- ORDER HEADER --}} 
            <div class="card">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="flex-1 min-w-0">
                        <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight text-black mb-2">
                            {{ $order->service->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <span class="text-gray-600">
                                Penjual: <strong class="text-black">{{ $order->service->seller->name }}</strong>
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="text-gray-600">
                                Pembeli: <strong class="text-black">{{ $order->buyer->name }}</strong>
                            </span>
                        </div>
                        
                        <span class="status-pill mt-4 {{ $statusClass }}">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>
                    
                    <div class="flex flex-col items-end min-w-[180px]">
                        <p class="font-display text-4xl sm:text-5xl font-black tracking-tight text-black mb-4">
                            Rp{{ number_format($order->final_price, 0, ',', '.') }}
                        </p>
                        
                        <a href="{{ route('conversations.show', $order->service->conversations()->where('buyer_id', $order->buyer_id)->where('seller_id', $order->service->user_id)->first()?->id ?? '#') }}"
                           class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            Hubungi {{ $isBuyer ? 'Penjual' : 'Pembeli' }}
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- PAYMENT STATUS CARD (Buyer) --}}
            @if($isBuyer)
                <div class="card">
                    <h2 class="font-display text-xl font-black tracking-tight mb-4">Status Pembayaran</h2>
                    
                    @php
                        $paymentStatusLabel = match ($order->payment_status) {
                            'paid' => $order->payment?->isAdminConfirmed() ? 'Saldo dikonfirmasi — seller sedang mengerjakan' : 'QRIS lunas — menunggu konfirmasi saldo admin (escrow)',
                            'pending' => 'Menunggu pembayaran',
                            'expired' => 'Kedaluwarsa',
                            'failed' => 'Gagal',
                            default => ucfirst((string) $order->payment_status),
                        };
                    @endphp
                    
                    @if ($order->payment_status === 'paid' && $order->payment?->isAdminConfirmed())
                        <div class="bg-black text-white px-4 py-3 rounded-lg font-bold">
                            Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.
                        </div>
                    @elseif ($order->payment_status === 'paid')
                        <div class="bg-[#E4002B] text-white px-4 py-3 rounded-lg font-bold mb-3">
                            Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin sebelum seller mulai mengerjakan.
                        </div>
                        <p class="text-xs text-gray-500 mb-4">
                            Admin akan mencocokkan dana masuk di rekening, lalu menekan <strong>Konfirmasi Saldo Masuk</strong> di Transaksi.
                        </p>
                    @elseif ($order->status === 'menunggu_pembayaran')
                        <p class="text-sm text-gray-600 mb-4">Bayar aman melalui QRIS Midtrans Sandbox. Setelah sukses, status menjadi Jasa Terbayarkan di admin.</p>
                        <a href="{{ route('orders.payment.show', $order) }}" class="inline-flex items-center justify-center bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition w-full md:w-auto">
                            Bayar dengan QRIS
                        </a>
                    @elseif ($order->payment)
                        <p class="text-sm text-gray-600">
                            Status pembayaran: <strong>{{ $paymentStatusLabel }}</strong>
                        </p>
                    @else
                        <p class="text-sm text-gray-500">Menunggu buyer melakukan pembayaran.</p>
                    @endif
                </div>
            @endif
            
            {{-- CHAT MESSAGES CARD --}} 
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-xl font-black tracking-tight">Diskusi Pesanan</h2>
                    <a href="{{ route('conversations.show', $order->service->conversations()->where('buyer_id', $order->buyer_id)->where('seller_id', $order->service->user_id)->first()?->id ?? '#') }}" class="text-sm font-bold uppercase tracking-wider text-black hover:opacity-70">
                        Lihat riwayat diskusi harga &rarr;
                    </a>
                </div>
                
                <div class="space-y-3 max-h-80 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300">
                    @forelse ($order->messages as $msg)
                        <div class="flex gap-3 {{ $msg->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="font-bold text-sm">{{ $msg->sender->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-800 bg-gray-50 px-3 py-2 rounded-lg">
                                    {{ $msg->message }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-sm text-gray-400 py-8">
                            Belum ada pesan dalam percakapan pesanan ini.
                        </p>
                    @endforelse
                </div>
                
                <form method="POST" action="{{ route('order-messages.store', $order) }}" class="mt-4">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="message" required placeholder="Tulis pesan..." class="flex-1 rounded-lg border-gray-300 text-sm px-4 py-3 focus:border-black focus:ring-black">
                        <button type="submit" class="bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- FILES SECTION --}} 
            <div class="card">
                <h2 class="font-display text-xl font-black tracking-tight mb-4">File Pesanan</h2>
                
                @if ($order->files->count() > 0)
                    <div class="space-y-2 mb-6">
                        @foreach ($order->files as $file)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded bg-black text-white flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm truncate">{{ $file->file_path }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ ucfirst($file->file_type) }} oleh {{ $file->uploader->name }} &bull; {{ $file->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="inline-flex items-center justify-center bg-black text-white px-4 py-2 rounded font-bold uppercase tracking-wider text-xs hover:bg-gray-900 transition flex-shrink-0 ml-4">
                                    Unduh
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                {{-- SELLER ACTIONS --}} 
                @if ($isSeller)
                    <div class="space-y-4">
                        @if ($order->status === 'menunggu_persetujuan')
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                                <p class="text-sm text-emerald-900 font-bold">
                                    ✅ Hasil sudah dikirim. Menunggu buyer menyetujui — setelah disetujui, dana cair otomatis ke dompet <strong>1 jam</strong> kemudian.
                                </p>
                            </div>
                        @elseif ($order->status === 'dikerjakan')
                            <div class="bg-black text-white p-4 rounded-lg">
                                <p class="text-sm font-bold">
                                    &rarr; Sedang dikerjakan. Upload hasil di bawah untuk menyerahkan pesanan ke buyer.
                                </p>
                            </div>
                        @endif
                        
                        @if ($order->status === 'dibayar')
                            <form method="POST" action="{{ route('orders.start-work', $order) }}" class="flex gap-3">
                                @csrf
                                <button type="submit" class="flex-1 bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition">
                                    ▶ Mulai Kerjakan
                                </button>
                            </form>
                        @endif
                        
                        @if (in_array($order->status, ['dibayar', 'dikerjakan']))
                            <form method="POST" action="{{ route('order-files.store', $order) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="file_type" value="hasil">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <input type="file" name="file" accept=".pdf,.zip,.png,.jpg,.jpeg,.doc,.docx,.ppt,.pptx" required
                                           class="flex-1 file:mr-3 file:py-3 file:px-4 file:rounded-lg file:border-0 file:bg-black file:text-white file:font-bold file:text-sm file:uppercase file:tracking-wider file:cursor-pointer hover:file:bg-gray-900">
                                    <button type="submit" class="bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition flex-shrink-0">
                                        Upload Hasil
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Format: PDF / ZIP / JPG / PNG / DOC (maks 5MB).</p>
                            </form>
                        @endif
                    </div>
                @endif
                
                {{-- BUYER ACTIONS --}} 
                @if ($isBuyer && $order->status === 'menunggu_persetujuan')
                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                            <p class="text-sm text-emerald-900">
                                Hasil sudah dikirim seller. Jika sudah sesuai, tekan <strong>Selesaikan Pesanan</strong>. Dana akan cair otomatis ke seller <strong>1 jam</strong> setelah itu (jeda anti-salah-klik).
                            </p>
                        </div>
                        
                        <form method="POST" action="{{ route('order-files.approve', $order) }}" class="flex gap-3">
                            @csrf
                            <button type="submit" class="flex-1 bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition">
                                ✓ Selesaikan Pesanan
                            </button>
                        </form>
                        
                        <button onclick="document.getElementById('revisiForm').classList.toggle('hidden')"
                                class="w-full bg-white border border-gray-300 text-black px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-50 transition">
                            ↺ Minta Revisi
                        </button>
                        
                        <form id="revisiForm" method="POST" action="{{ route('order-files.revise', $order) }}" class="hidden mt-4 space-y-3">
                            @csrf
                            <input type="text" name="revision_note" placeholder="Jelaskan revisi yang diinginkan..." class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm">
                            <button type="submit" class="w-full bg-amber-100 text-amber-900 px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-amber-200 transition">
                                Kirim
                            </button>
                        </form>
                    </div>
                @endif
                
                @if ($order->status === 'selesai')
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        @if ($order->payment && $order->payment->status === 'released')
                            <p class="text-sm text-gray-700 font-bold">
                                &check; Pesanan selesai & dana sudah cair ke saldo dompet seller.
                            </p>
                        @else
                            <p class="text-sm text-gray-700 font-bold">
                                &rarr; Pesanan selesai. Dana akan cair otomatis ke seller dalam 1 jam sejak penyelesaian.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
            
            {{-- REVIEW SECTION --}} 
            @if ($isBuyer && $order->status === 'selesai')
                <div class="card">
                    <h2 class="font-display text-xl font-black tracking-tight mb-4">Beri Review</h2>
                    
                    @if ($order->review)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-display text-2xl font-black">{{ $order->review->rating }}/5</span>
                                <span class="text-gray-600">•</span>
                                <span class="text-sm text-gray-500">{{ $order->review->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-800 font-medium">{{ $order->review->comment }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('reviews.store', $order) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2">Rating</label>
                                <select name="rating" class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm font-medium">
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                    <option value="4">⭐⭐⭐⭐ Puas</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2">Komentar (opsional)</label>
                                <textarea name="comment" rows="3" placeholder="Tulis komentar tentang pengalaman bertransaksi..." class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-900 transition">
                                Kirim Review
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            
            {{-- REPORT SECTION --}} 
            <div class="text-center">
                <button onclick="document.getElementById('reportForm').classList.toggle('hidden')" class="text-sm font-bold uppercase tracking-wider text-red-600 hover:text-red-800 transition">
                    ⚠ Laporkan masalah pada pesanan ini
                </button>
                <form id="reportForm" method="POST" action="{{ route('reports.store') }}" class="hidden mt-4 card" x-data="{ selectedRole: '', selectedCategory: '' }">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="reported_user_id" value="{{ $isBuyer ? $order->service->user_id : $order->buyer_id }}">
                    
                    <div class="space-y-4">
                        {{-- Role Selection --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Anda melaporkan sebagai <span class="text-red-600">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition"
                                       :class="selectedRole === 'buyer' ? 'border-black bg-black text-white' : 'border-gray-300 hover:border-gray-400'">
                                    <input type="radio" name="reporter_role" value="buyer" required class="sr-only" x-model="selectedRole">
                                    <span class="font-bold text-sm">BUYER</span>
                                </label>
                                <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition"
                                       :class="selectedRole === 'seller' ? 'border-black bg-black text-white' : 'border-gray-300 hover:border-gray-400'">
                                    <input type="radio" name="reporter_role" value="seller" required class="sr-only" x-model="selectedRole">
                                    <span class="font-bold text-sm">SELLER</span>
                                </label>
                            </div>
                        </div>

                        {{-- Category Selection --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Kategori Masalah <span class="text-red-600">*</span></label>
                            <select name="category" required class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm" x-model="selectedCategory">
                                <option value="">Pilih kategori masalah</option>
                                @foreach(\App\Models\Report::getCategories() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Reason --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Jelaskan Detail Masalah <span class="text-red-600">*</span></label>
                            <textarea name="reason" rows="4" required minlength="10" maxlength="500" placeholder="Jelaskan masalah yang Anda alami secara detail (minimal 10 karakter)..." class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 10 karakter, maksimum 500 karakter</p>
                        </div>

                        <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-red-700 transition rounded-lg">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
        
        {{-- SIDEBAR --}} 
        <div class="space-y-6">
            @if($isSeller)
                <div class="card">
                    <h2 class="font-display text-lg font-black tracking-tight mb-4">Pembayaran & Status</h2>
                    
                    @php
                        $paymentStatusLabel = match ($order->payment_status) {
                            'paid' => $order->payment?->isAdminConfirmed() ? 'Saldo dikonfirmasi' : 'Menunggu konfirmasi admin',
                            'pending' => 'Menunggu pembayaran',
                            'expired' => 'Kedaluwarsa',
                            'failed' => 'Gagal',
                            default => ucfirst((string) $order->payment_status),
                        };
                    @endphp
                    
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Status Pembayaran</span>
                            <span class="font-bold text-black">{{ $paymentStatusLabel }}</span>
                        </div>
                        
                        @if ($order->payment_status === 'paid' && $order->payment?->isAdminConfirmed())
                            <div class="bg-black text-white px-4 py-3 rounded-lg text-sm font-bold">
                                Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.
                            </div>
                        @elseif ($order->payment_status === 'paid')
                            <div class="bg-[#E4002B] text-white px-4 py-3 rounded-lg text-sm font-bold">
                                Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin.
                            </div>
                        @endif
                        
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <a href="{{ route('wallet.index') }}" class="flex items-center gap-2 text-sm font-bold text-black hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Lihat Dompet
                            </a>
                            
                            @if ($order->status === 'menunggu_persetujuan')
                                <p class="text-xs text-gray-500">Hasil sudah dikirim. Menunggu buyer menyetujui.</p>
                            @elseif ($order->status === 'dikerjakan')
                                <p class="text-xs text-gray-500">Sedang dikerjakan. Upload hasil untuk menyerahkan ke buyer.</p>
                            @elseif ($order->status === 'dibayar')
                                <p class="text-xs text-gray-500">Dana sudah di-escrow. Mulai kerjakan lalu upload hasil.</p>
                            @elseif ($order->status === 'selesai')
                                <p class="text-xs text-gray-500">Pesanan selesai. Dana cair otomatis ke dompet 1 jam setelah penyelesaian.</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card bg-black text-white">
                    <h3 class="font-display text-lg font-black tracking-tight mb-2">Seller Dashboard</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Total Pendapatan</span>
                            <span class="font-bold">Rp{{ number_format($order->final_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Status</span>
                            <span class="font-bold uppercase">{{ $order->status }}</span>
                        </div>
                    </div>
                    <a href="{{ route('orders.index', ['role' => 'seller']) }}" class="mt-4 block text-center bg-white text-black px-4 py-2 rounded font-bold uppercase tracking-wider text-xs hover:bg-gray-200 transition">
                        Kembali ke Daftar Pesanan
                    </a>
                </div>
            @endif
            
            <div class="card bg-amber-50 border-amber-100">
                <h3 class="font-display text-lg font-black tracking-tight mb-2">Butuh Bantuan?</h3>
                <p class="text-sm text-gray-700 mb-3">
                    Jika ada masalah dengan pesanan ini, jangan ragu untuk menghubungi counterparty atau mengajukan laporan.
                </p>
                <a href="{{ route('home') }}" class="inline-block w-full text-center bg-amber-600 text-white px-4 py-2 rounded font-bold uppercase tracking-wider text-xs hover:bg-amber-700 transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection
