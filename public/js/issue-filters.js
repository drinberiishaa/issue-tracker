/**
 * Issues index page: debounced text search plus status/priority/tag filters,
 * all applied via AJAX against the issues.search endpoint (no full page reload).
 */
(function () {
    const searchInput = document.getElementById('issue-search');
    const statusSelect = document.getElementById('filter-status');
    const prioritySelect = document.getElementById('filter-priority');
    const tagButtons = document.querySelectorAll('.tag-filter-btn');
    const listContainer = document.getElementById('issues-list');

    if (!listContainer) return;

    let currentTagId = '';

    // Determine initial active tag from the button that's already highlighted server-side.
    tagButtons.forEach((btn) => {
        if (btn.classList.contains('bg-brand-600')) {
            currentTagId = btn.dataset.tagId;
        }
    });

    async function applyFilters(page = 1) {
        const params = new URLSearchParams();
        if (searchInput && searchInput.value.trim()) params.set('q', searchInput.value.trim());
        if (statusSelect && statusSelect.value) params.set('status', statusSelect.value);
        if (prioritySelect && prioritySelect.value) params.set('priority', prioritySelect.value);
        if (currentTagId) params.set('tag', currentTagId);
        if (page > 1) params.set('page', page);

        const url = `/issues/search/ajax?${params.toString()}`;

        listContainer.style.opacity = '0.6';
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const html = await response.text();
            listContainer.innerHTML = html;
            bindPaginationLinks();
        } catch (e) {
            // Leave existing list in place on error.
        } finally {
            listContainer.style.opacity = '1';
        }
    }

    function bindPaginationLinks() {
        listContainer.querySelectorAll('a[href*="page="]').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = new URL(link.href);
                const page = url.searchParams.get('page') || 1;
                applyFilters(page);
            });
        });
    }

    const debouncedSearch = window.debounce(() => applyFilters(1), 350);

    if (searchInput) searchInput.addEventListener('input', debouncedSearch);
    if (statusSelect) statusSelect.addEventListener('change', () => applyFilters(1));
    if (prioritySelect) prioritySelect.addEventListener('change', () => applyFilters(1));

    tagButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            currentTagId = btn.dataset.tagId || '';
            tagButtons.forEach((b) => {
                b.classList.remove('bg-brand-600', 'text-white', 'border-brand-600');
                b.classList.add('border-gray-300', 'text-gray-600');
            });
            btn.classList.add('bg-brand-600', 'text-white', 'border-brand-600');
            btn.classList.remove('border-gray-300', 'text-gray-600');
            applyFilters(1);
        });
    });

    bindPaginationLinks();
})();
