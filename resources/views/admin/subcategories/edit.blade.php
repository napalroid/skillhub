<x-layouts.admin>
    {{-- PAGE HEADER --}}
    <div class="mb-8" data-stagger-item>
        <div class="flex items-start justify-between gap-4 mb-2">
            <div>
                <h1 class="font-heading font-bold text-3xl lg:text-4xl text-black uppercase tracking-tight">Edit Subkategori</h1>
                <div class="mt-2 flex items-center gap-3 text-sm">
                    <span class="text-[#555555]">{{ $subcategory->category->name }}</span>
                    <span class="text-[#DDDDDD]">/</span>
                    <span class="font-heading font-bold text-black">{{ $subcategory->name }}</span>
                </div>
            </div>
            <a href="{{ route('admin.categories.edit', $subcategory->category) }}" class="btn-ghost text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali
            </a>
        </div>
        
        {{-- STATS BAR --}}
        <div class="mt-4 flex items-center gap-6">
            <div class="flex items-center gap-2">
                <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Total Jasa</span>
                <span class="font-heading font-bold text-2xl text-black">{{ $services->count() }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- LEFT: SUBCATEGORY INFO FORM --}}
        <div class="lg:col-span-1">
            <div class="admin-card p-6">
                <h2 class="font-heading font-bold text-sm uppercase tracking-wider text-black mb-4">Informasi Subkategori</h2>
                
                <form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}" class="space-y-4">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="label-field" for="sub-name">Nama <span class="text-[#E4002B] font-normal">*</span></label>
                        <input type="text" id="sub-name" name="name" value="{{ $subcategory->name }}" required class="input-field">
                        @error('name') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-field" for="sub-category">Kategori Induk <span class="text-[#E4002B] font-normal">*</span></label>
                        <select id="sub-category" name="category_id" required class="input-field">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($subcategory->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-[#DDDDDD]">
                        <button type="submit" class="btn-primary text-xs px-4 py-2">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: SERVICE MANAGEMENT --}}
        <div class="lg:col-span-2">
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-heading font-bold text-sm uppercase tracking-wider text-black">Jasa dalam Subkategori</h2>
                </div>

                {{-- SERVICE LIST --}}
                @if($services->count() > 0)
                    <div class="space-y-2" id="service-list">
                         @foreach($services as $service)
                             <div class="border border-[#DDDDDD] rounded-sm hover:border-black transition-colors duration-150 overflow-hidden group service-item" 
                                  data-service-id="{{ $service->id }}"
                                  data-service-json="{{ json_encode([
                                      'id' => $service->id,
                                      'title' => $service->title,
                                      'seller' => $service->seller->name,
                                      'price' => $service->price,
                                      'rating' => $service->avg_rating,
                                      'ordersCount' => $service->orders_count,
                                      'status' => $service->status,
                                      'description' => $service->description ?? '',
                                      'image' => $service->image ?? '',
                                      'subcategory' => [
                                          'id' => $subcategory->id,
                                          'name' => $subcategory->name,
                                          'categoryName' => $subcategory->category->name
                                      ]
                                  ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
                                <div class="flex gap-4 p-4">
                                    {{-- SERVICE IMAGE --}}
                                    <div class="w-28 h-20 rounded-sm overflow-hidden bg-[#F5F5F5] shrink-0">
                                        <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.png') }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                                    </div>

                                    {{-- SERVICE INFO --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-heading font-bold text-base text-black truncate">{{ $service->title }}</h3>
                                        <p class="text-sm text-[#555555] mt-0.5">{{ $service->seller->name }}</p>
                                        
                                        <div class="flex items-center gap-4 mt-2 text-xs">
                                            <span class="font-heading font-bold text-black">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                            
                                            @if($service->avg_rating > 0)
                                                <span class="flex items-center gap-1 text-[#555555]">
                                                    <svg class="w-3.5 h-3.5 text-[#EDE734]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    {{ number_format($service->avg_rating, 1) }}
                                                </span>
                                            @endif
                                            
                                            @if($service->orders_count > 0)
                                                <span class="text-[#555555]">{{ $service->orders_count }} pesanan</span>
                                            @endif
                                            
                                            <span class="badge badge-{{ $service->status === 'approved' ? 'success' : ($service->status === 'pending' ? 'pending' : 'accent') }}">
                                                {{ ucfirst($service->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div class="flex items-center gap-2">
                                        <button 
                                            class="btn-detail px-3 py-2 text-xs font-heading font-bold uppercase tracking-wider text-black hover:bg-black/5 rounded-sm transition-colors duration-150">
                                            Detail
                                        </button>
                                        <button 
                                            class="btn-remove px-3 py-2 text-xs font-heading font-bold uppercase tracking-wider text-[#E4002B] hover:bg-[#E4002B]/5 rounded-sm transition-colors duration-150">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg class="w-8 h-8 text-[#999999]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <h3 class="font-heading font-bold text-lg text-black">Belum ada jasa</h3>
                        <p class="text-sm text-[#555555] mt-1">Subkategori ini belum memiliki jasa yang terdaftar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- DETAIL SERVICE MODAL --}}
    <div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
        <div class="bg-white border border-[#DDDDDD] rounded-sm w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col" style="display: flex;">
            {{-- MODAL HEADER --}}
            <div class="px-6 py-4 border-b border-[#DDDDDD]">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-lg uppercase tracking-tight text-black">Detail Jasa</h3>
                    <button id="close-detail-modal" class="p-2 hover:bg-[#F5F5F5] rounded-sm transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- MODAL BODY --}}
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- SERVICE IMAGE --}}
                    <div class="aspect-video rounded-sm overflow-hidden bg-[#F5F5F5]">
                        <img id="detail-img" src="" alt="" class="w-full h-full object-cover">
                    </div>

                    {{-- SERVICE INFO --}}
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Nama Jasa</span>
                            <h4 id="detail-title" class="font-heading font-bold text-xl text-black mt-1"></h4>
                        </div>

                        <div>
                            <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Penyedia Jasa</span>
                            <p id="detail-seller" class="text-sm text-black mt-1"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Harga</span>
                                <p id="detail-price" class="font-heading font-bold text-lg text-black mt-1"></p>
                            </div>
                            <div>
                                <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Status</span>
                                <div class="mt-1" id="detail-status-container"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Rating</span>
                                <div class="flex items-center gap-2 mt-1" id="detail-rating-container"></div>
                            </div>
                            <div>
                                <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Pesanan</span>
                                <p id="detail-orders" class="font-heading font-bold text-black mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mt-6 pt-6 border-t border-[#DDDDDD]">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Deskripsi</span>
                    <p id="detail-desc" class="text-sm text-[#555555] mt-2 whitespace-pre-line"></p>
                </div>

                {{-- SUBCATEGORY INFO --}}
                <div class="mt-6 pt-6 border-t border-[#DDDDDD]">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555]">Subkategori</span>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm text-[#555555]" id="detail-category-name"></span>
                        <span class="text-[#DDDDDD]">/</span>
                        <span class="font-heading font-bold text-sm text-black" id="detail-subcategory-name"></span>
                    </div>
                </div>
            </div>

            {{-- MODAL FOOTER --}}
            <div class="px-6 py-4 border-t border-[#DDDDDD] flex justify-end">
                <button id="close-detail-modal-footer" class="btn-outline text-xs px-4 py-2">Tutup</button>
            </div>
        </div>
    </div>

    {{-- REMOVE SERVICE MODAL --}}
    <div id="remove-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
        <div class="bg-white border border-[#DDDDDD] rounded-sm w-full max-w-lg">
            {{-- MODAL HEADER --}}
            <div class="px-6 py-4 border-b border-[#DDDDDD]">
                <h3 class="font-heading font-bold text-lg uppercase tracking-tight text-black">Hapus Jasa dari Subkategori</h3>
            </div>

            {{-- MODAL BODY --}}
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555] mb-2">Jasa yang akan dihapus</p>
                    <div class="border border-[#DDDDDD] rounded-sm p-4 bg-[#FAFAFA]">
                        <p id="remove-service-title" class="font-heading font-bold text-base text-black"></p>
                        <p id="remove-service-seller" class="text-sm text-[#555555] mt-1"></p>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#555555] mb-2">Subkategori</p>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-[#555555]" id="remove-category-name"></span>
                        <span class="text-[#DDDDDD]">/</span>
                        <span class="font-heading font-bold text-sm text-black" id="remove-subcategory-name"></span>
                    </div>
                </div>

                <div>
                    <label class="label-field" for="removal-reason">Alasan Penghapusan <span class="text-[#E4002B] font-normal">*</span></label>
                    <select id="removal-reason" class="input-field">
                        <option value="">Pilih alasan</option>
                        <option value="Jasa tidak sesuai dengan subkategori">Jasa tidak sesuai dengan subkategori</option>
                        <option value="Jasa melanggar ketentuan platform">Jasa melanggar ketentuan platform</option>
                        <option value="Informasi jasa tidak lengkap">Informasi jasa tidak lengkap</option>
                        <option value="Jasa sudah tidak relevan">Jasa sudah tidak relevan</option>
                        <option value="Jasa memiliki konten yang tidak sesuai">Jasa memiliki konten yang tidak sesuai</option>
                        <option value="Duplikasi jasa">Duplikasi jasa</option>
                        <option value="Permintaan dari penyedia jasa">Permintaan dari penyedia jasa</option>
                        <option value="other">Alasan lainnya</option>
                    </select>
                    <p id="removal-reason-error" class="mt-1 text-xs text-[#E4002B]" style="display: none;"></p>
                </div>

                <div id="other-reason-container" class="space-y-2" style="display: none;">
                    <label class="label-field" for="removal-reason-detail">Jelaskan alasan penghapusan <span class="text-[#E4002B] font-normal">*</span></label>
                    <textarea id="removal-reason-detail" rows="3" placeholder="Tulis alasan secara manual..." class="input-field resize-none"></textarea>
                    <p id="removal-reason-detail-error" class="text-xs text-[#E4002B]" style="display: none;"></p>
                </div>

                <div class="bg-[#FFF8F8] border border-[#E4002B]/20 rounded-sm p-4">
                    <p class="text-xs text-[#555555]">
                        <span class="font-heading font-bold text-[#E4002B] uppercase tracking-wider">Catatan:</span>
                        User pemilik jasa akan menerima notifikasi tentang penghapusan ini beserta alasan yang Anda berikan.
                    </p>
                </div>
            </div>

            {{-- MODAL FOOTER --}}
            <div class="px-6 py-4 border-t border-[#DDDDDD] flex justify-end gap-2">
                <button id="close-remove-modal" class="btn-outline text-xs px-4 py-2">Batal</button>
                <button id="confirm-remove-service" class="btn-danger text-xs px-4 py-2">
                    Hapus dari Subkategori
                </button>
            </div>
        </div>
    </div>

    <script>
    // Detail Modal Logic
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function() {
            const serviceItem = this.closest('.service-item');
            if (!serviceItem) return;
            
            const serviceData = JSON.parse(serviceItem.dataset.serviceJson);
            openDetailModal(serviceData);
        });
    });

    function openDetailModal(service) {
        document.getElementById('detail-title').textContent = service.title;
        document.getElementById('detail-seller').textContent = service.seller;
        document.getElementById('detail-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(service.price);
        document.getElementById('detail-orders').textContent = service.ordersCount > 0 ? service.ordersCount + ' pesanan' : 'Belum ada pesanan';
        document.getElementById('detail-desc').textContent = service.description || 'Tidak ada deskripsi';
        
        // Status badge
        const statusContainer = document.getElementById('detail-status-container');
        const badgeClass = service.status === 'approved' ? 'badge-success' : (service.status === 'pending' ? 'badge-pending' : 'badge-accent');
        statusContainer.innerHTML = `<span class="badge ${badgeClass}">${service.status.charAt(0).toUpperCase() + service.status.slice(1)}</span>`;
        
        // Rating
        const ratingContainer = document.getElementById('detail-rating-container');
        if (service.rating > 0) {
            ratingContainer.innerHTML = `
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-[#EDE734]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span class="font-heading font-bold text-black">${Number(service.rating).toFixed(1)}</span>
                </span>
            `;
        } else {
            ratingContainer.innerHTML = '<span class="text-sm text-[#999999]">Belum ada rating</span>';
        }
        
        // Image
        const detailImg = document.getElementById('detail-img');
        if (service.image) {
            detailImg.src = '/storage/' + service.image;
            detailImg.alt = service.title;
        } else {
            detailImg.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23DDDDDD\' stroke-width=\'1.5\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/%3E%3C/svg%3E';
        }
        
        // Subcategory info
        document.getElementById('detail-category-name').textContent = service.subcategory.categoryName;
        document.getElementById('detail-subcategory-name').textContent = service.subcategory.name;
        
        document.getElementById('detail-modal').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('detail-modal').style.display = 'none';
    }

    // Detail modal close handlers
    document.getElementById('close-detail-modal').addEventListener('click', closeDetailModal);
    document.getElementById('close-detail-modal-footer').addEventListener('click', closeDetailModal);
    document.getElementById('detail-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    // Remove Modal Logic
    let currentRemoveServiceId = null;
    let currentRemoveData = null;

    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', function() {
            const serviceItem = this.closest('.service-item');
            if (!serviceItem) return;
            
            const serviceData = JSON.parse(serviceItem.dataset.serviceJson);
            currentRemoveData = serviceData;
            currentRemoveServiceId = serviceData.id;
            
            openRemoveModal(serviceData);
        });
    });

    function openRemoveModal(service) {
        document.getElementById('remove-service-title').textContent = service.title;
        document.getElementById('remove-service-seller').textContent = service.seller;
        document.getElementById('remove-category-name').textContent = service.subcategory.categoryName;
        document.getElementById('remove-subcategory-name').textContent = service.subcategory.name;
        
        document.getElementById('removal-reason').value = '';
        document.getElementById('removal-reason-detail').value = '';
        document.getElementById('other-reason-container').style.display = 'none';
        
        document.getElementById('remove-modal').style.display = 'flex';
    }

    function closeRemoveModal() {
        document.getElementById('remove-modal').style.display = 'none';
        currentRemoveServiceId = null;
        currentRemoveData = null;
    }

    // Remove modal close handlers
    document.getElementById('close-remove-modal').addEventListener('click', closeRemoveModal);
    document.getElementById('remove-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRemoveModal();
    });

    // Handle reason selection
    document.getElementById('removal-reason').addEventListener('change', function() {
        if (this.value === 'other') {
            document.getElementById('other-reason-container').style.display = 'block';
            document.getElementById('removal-reason-error').style.display = 'none';
        } else {
            document.getElementById('other-reason-container').style.display = 'none';
        }
    });

    // Confirm remove
    document.getElementById('confirm-remove-service').addEventListener('click', async function() {
        const reason = document.getElementById('removal-reason').value;
        const reasonDetail = document.getElementById('removal-reason-detail').value;
        const reasonError = document.getElementById('removal-reason-error');
        const reasonDetailError = document.getElementById('removal-reason-detail-error');
        
        // Validation
        reasonError.style.display = 'none';
        reasonDetailError.style.display = 'none';
        
        if (!reason) {
            reasonError.textContent = 'Pilih alasan penghapusan';
            reasonError.style.display = 'block';
            return;
        }
        
        if (reason === 'other' && !reasonDetail.trim()) {
            reasonDetailError.textContent = 'Alasan manual wajib diisi';
            reasonDetailError.style.display = 'block';
            return;
        }
        
        // Final reason
        const finalReason = reason === 'other' ? reasonDetail : reason;
        
        this.disabled = true;
        this.textContent = 'Menghapus...';
        
        try {
            const response = await fetch(`/admin/subcategories/{{ $subcategory->id }}/services/${currentRemoveServiceId}/remove`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    reason: reason,
                    reason_detail: reasonDetail
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Show success notification
                const successMessage = data.message + (data.refund_count > 0 ? ` (${data.refund_count} pesanan aktif telah direfund otomatis)` : '');
                
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-20 right-6 z-50 bg-[#2C9F45] text-white border-[#2C9F45] border-2 px-4 py-3 rounded-sm font-heading text-xs font-bold uppercase tracking-wider shadow-lg';
                toast.textContent = successMessage;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'polite');
                document.body.appendChild(toast);
                
                // Auto remove toast after 5 seconds
                setTimeout(() => {
                    toast.remove();
                }, 5000);
                
                // Reload after showing toast
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                alert(data.message || 'Terjadi kesalahan saat menghapus jasa');
            }
        } catch (error) {
            console.error('Error removing service:', error);
            alert('Terjadi kesalahan saat menghapus jasa');
        } finally {
            this.disabled = false;
            this.textContent = 'Hapus dari Subkategori';
        }
    });

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeRemoveModal();
        }
    });
    </script>
</x-layouts.admin>
