@php
    $user = auth()->user();
    $initial = mb_strtoupper(mb_substr($user->name, 0, 1));
@endphp

<x-layouts.app title="Edit Profil — SkillHub">

    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:py-14">

        <div class="mb-8">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-600">Akun</p>
            <h1 class="mt-1.5 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Edit Profil</h1>
            <p class="mt-2 max-w-xl text-sm leading-6 text-gray-500">Perbarui informasi pribadi dan preferensi pencairan dana.</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">

            {{-- Profile Header — Double-Bezel outer shell --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-[1.75rem] border border-gray-200/80 bg-gray-50 p-2">
                    <div class="overflow-hidden rounded-[1.4rem] bg-white">
                        <div class="relative aspect-[16/8] bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600">
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-30"></div>
                        </div>
                        <div class="relative px-6 pb-6">
                            <div class="-mt-12 mb-4 flex items-end gap-4">
                                <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gray-900 text-2xl font-extrabold text-white shadow-xl shadow-black/10">
                                    {{ $initial }}
                                </div>
                                <div class="mb-1">
                                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-gray-900">{{ $user->services?->where('status', 'approved')->count() ?? 0 }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-gray-500">Jasa</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-gray-900">{{ $user->orders?->where('buyer_id', $user->id)->count() ?? 0 }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-gray-500">Pesanan</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-blue-600">Rp{{ number_format($user->balance, 0, ',', '.') }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-gray-500">Saldo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Form Profil — Double-Bezel outer shell --}}
            <section>
                <div class="rounded-[1.75rem] border border-gray-200/80 bg-gray-50 p-2">
                    <div class="rounded-[1.4rem] bg-white p-6 sm:p-8">
                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="name" class="mb-2 block text-sm font-bold text-gray-700">Nama lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                                       class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                @error('name')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                @error('email')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                                @if ($user->email_verified_at)
                                    <p class="mt-2 text-xs text-emerald-600">✓ Email sudah diverifikasi</p>
                                @else
                                    <p class="mt-2 text-xs text-amber-600">⚠ Belum diverifikasi — <a href="#" class="font-semibold hover:underline">Kirim ulang</a></p>
                                @endif
                            </div>

                            <div>
                                <label for="phone" class="mb-2 block text-sm font-bold text-gray-700">Nomor telepon</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" required maxlength="20" placeholder="0812..."
                                       class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                @error('phone')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="border-t border-gray-100 pt-6">
                                <p class="text-sm font-bold text-gray-900">Pencairan dana (e-wallet)</p>
                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="payout_type" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-500">Metode</label>
                                        <select name="payout_type" id="payout_type"
                                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                            @foreach(['dana' => 'DANA', 'gopay' => 'GoPay', 'ovo' => 'OVO', 'shopeepay' => 'ShopeePay', 'bank' => 'Bank'] as $val => $label)
                                                <option value="{{ $val }}" {{ old('payout_type', $user->payout_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('payout_type')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="payout_account" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-500">Nomor akun</label>
                                        <input type="text" name="payout_account" id="payout_account" value="{{ old('payout_account', $user->payout_account ?? '') }}" maxlength="50"
                                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                        @error('payout_account')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="payout_account_name" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-500">Nama pemilik akun</label>
                                        <input type="text" name="payout_account_name" id="payout_account_name" value="{{ old('payout_account_name', $user->payout_account_name ?? '') }}" maxlength="100"
                                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                        @error('payout_account_name')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                <button type="button" onclick="confirm('Apakah Anda yakin ingin menghapus akun? Semua data akan hilang permanen.')" class="rounded-2xl px-5 py-3 text-center text-sm font-bold text-red-600 transition hover:bg-red-50">
                                    Hapus akun
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/10 transition duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/20 active:scale-[0.98]">
                                    Simpan perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

</x-layouts.app>
