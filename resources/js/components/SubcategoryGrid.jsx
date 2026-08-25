import React, { useState, useEffect } from 'react';
import { PageTransition } from './PageTransition';
import { SkeletonRow } from './Skeleton';

const SubcategoryCard = ({ subcategory, index }) => {
  return (
    <div className="bg-white border border-[#DDDDDD] rounded-lg p-5 shadow-sm hover:shadow-md hover:border-[#000000] hover:-translate-y-1 transition-all duration-200">
      <div className="flex items-center gap-3 mb-4">
        <span className="w-10 h-10 rounded-lg bg-[#F5F5F5] flex items-center justify-center text-xs font-bold shrink-0 border border-[#DDDDDD] font-heading">
          {subcategory.name.charAt(0).toUpperCase()}
        </span>
        <div className="min-w-0 flex-1">
          <p className="text-sm font-bold text-black truncate font-heading">{subcategory.name}</p>
          <p className="text-[10px] text-[#999999] mt-1">
            Kategori: {subcategory.category?.name || 'Tidak diketahui'}
          </p>
        </div>
      </div>

      <div className="mb-4">
        <span className="text-[10px] font-bold text-[#555555] bg-[#EAEAEA] px-2 py-0.5 rounded border border-[#DDDDDD] font-heading">
          {subcategory.services_count} jasa
        </span>
      </div>

      <div className="flex gap-2">
        <a href={`/admin/subcategories/${subcategory.id}/edit`}
           className="flex-1 text-center text-[10px] font-bold text-black border-2 border-[#000000] rounded px-3 py-2 transition hover:bg-[#000000] hover:text-white font-heading">
          Edit
        </a>
        <form action={`/admin/subcategories/${subcategory.id}`} method="POST" className="inline flex-1">
          <input type="hidden" name="_method" value="DELETE" />
          <input type="hidden" name="_token" value={window.Laravel?.csrfToken || ''} />
          <button type="submit" 
                  onClick={(e) => { if (!confirm('PERINGATAN: Akan menghapus subkategori INI DAN SEMUA JASANYA! Yakin?')) e.preventDefault(); }}
                  className="w-full text-center text-[10px] font-bold text-[#E4002B] border-2 border-[#E4002B] rounded px-3 py-2 transition hover:bg-[#E4002B] hover:text-white font-heading">
          Hapus + Jasa
        </button>
        </form>
      </div>
    </div>
  );
};

export const SubcategoryGrid = ({ 
  initialSubcategories = [], 
  initialCategories = [],
  initialLoading = false,
  fetchUrl = '/admin/subcategories/data'
}) => {
  const [subcategories, setSubcategories] = useState(initialSubcategories);
  const [categories, setCategories] = useState(initialCategories);
  const [loading, setLoading] = useState(initialLoading);
  const [error, setError] = useState(null);
  const [showCreateModal, setShowCreateModal] = useState(false);

  useEffect(() => {
    if (initialLoading || subcategories.length === 0) {
      const fetchData = async () => {
        try {
          setLoading(true);
          const response = await fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const data = await response.json();
          setSubcategories(data.subcategories || []);
          setCategories(data.categories || []);
        } catch (err) {
          setError(err.message);
        } finally {
          setLoading(false);
        }
      };
      fetchData();
    }
  }, [fetchUrl, initialLoading, subcategories.length]);

  return (
    <>
      <PageTransition staggerItems={Array.from({ length: subcategories.length || 6 }, (_, i) => i)}>
        {loading ? (
          <SkeletonRow columns={3} />
        ) : error ? (
          <div className="col-span-full text-center py-12">
            <p className="text-[#E4002B]">Gagal memuat data: {error}</p>
            <button onClick={() => window.location.reload()} className="mt-2 text-black underline">Coba lagi</button>
          </div>
        ) : subcategories.length === 0 ? (
          <div className="lg:col-span-3 text-center py-16">
            <div className="w-12 h-12 rounded-full bg-[#E4002B]/5 border-2 border-[#E4002B]/20 flex items-center justify-center mx-auto mb-3">
              <svg className="w-6 h-6 text-[#999999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </div>
            <p className="text-sm text-[#999999]">Belum ada subkategori.</p>
            <button onClick={() => setShowCreateModal(true)} 
                    className="mt-3 inline-flex items-center gap-1 text-sm font-bold text-[#E4002B] hover:text-black font-heading">
              <span>+</span> Buat subkategori pertama
            </button>
          </div>
        ) : (
          <section className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {subcategories.map((sub, index) => (
              <SubcategoryCard key={sub.id} subcategory={sub} index={index} />
            ))}
          </section>
        )}
      </PageTransition>

      {/* Create Modal */}
      {showCreateModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" 
             onClick={() => setShowCreateModal(false)}
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
          <div className="w-full max-w-md bg-white rounded-md border border-[#000000] p-6 shadow-xl" 
               onClick={(e) => e.stopPropagation()}
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95">
            <form method="POST" action="/admin/subcategories">
              <input type="hidden" name="_token" value={window.Laravel?.csrfToken || ''} />
              <div className="flex items-center justify-between mb-6">
                <h3 className="font-heading font-semibold text-sm text-black">Tambah Subkategori</h3>
                <button type="button" onClick={() => setShowCreateModal(false)} className="btn-ghost p-1" aria-label="Tutup modal">&times;</button>
              </div>
              <div className="space-y-5">
                <div>
                  <label className="block text-[10px] font-bold tracking-wider text-[#999999] uppercase mb-1 font-heading">
                    Nama Subkategori <span className="text-[#E4002B]">*</span>
                  </label>
                  <input type="text" name="name" required
                    className="w-full border-2 border-[#000000] rounded px-3 py-2 text-sm text-black focus:border-[#000000] outline-none transition"
                    placeholder="Contoh: Desain Logo" />
                </div>
                <div>
                  <label className="block text-[10px] font-bold tracking-wider text-[#999999] uppercase mb-1 font-heading">
                    Kategori Induk <span className="text-[#E4002B]">*</span>
                  </label>
                  <select name="category_id" required
                    className="w-full border-2 border-[#000000] rounded px-3 py-2 text-sm text-black focus:border-[#000000] outline-none transition">
                    <option value="">Pilih kategori</option>
                    {categories.map(cat => (
                      <option key={cat.id} value={cat.id}>{cat.name}</option>
                    ))}
                  </select>
                </div>
                <div className="flex justify-end gap-2 pt-4 border-t border-[#DDDDDD]">
                  <button type="button" onClick={() => setShowCreateModal(false)}
                    className="text-[10px] font-bold text-[#999999] border-2 border-[#000000] rounded px-4 py-2 hover:bg-[#F5F5F5] transition font-heading">
                    Batal
                  </button>
                  <button type="submit"
                    className="text-[10px] font-bold text-white bg-[#000000] rounded px-4 py-2 hover:bg-[#000000]/80 transition font-heading">
                    Simpan Subkategori
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
};

export default SubcategoryGrid;