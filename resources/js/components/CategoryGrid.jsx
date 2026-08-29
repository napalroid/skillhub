import React, { useState, useEffect } from 'react';
import { PageTransition } from './PageTransition';
import { SkeletonRow } from './Skeleton';

const CategoryRow = ({ category }) => {
  const hasImage = !!category.image_url;
  const hasIcon = !!category.icon_url;
  const isFileIcon = hasIcon && category.icon_is_file;

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

  const subs = category.subcategories || [];

  return (
    <article className="group border border-transparent bg-transparent px-5 py-5 transition-colors duration-200 hover:border-black sm:px-6 sm:py-6">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-5">
        <div className="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden border border-[#DDDDDD] text-black transition-transform duration-200 group-hover:-translate-y-0.5">
          {hasImage ? (
            <img src={category.image_url} alt={category.name} className="h-full w-full object-cover" />
          ) : hasIcon ? (
            <img src={category.icon_url} alt={category.name} className="h-7 w-7 object-contain" />
          ) : (
            getIconSvg(category.display_icon)
          )}
        </div>

        <div className="min-w-0 flex-1">
          <h3 className="font-heading text-lg font-bold uppercase tracking-tight text-black sm:text-xl">
            {category.name}
          </h3>
          <p className="mt-1 text-xs font-medium text-[#999999]">
            {category.subcategories_count} subkategori &nbsp;•&nbsp; {category.services_count} jasa
          </p>
        </div>

        <div className="flex shrink-0 items-center gap-2">
          <a href={`/admin/categories/${category.id}/edit`}
             className="inline-flex items-center gap-1.5 px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-black transition-colors duration-150 hover:text-[#555555] font-heading">
            <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            Edit
          </a>
          <form action={`/admin/categories/${category.id}`} method="POST" className="inline">
            <input type="hidden" name="_method" value="DELETE" />
            <input type="hidden" name="_token" value={window.csrfToken || ''} />
            <button type="submit"
                    onClick={(e) => { if (!confirm('PERINGATAN: Akan menghapus kategori, SEMUA subkategori, DAN jasanya! Yakin?')) e.preventDefault(); }}
                    className="inline-flex items-center gap-1.5 px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-[#E4002B] transition-colors duration-150 hover:text-[#E4002B]/80 font-heading">
              <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
              Hapus
            </button>
          </form>
        </div>
      </div>

      <div className="mt-5 border-t border-[#DDDDDD] pt-4 sm:mt-6 sm:pt-5">
        {subs.length > 0 ? (
          <ul className="flex flex-col gap-2.5">
            {subs.map((sub) => (
              <li key={sub.id} className="flex items-center gap-3 border border-transparent px-2 -mx-2 transition-all duration-200 hover:-translate-y-0.5 hover:border-[#DDDDDD] hover:bg-white hover:shadow-[0_4px_12px_rgba(0,0,0,0.10)]">
                <span className="h-1.5 w-1.5 shrink-0 rotate-45 bg-black" aria-hidden="true"></span>
                <span className="min-w-0 flex-1 truncate text-sm font-medium text-black">{sub.name}</span>
                <span className="shrink-0 text-[10px] font-bold uppercase tracking-wider text-[#999999]">
                  {sub.services_count} jasa
                </span>
                <div className="flex shrink-0 items-center gap-1">
                  <a href={`/admin/subcategories/${sub.id}/edit`}
                     className="px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-black transition-colors duration-150 hover:text-[#555555] font-heading">
                    Edit
                  </a>
                  <form action={`/admin/subcategories/${sub.id}`} method="POST" className="inline">
                    <input type="hidden" name="_method" value="DELETE" />
                    <input type="hidden" name="_token" value={window.csrfToken || ''} />
                    <button type="submit"
                            onClick={(e) => { if (!confirm('PERINGATAN: Akan menghapus subkategori INI DAN SEMUA JASANYA! Yakin?')) e.preventDefault(); }}
                            className="px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-[#E4002B] transition-colors duration-150 hover:text-[#E4002B]/80 font-heading"
                            aria-label={`Hapus subkategori ${sub.name}`}>
                      Hapus
                    </button>
                  </form>
                </div>
              </li>
            ))}
          </ul>
        ) : (
          <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <p className="text-sm text-[#999999]">Belum ada subkategori.</p>
            <a href="/admin/subcategories/create"
               className="inline-flex w-fit items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-black hover:text-[#555555] font-heading">
              <span className="text-base leading-none">+</span> Tambahkan Subkategori
            </a>
          </div>
        )}
      </div>
    </article>
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
        <SkeletonRow columns={1} />
      ) : error ? (
        <div className="border border-[#E4002B]/20 bg-[#E4002B]/5 px-4 py-8 text-center">
          <p className="text-sm text-[#E4002B]">Gagal memuat data: {error}</p>
          <button onClick={() => window.location.reload()} className="mt-2 text-sm font-bold text-black underline">Coba lagi</button>
        </div>
      ) : categories.length === 0 ? (
        <div className="border border-[#DDDDDD] bg-white px-6 py-16 text-center">
          <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center border border-[#DDDDDD] bg-[#F5F5F5]">
            <svg className="h-6 w-6 text-[#999999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
            </svg>
          </div>
          <h3 className="font-heading text-lg font-bold uppercase tracking-tight text-black">Belum ada kategori</h3>
          <p className="mx-auto mt-2 max-w-sm text-sm text-[#999999]">
            Tambahkan kategori pertama untuk mulai mengatur struktur SkillHub.
          </p>
          <button
            onClick={() => window.dispatchEvent(new CustomEvent('skillhub:open-category-modal'))}
            className="mt-5 inline-flex items-center gap-1.5 bg-black px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white transition hover:bg-black/80 font-heading">
            <span className="text-base leading-none">+</span> Tambahkan Kategori
          </button>
        </div>
      ) : (
        <section className="flex flex-col">
          {categories.map((category, i) => (
            <div key={category.id} className={i > 0 ? 'border-t border-[#E5E5E5]' : ''}>
              <CategoryRow category={category} />
            </div>
          ))}
        </section>
      )}
    </PageTransition>
  );
};

export default CategoryGrid;
