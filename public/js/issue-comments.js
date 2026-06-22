/**
 * Issue detail page: loads comments via AJAX (paginated), and posts new
 * comments via AJAX — prepending the new comment to the list and clearing
 * the form, with inline (non-alert) validation errors.
 */
(function () {
    const form = document.getElementById('comment-form');
    const listEl = document.getElementById('comments-list');
    const paginationEl = document.getElementById('comments-pagination');
    if (!form || !listEl) return;

    const issueId = form.dataset.issueId;
    const errorsBox = document.getElementById('comment-errors');
    const errorsList = errorsBox.querySelector('ul');

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function formatDate(isoString) {
        const date = new Date(isoString);
        return date.toLocaleString(undefined, {
            month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit',
        });
    }

    function commentHtml(comment) {
        return `
            <div class="flex gap-3 comment-item" data-comment-id="${comment.id}">
                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold shrink-0">
                    ${escapeHtml(comment.author_name.slice(0, 2).toUpperCase())}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="font-medium text-sm text-gray-900">${escapeHtml(comment.author_name)}</span>
                        <span class="text-xs text-gray-400">${formatDate(comment.created_at)}</span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-line">${escapeHtml(comment.body)}</p>
                </div>
            </div>
        `;
    }

    function renderPage(data, { append = false } = {}) {
        const html = data.data.map(commentHtml).join('');
        if (append) {
            listEl.insertAdjacentHTML('beforeend', html);
        } else {
            listEl.innerHTML = html || '<p class="text-sm text-gray-400">No comments yet. Be the first to comment.</p>';
        }
        renderPagination(data);
    }

    function renderPagination(data) {
        paginationEl.innerHTML = '';
        if (data.next_page_url) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'text-sm text-brand-600 hover:text-brand-700 font-medium';
            btn.textContent = 'Load more comments';
            btn.addEventListener('click', () => loadPage(data.current_page + 1, { append: true }));
            paginationEl.appendChild(btn);
        }
    }

    async function loadPage(page = 1, { append = false } = {}) {
        try {
            const data = await window.appFetch(`/issues/${issueId}/comments?page=${page}`, { method: 'GET' });
            renderPage(data, { append });
        } catch (e) {
            listEl.innerHTML = '<p class="text-sm text-red-500">Could not load comments.</p>';
        }
    }

    function showErrors(messages) {
        errorsList.innerHTML = messages.map((m) => `<li>${escapeHtml(m)}</li>`).join('');
        errorsBox.classList.remove('hidden');
    }

    function hideErrors() {
        errorsBox.classList.add('hidden');
        errorsList.innerHTML = '';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideErrors();

        const authorName = form.querySelector('#author_name').value.trim();
        const body = form.querySelector('#body').value.trim();

        const submitBtn = form.querySelector('button[type="submit"]');
        const submitLabel = submitBtn.querySelector('.submit-label');
        submitBtn.disabled = true;
        submitLabel.textContent = 'Posting…';

        try {
            const data = await window.appFetch(`/issues/${issueId}/comments`, {
                method: 'POST',
                body: { author_name: authorName, body },
            });

            // Prepend the new comment and clear the form, per requirements.
            listEl.insertAdjacentHTML('afterbegin', commentHtml(data.comment));
            const emptyMsg = listEl.querySelector('p.text-gray-400');
            if (emptyMsg) emptyMsg.remove();
            form.reset();
        } catch (err) {
            if (err.status === 422 && err.data && err.data.errors) {
                const messages = Object.values(err.data.errors).flat();
                showErrors(messages);
            } else {
                showErrors(['Something went wrong. Please try again.']);
            }
        } finally {
            submitBtn.disabled = false;
            submitLabel.textContent = 'Post Comment';
        }
    });

    loadPage(1);
})();
