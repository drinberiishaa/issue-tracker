/**
 * Issue detail page: tag attach/detach via the "Manage tags" modal,
 * plus inline removal via the x button on each tag pill. All AJAX, no reload.
 */
(function () {
    const tagsList = document.getElementById('issue-tags-list');
    if (!tagsList) return;

    const issueId = tagsList.dataset.issueId;
    const modal = document.getElementById('tag-modal');
    const openBtn = document.getElementById('open-tag-modal');
    const closeBtn = document.getElementById('close-tag-modal');
    const modalTagButtons = document.querySelectorAll('.modal-tag-toggle');
    const newTagNameInput = document.getElementById('new-tag-name');
    const newTagColorInput = document.getElementById('new-tag-color');
    const createTagBtn = document.getElementById('create-tag-btn');
    const newTagError = document.getElementById('new-tag-error');

    function attachedTagIds() {
        return Array.from(tagsList.querySelectorAll('[data-tag-id]')).map((el) => el.dataset.tagId);
    }

    function renderAttachedTags(tags) {
        tagsList.innerHTML = '';
        if (tags.length === 0) {
            tagsList.innerHTML = '<p class="text-sm text-gray-400" id="no-tags-msg">No tags attached.</p>';
        }
        tags.forEach((tag) => {
            const span = document.createElement('span');
            span.className = 'badge tag-pill-removable';
            span.dataset.tagId = tag.id;
            span.style.backgroundColor = `${tag.color || '#6B7280'}1A`;
            span.style.color = tag.color || '#6B7280';
            span.innerHTML = `${escapeHtml(tag.name)} <button type="button" class="detach-tag-btn ml-1.5 hover:opacity-60" data-tag-id="${tag.id}" title="Remove tag">&times;</button>`;
            tagsList.appendChild(span);
        });
        bindDetachButtons();
        syncModalHighlights();
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function bindDetachButtons() {
        tagsList.querySelectorAll('.detach-tag-btn').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const tagId = btn.dataset.tagId;
                try {
                    const data = await window.appFetch(`/issues/${issueId}/tags/${tagId}`, { method: 'DELETE' });
                    renderAttachedTags(data.tags);
                } catch (e) {
                    alertInline('Could not remove tag. Please try again.');
                }
            });
        });
    }

    function syncModalHighlights() {
        const attached = attachedTagIds();
        modalTagButtons.forEach((btn) => {
            const isAttached = attached.includes(btn.dataset.tagId);
            btn.classList.toggle('bg-brand-600', isAttached);
            btn.classList.toggle('text-white', isAttached);
            btn.classList.toggle('border-brand-600', isAttached);
            btn.classList.toggle('border-gray-300', !isAttached);
        });
    }

    function alertInline(message) {
        // Lightweight inline error, no native alert() boxes.
        newTagError.textContent = message;
        newTagError.classList.remove('hidden');
        setTimeout(() => newTagError.classList.add('hidden'), 4000);
    }

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            syncModalHighlights();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }

    modalTagButtons.forEach((btn) => {
        btn.addEventListener('click', async () => {
            const tagId = btn.dataset.tagId;
            const isAttached = attachedTagIds().includes(tagId);

            try {
                let data;
                if (isAttached) {
                    data = await window.appFetch(`/issues/${issueId}/tags/${tagId}`, { method: 'DELETE' });
                } else {
                    data = await window.appFetch(`/issues/${issueId}/tags`, {
                        method: 'POST',
                        body: { tag_id: tagId },
                    });
                }
                renderAttachedTags(data.tags);
            } catch (e) {
                alertInline('Could not update tag. Please try again.');
            }
        });
    });

    if (createTagBtn) {
        createTagBtn.addEventListener('click', async () => {
            const name = newTagNameInput.value.trim();
            newTagError.classList.add('hidden');

            if (!name) {
                alertInline('Tag name is required.');
                return;
            }

            try {
                const data = await window.appFetch('/tags', {
                    method: 'POST',
                    body: { name, color: newTagColorInput.value },
                });

                // Add the new tag button into the modal list immediately.
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'modal-tag-toggle badge border border-gray-300';
                btn.dataset.tagId = data.tag.id;
                btn.dataset.tagName = data.tag.name;
                btn.dataset.tagColor = data.tag.color;
                btn.textContent = data.tag.name;
                document.getElementById('modal-all-tags').appendChild(btn);
                btn.addEventListener('click', async () => {
                    const tagData = await window.appFetch(`/issues/${issueId}/tags`, {
                        method: 'POST',
                        body: { tag_id: data.tag.id },
                    });
                    renderAttachedTags(tagData.tags);
                });

                newTagNameInput.value = '';

                // Auto-attach the freshly created tag to this issue.
                const attachData = await window.appFetch(`/issues/${issueId}/tags`, {
                    method: 'POST',
                    body: { tag_id: data.tag.id },
                });
                renderAttachedTags(attachData.tags);
            } catch (e) {
                const message = e.data && e.data.errors && e.data.errors.name
                    ? e.data.errors.name[0]
                    : 'Could not create tag.';
                alertInline(message);
            }
        });
    }

    bindDetachButtons();
})();
