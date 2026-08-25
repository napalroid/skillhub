import React, { useState, useEffect } from 'react';
import { PageTransition } from './PageTransition';
import { SkeletonRow } from './Skeleton';

const CategoryCard = ({ category, index }) => {
  const hasImage = category.image;
  const hasIcon = category.icon;
  const isFileIcon = hasIcon && category.icon_is_file;

  return (
    <div className="bg-white border border-[#DDDDDD] rounded-lg p-5 shadow-sm hover:shadow-md hover:border-[#000000] hover:-translate-y-1 transition-all duration-200">
      <div className="flex items-start gap-3 mb-4">
        <div className="w-12 h-12 rounded-lg bg-[#F5F5F5] flex items-center justify-center shrink-0 overflow-hidden border border-[#DDDDDD]">
          {hasImage ? (
            <img src={category.image_url} alt={category.name} className="w-full h-full object-cover" />
          ) : hasIcon ? (
            isFileIcon ? (
              <img src={category.icon_url} alt={category.name} className="w-8 h-8 object-contain" />
            ) : (
              <span className="text-2xl">{category.icon}</span>
            )
          ) : (
            <span className="text-2xl">{category.display_icon}</span>
          )}
        </div>
        <div className="min-w-0 flex-1">
          <p className="text-sm font-bold text-black truncate font-heading">{category.name}</p>
          <p className="text-[10px] text-[#999999] mt-1">
            {category.subcategories_count} subkategori • {category.services_count} jasa
          </p>
        </div>
      </div>

      {category.subcategories && category.subcategories.length > 0 && (
        <div className="space-y-2 mb-4">
          {category.subcategories.map((sub) => (
            <div key={sub.id} className="flex items-center justify-between px-3 py-2 bg-[#F5F5F5] rounded-md border border-[#DDDDDD]">
              <div className="flex items-center gap-2 min-w-0 flex-1">
                <span className="w-5 h-5 rounded bg-[#F5F5F5] flex items-center justify-center text-xs font-bold shrink-0 border border-[#DDDDDD] font-heading">
                  {sub.name.charAt(0).toUpperCase()}
                </span>
                <span className="text-xs font-medium text-black truncate">{sub.name}</span>
                <span className="text-[10px] font-bold text-[#555555] bg-[#EAEAEA] px-1.5 py-0.5 rounded border border-[#DDDDDD] font-heading">
                  {sub.services_count} jasa
                </span>
              </div>
              <div className="flex gap-1 shrink-0">
                <a href={`/admin/subcategories/${sub.id}/edit`}
                   className="text-[10px] font-bold text-black hover:text-[#E4002B] border border-[#DDDDDD] rounded px-2 py-1 transition hover:bg-[#F5F5F5] font-heading">
                  Edit
                </a>
                <form action={`/admin/subcategories/${sub.id}`} method="POST" className="inline">
                  <input type="hidden" name="_method" value="DELETE" />
                  <input type="hidden" name="_token" value={window.Laravel?.csrfToken || ''} />
                  <button type="submit" 
                          onClick={(e) => { if (!confirm('PERINGATAN: Akan menghapus subkategori INI DAN SEMUA JASANYA! Yakin?')) e.preventDefault(); }}
                          className="text-[10px] font-bold text-[#E4002B] hover:bg-[#E4002B]/5 border border-[#E4002B]/20 rounded px-2 py-1 transition font-heading">
                  Hapus
                </button>
              </form>
              </div>
            </div>
          ))}
        </div>
      )}

      <div className="flex gap-2">
        <a href={`/admin/categories/${category.id}/edit`}
           className="flex-1 text-center text-[10px] font-bold text-black border-2 border-[#000000] rounded px-3 py-2 transition hover:bg-[#000000] hover:text-white font-heading">
          Edit Kategori
        </a>
        <form action={`/admin/categories/${category.id}`} method="POST" className="inline flex-1">
          <input type="hidden" name="_method" value="DELETE" />
          <input type="hidden" name="_token" value={window.Laravel?.csrfToken || ''} />
          <button type="submit" 
                  onClick={(e) => { if (!confirm('PERINGATAN: Akan menghapus kategori, SEMUA subkategori, DAN jasanya! Yakin?')) e.preventDefault(); }}
                  className="w-full text-center text-[10px] font-bold text-[#E4002B] border-2 border-[#E4002B] rounded px-3 py-2 transition hover:bg-[#E4002B] hover:text-white font-heading">
            Hapus Semua
          </button>
        </form>
      </div>
    </div>
  );
};

export const CategoryGrid = ({ 
  initialCategories = [], 
  initialLoading = false,
  fetchUrl = '/admin/categories/data'
}) => {
  const [categories, setCategories] = useState(initialCategories);
  const [loading, setLoading] = useState(initialLoading);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (initialLoading || categories.length === 0) {
      const fetchData = async () => {
        try {
          setLoading(true);
          const response = await fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const data = await response.json();
          setCategories(data.categories || []);
        } catch (err) {
          setError(err.message);
        } finally {
          setLoading(false);
        }
      };
      fetchData();
    }
  }, [fetchUrl, initialLoading, categories.length]);

  return (
    <PageTransition staggerItems={Array.from({ length: categories.length || 6 }, (_, i) => i)}>
      {loading ? (
        <SkeletonRow columns={3} />
      ) : error ? (
        <div className="col-span-full text-center py-12">
          <p className="text-[#E4002B]">Gagal memuat data: {error}</p>
          <button onClick={() => window.location.reload()} className="mt-2 text-black underline">Coba lagi</button>
        </div>
      ) : categories.length === 0 ? (
        <div className="lg:col-span-3 text-center py-16">
          <div className="w-12 h-12 rounded-full bg-[#E4002B]/5 border-2 border-[#E4002B]/20 flex items-center justify-center mx-auto mb-3">
            <svg className="w-6 h-6 text-[#999999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
          </div>
          <p className="text-sm text-[#999999]">Belum ada kategori.</p>
          <button onClick={() => document.getElementById('create-category-modal')?.classList.remove('hidden')} 
                  className="mt-3 inline-flex items-center gap-1 text-sm font-bold text-[#E4002B] hover:text-black font-heading">
            <span>+</span> Buat kategori pertama
          </button>
        </div>
      ) : (
        <section className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {categories.map((category, index) => (
            <CategoryCard key={category.id} category={category} index={index} />
          ))}
        </section>
      )}
    </PageTransition>
  );
};

export default CategoryGrid;