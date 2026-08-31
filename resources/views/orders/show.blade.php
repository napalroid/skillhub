@extends('layouts.app')

@section('title', 'Pesanan #{{ $order->id }}')

@section('content')
<style>
    .order-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 2px;
        padding: 2rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 0.625rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        border-radius: 1px;
    }
    
    @php
        $statusMap = [
            'menunggu_pembayaran' => ['bg' => '#f5f5f5', 'text' => '#555555'],
            'menunggu_verifikasi' => ['bg' => '#f5f5f5', 'text' => '#555555'],
            'dibayar' => ['bg' => '#EDE734', 'text' => '#555555'],
            'dikerjakan' => ['bg' => '#000', 'text' => '#fff'],
            'menunggu_persetujuan' => ['bg' => '#2C9F45', 'text' => '#fff'],
            'selesai' => ['bg' => '#2C9F45', 'text' => '#fff'],
            'dibatalkan' => ['bg' => '#E4002B', 'text' => '#fff'],
        ];
        $statusStyle = $statusMap[$order->status] ?? ['bg' => '#f5f5f5', 'text' => '#555555'];
    @endphp
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-[1fr_320px] gap-6">
        
        <div class="space-y-6">
            
            {{-- ORDER HEADER --}} 
            <div class="order-card">
                <div class="flex items-start justify-between gap-6 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex-1 min-w-0">
                        <h1 class="font-heading text-2xl font-black tracking-tight mb-3">
                            {{ $order->service->title }}
                        </h1>
                        
                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs">
                            <span class="text-gray-500">
                                Penjual: <strong class="text-black font-bold">{{ $order->service->seller->name }}</strong>
                            </span>
                            <span class="text-gray-500">
                                Pembeli: <strong class="text-black font-bold">{{ $order->buyer->name }}</strong>
                            </span>
                        </div>
                    </div>
                    
                    <span class="status-badge" style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
                
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Total</p>
                        <p class="font-heading text-3xl font-black tracking-tight">
                            Rp{{ number_format($order->final_price, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    <a href="{{ route('orders.conversation', $order) }}"
                       class="btn-primary">
                        Hubungi {{ $isBuyer ? 'Penjual' : 'Pembeli' }}
                    </a>
                </div>
            </div>
            
            {{-- PAYMENT STATUS CARD (Buyer) --}}
            @if($isBuyer)
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Status Pembayaran</h2>
                    
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
                        <div class="bg-black text-white px-4 py-3 text-sm font-bold">
                            Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.
                        </div>
                    @elseif ($order->payment_status === 'paid')
                        <div class="bg-[#E4002B] text-white px-4 py-3 text-sm font-bold mb-3">
                            Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin sebelum seller mulai mengerjakan.
                        </div>
                        <p class="text-xs text-gray-500">
                            Admin akan mencocokkan dana masuk di rekening, lalu menekan <strong>Konfirmasi Saldo Masuk</strong> di Transaksi.
                        </p>
                    @elseif ($order->status === 'menunggu_pembayaran')
                        <p class="text-sm text-gray-600 mb-4">Bayar aman melalui QRIS Midtrans Sandbox. Setelah sukses, status menjadi Jasa Terbayarkan di admin.</p>
                        <a href="{{ route('orders.payment.show', $order) }}" class="btn-primary">
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
            
            {{-- FILES SECTION --}} 
            <div class="order-card">
                <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">File Pesanan</h2>
                
                @if ($order->files->count() > 0)
                    <div class="space-y-2 mb-6">
                        @foreach ($order->files as $file)
                            <div class="flex items-center justify-between bg-gray-50 p-3 border border-gray-200">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="w-8 h-8 bg-black text-white flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-xs truncate">{{ $file->file_path }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ ucfirst($file->file_type) }} oleh {{ $file->uploader->name }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn-outline ml-4 flex-shrink-0" style="padding: 0.5rem 1rem; font-size: 0.625rem;">
                                    Unduh
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                {{-- BUYER MESSAGE --}} 
                @if ($order->messages->count() > 0 && $isSeller)
                    <div class="order-card bg-[#EDE734] border border-[#d4ce2a]">
                        <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-3">Pesan dari Buyer</h3>
                        @foreach ($order->messages as $msg)
                            <div class="mb-3 pb-3 border-b border-black/20 last:border-0 last:mb-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-bold text-xs">Buyer:</span>
                                    <span class="text-xs text-gray-700">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-xs text-gray-900">{{ $msg->message }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                {{-- SELLER ACTIONS --}} 
                @if ($isSeller)
                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        @if ($order->status === 'menunggu_persetujuan')
                            <div class="bg-[#EDE734] border border-[#d4ce2a] px-4 py-3">
                                <p class="text-xs font-bold text-black">
                                    Hasil sudah dikirim. Menunggu buyer menyetujui — setelah disetujui, dana cair otomatis ke dompet 1 jam kemudian.
                                </p>
                            </div>
                        @elseif ($order->status === 'dikerjakan')
                            <div class="bg-black text-white p-3">
                                <p class="text-xs font-bold">
                                    Sedang dikerjakan. Upload hasil di bawah untuk menyerahkan pesanan ke buyer.
                                </p>
                            </div>
                        @endif
                        
                        @if ($order->status === 'dibayar')
                            <form method="POST" action="{{ route('orders.start-work', $order) }}">
                                @csrf
                                <button type="submit" class="btn-primary w-full">
                                    Mulai Kerjakan
                                </button>
                            </form>
                        @endif
                        
                        @if (in_array($order->status, ['dibayar', 'dikerjakan']))
                            <form method="POST" action="{{ route('order-files.store', $order) }}" enctype="multipart/form-data" class="space-y-2">
                                @csrf
                                <input type="hidden" name="file_type" value="hasil">
                                <input type="file" name="file" accept=".pdf,.zip,.png,.jpg,.jpeg,.doc,.docx,.ppt,.pptx" required
                                       class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-black file:text-white file:font-bold file:text-xs file:uppercase file:tracking-wider file:cursor-pointer hover:file:bg-gray-800">
                                <button type="submit" class="btn-primary w-full">
                                    Upload Hasil
                                </button>
                                <p class="text-xs text-gray-500">Format: PDF / ZIP / JPG / PNG / DOC (maks 5MB).</p>
                            </form>
                        @endif
                    </div>
                @endif
                
                {{-- BUYER ACTIONS --}} 
                @if ($isBuyer && $order->status === 'menunggu_persetujuan')
                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        <div class="bg-[#2C9F45] text-white px-4 py-3">
                            <p class="text-xs font-bold">
                                Hasil sudah dikirim seller. Jika sudah sesuai, tekan Selesaikan Pesanan. Dana akan cair otomatis ke seller 1 jam setelah itu.
                            </p>
                        </div>
                        
                        <form method="POST" action="{{ route('order-files.approve', $order) }}">
                            @csrf
                            <button type="submit" class="btn-success w-full">
                                Selesaikan Pesanan
                            </button>
                        </form>
                        
                        <button onclick="document.getElementById('revisiForm').classList.toggle('hidden')"
                                class="btn-outline w-full">
                            Minta Revisi
                        </button>
                        
                        <form id="revisiForm" method="POST" action="{{ route('order-files.revise', $order) }}" class="hidden space-y-2">
                            @csrf
                            <input type="text" name="revision_note" placeholder="Jelaskan revisi yang diinginkan..." class="input-field text-xs">
                            <button type="submit" class="w-full bg-[#EDE734] text-black px-4 py-3 font-bold uppercase tracking-wider text-xs border-2 border-[#d4ce2a] hover:bg-[#d4ce2a]">
                                Kirim
                            </button>
                        </form>
                    </div>
                @endif
                
                @if ($order->status === 'selesai')
                    <div class="pt-4 border-t border-gray-200">
                        @if ($order->payment && $order->payment->status === 'released')
                            <p class="text-xs text-gray-700 font-bold">
                                Pesanan selesai & dana sudah cair ke saldo dompet seller.
                            </p>
                        @else
                            <p class="text-xs text-gray-700 font-bold">
                                Pesanan selesai. Dana akan cair otomatis ke seller dalam 1 jam sejak penyelesaian.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
            
            {{-- REVIEW SECTION --}} 
            @if ($isBuyer && $order->status === 'selesai')
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Beri Review</h2>
                    
                    @php
                        $existingReview = $order->service->reviews()->where('user_id', auth()->id())->first();
                    @endphp
                    
                    @if ($existingReview)
                        <div class="bg-gray-50 p-4 border border-gray-200">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-heading text-xl font-black">{{ $existingReview->rating }}/5</span>
                                <span class="text-xs text-gray-500">{{ $existingReview->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs text-gray-800">{{ $existingReview->comment }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('reviews.store', $order->service) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="label-field">Rating</label>
                                <select name="rating" class="input-field text-xs">
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                    <option value="4">⭐⭐⭐⭐ Puas</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="label-field">Komentar (opsional)</label>
                                <textarea name="comment" rows="3" placeholder="Tulis komentar tentang pengalaman bertransaksi..." class="input-field text-xs"></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full">
                                Kirim Review
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            
        </div>
        
        {{-- SIDEBAR --}} 
        <div class="space-y-6">
            @if($isSeller)
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Pembayaran & Status</h2>
                    
                    @php
                        $paymentStatusLabel = match ($order->payment_status) {
                            'paid' => $order->payment?->isAdminConfirmed() ? 'Saldo dikonfirmasi' : 'Menunggu konfirmasi admin',
                            'pending' => 'Menunggu pembayaran',
                            'expired' => 'Kedaluwarsa',
                            'failed' => 'Gagal',
                            default => ucfirst((string) $order->payment_status),
                        };
                    @endphp
                    
                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Status Pembayaran</span>
                            <span class="text-xs font-black">{{ $paymentStatusLabel }}</span>
                        </div>
                        
                        @if ($order->payment_status === 'paid' && $order->payment?->isAdminConfirmed())
                            <div class="bg-black text-white px-3 py-2 text-xs font-bold">
                                Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.
                            </div>
                        @elseif ($order->payment_status === 'paid')
                            <div class="bg-[#E4002B] text-white px-3 py-2 text-xs font-bold">
                                Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin.
                            </div>
                        @endif
                        
                        <div class="pt-3 border-t border-gray-200 space-y-2">
                            <a href="{{ route('wallet.index') }}" class="flex items-center gap-2 text-xs font-bold hover:underline">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
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
                
                <div class="order-card border-2 border-black">
                    <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-2">Butuh Bantuan?</h3>
                    <p class="text-xs text-gray-700 mb-3">
                        Ada masalah dengan pesanan? Hubungi counterparty atau ajukan laporan.
                    </p>
                    <button onclick="document.getElementById('reportForm').classList.toggle('hidden')" class="btn-danger w-full">
                        Laporkan Masalah
                    </button>
                </div>
            @endif
            
            <div class="order-card border-2 border-black">
                <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-2">Butuh Bantuan?</h3>
                <p class="text-xs text-gray-700 mb-3">
                    Ada masalah dengan pesanan? Hubungi counterparty atau ajukan laporan.
                </p>
                <button onclick="document.getElementById('reportForm').classList.toggle('hidden')" class="btn-danger w-full">
                    Laporkan Masalah
                </button>
            </div>
        </div>
        
    </div>
    
    {{-- REPORT FORM --}} 
    <form id="reportForm" method="POST" action="{{ route('reports.store') }}" class="hidden mt-6 order-card" x-data="{ selectedRole: '', selectedCategory: '' }">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="reported_user_id" value="{{ $isBuyer ? $order->service->user_id : $order->buyer_id }}">
        
        <h3 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Laporan Masalah</h3>
        
        <div class="space-y-4">
            <div>
                <label class="label-field">Anda melaporkan sebagai <span class="text-red-600">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center px-4 py-3 border-2 cursor-pointer transition"
                           :class="selectedRole === 'buyer' ? 'border-black bg-black text-white' : 'border-gray-300 hover:border-gray-400'">
                        <input type="radio" name="reporter_role" value="buyer" required class="sr-only" x-model="selectedRole">
                        <span class="font-bold text-xs uppercase tracking-wider">BUYER</span>
                    </label>
                    <label class="relative flex items-center justify-center px-4 py-3 border-2 cursor-pointer transition"
                           :class="selectedRole === 'seller' ? 'border-black bg-black text-white' : 'border-gray-300 hover:border-gray-400'">
                        <input type="radio" name="reporter_role" value="seller" required class="sr-only" x-model="selectedRole">
                        <span class="font-bold text-xs uppercase tracking-wider">SELLER</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="label-field">Kategori Masalah <span class="text-red-600">*</span></label>
                <select name="category" required class="input-field text-xs" x-model="selectedCategory">
                    <option value="">Pilih kategori masalah</option>
                    @foreach(\App\Models\Report::getCategories() as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label-field">Jelaskan Detail Masalah <span class="text-red-600">*</span></label>
                <textarea name="reason" rows="4" required minlength="10" maxlength="500" placeholder="Jelaskan masalah yang Anda alami secara detail (minimal 10 karakter)..." class="input-field text-xs"></textarea>
                <p class="text-xs text-gray-500 mt-1">Minimum 10 karakter, maksimum 500 karakter</p>
            </div>

            <button type="submit" class="w-full bg-[#E4002B] text-white px-4 py-3 font-bold uppercase tracking-wider text-xs border-2 border-[#E4002B] hover:bg-white hover:text-[#E4002B] transition">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>
@endsection
