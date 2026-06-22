/**
 * Shared helpers used across the app's small AJAX scripts.
 * Exposes window.appFetch — a fetch wrapper that automatically attaches
 * the CSRF token and JSON headers expected by Laravel.
 */
(function () {
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * @param {string} url
     * @param {object} options - fetch options. body, if present, should be a plain object (will be JSON.stringify'd).
     */
    window.appFetch = async function appFetch(url, options = {}) {
        const headers = {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
            ...(options.headers || {}),
        };

        let body = options.body;
        if (body && typeof body === 'object' && !(body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
            body = JSON.stringify(body);
        }

        const response = await fetch(url, { ...options, headers, body });
        let data = null;
        try {
            data = await response.json();
        } catch (e) {
            data = null;
        }

        if (!response.ok) {
            const error = new Error('Request failed');
            error.status = response.status;
            error.data = data;
            throw error;
        }

        return data;
    };

    /**
     * Simple debounce helper used by search/filter inputs.
     */
    window.debounce = function debounce(fn, wait = 300) {
        let timeout;
        return function debounced(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), wait);
        };
    };
})();
