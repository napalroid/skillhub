import React from 'react';
import { createRoot } from 'react-dom/client';
import { CategoryGrid } from './components/CategoryGrid';
import { SubcategoryGrid } from './components/SubcategoryGrid';

window.mountAdminComponents = (data) => {
  const { 
    categoryContainerId = 'category-grid-root',
    subcategoryContainerId = 'subcategory-grid-root',
    categories = [],
    subcategories = [],
    categoriesForSub = [],
    loading = false,
    categoryFetchUrl = '/admin/categories/data',
    subcategoryFetchUrl = '/admin/subcategories/data'
  } = data;

  // Mount CategoryGrid
  const categoryEl = document.getElementById(categoryContainerId);
  if (categoryEl && !categoryEl._reactRoot) {
    const root = createRoot(categoryEl);
    root.render(
      React.createElement(CategoryGrid, {
        initialCategories: categories,
        initialLoading: loading,
        fetchUrl: categoryFetchUrl
      })
    );
    categoryEl._reactRoot = root;
  }

  // Mount SubcategoryGrid
  const subcategoryEl = document.getElementById(subcategoryContainerId);
  if (subcategoryEl && !subcategoryEl._reactRoot) {
    const root = createRoot(subcategoryEl);
    root.render(
      React.createElement(SubcategoryGrid, {
        initialSubcategories: subcategories,
        initialCategories: categoriesForSub,
        initialLoading: loading,
        fetchUrl: subcategoryFetchUrl
      })
    );
    subcategoryEl._reactRoot = root;
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const categoryEl = document.getElementById('category-grid-root');
  const subcategoryEl = document.getElementById('subcategory-grid-root');

  if (categoryEl) {
    const data = JSON.parse(categoryEl.dataset.props || '{}');
    window.mountAdminComponents({
      categoryContainerId: 'category-grid-root',
      categories: data.categories || [],
      loading: data.loading || false,
      categoryFetchUrl: data.categoryFetchUrl || '/admin/categories/data'
    });
  }

  if (subcategoryEl) {
    const data = JSON.parse(subcategoryEl.dataset.props || '{}');
    window.mountAdminComponents({
      subcategoryContainerId: 'subcategory-grid-root',
      subcategories: data.subcategories || [],
      categoriesForSub: data.categories || [],
      loading: data.loading || false,
      subcategoryFetchUrl: data.subcategoryFetchUrl || '/admin/subcategories/data'
    });
  }
});

export default {};