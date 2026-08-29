import React, { useState, useEffect } from 'react';
import { PageTransition } from './PageTransition';
import { SkeletonRow } from './Skeleton';

const CategoryCard = ({ category, index }) => {
  const hasImage = !!category.image_url;
  const hasIcon = !!category.icon_url;
  const isFileIcon = hasIcon && category.icon_is_file;

  // Map display_icon to actual SVG icon
  const getIconSvg = (iconName) => {
    const icons = {
      design: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>,
      code: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>,
      camera: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>,
      music: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.553z"/></svg>,
      write: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>,
      learn: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>,
      business: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>,
      star: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>,
    };
    return icons[iconName] || icons.star;
  };

  return (
    <div className="bg-white border border-[#DDDDDD] rounded-lg p-5 shadow-sm hover:shadow-md hover:border-[#000000] hover:-translate-y-1 transition-all duration-200">
      <div className="flex items-start gap-3 mb-4">
        <div className="w-12 h-12 rounded-lg bg-[#F5F5F5] flex items-center justify-center shrink-0 overflow-hidden border border-[#DDDDDD] text-black">
          {hasImage ? (
            <img src={category.image_url} alt={category.name} className="w-full h-full object-cover" />
          ) : hasIcon ? (
            <img src={category.icon_url} alt={category.name} className="w-8 h-8 object-contain" />
          ) : (
            getIconSvg(category.display_icon)
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