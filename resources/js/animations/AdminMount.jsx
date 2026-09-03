import React from 'react';
import { createRoot } from 'react-dom/client';
import { CategoryGrid } from '../components/CategoryGrid';

window.mountAdminComponents = (data) => {
  const { 
    categoryContainerId = 'category-grid-root',
    categories = [],
    loading = false,
    categoryFetchUrl = '/admin/categories/data'
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
};

document.addEventListener('DOMContentLoaded', () => {
  const categoryEl = document.getElementById('category-grid-root');

  if (categoryEl) {
    const data = JSON.parse(categoryEl.dataset.props || '{}');
    window.mountAdminComponents({
      categoryContainerId: 'category-grid-root',
      categories: data.categories || [],
      loading: data.loading || false,
      categoryFetchUrl: data.categoryFetchUrl || '/admin/categories/data'
    });
  }
});

export default {};