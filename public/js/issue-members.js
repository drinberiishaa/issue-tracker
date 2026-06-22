/**
 * Issue detail page (bonus feature): assign/unassign members (users) via the
 * "Manage members" modal, plus inline removal via the x button on each pill.
 */
(function () {
    const membersList = document.getElementById('issue-members-list');
    if (!membersList) return;

    const issueId = membersList.dataset.issueId;
    const modal = document.getElementById('member-modal');
    const openBtn = document.getElementById('open-member-modal');
    const closeBtn = document.getElementById('close-member-modal');
    const modalMemberButtons = document.querySelectorAll('.modal-member-toggle');

    function assignedUserIds() {
        return Array.from(membersList.querySelectorAll('[data-user-id]')).map((el) => el.dataset.userId);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderMembers(members) {
        membersList.innerHTML = '';
        if (members.length === 0) {
            membersList.innerHTML = '<p class="text-sm text-gray-400" id="no-members-msg">No members assigned.</p>';
        }
        members.forEach((member) => {
            const span = document.createElement('span');
            span.className = 'badge bg-indigo-50 text-indigo-700 member-pill-removable';
            span.dataset.userId = member.id;
            span.innerHTML = `${escapeHtml(member.name)} <button type="button" class="detach-member-btn ml-1.5 hover:opacity-60" data-user-id="${member.id}" title="Unassign">&times;</button>`;
            membersList.appendChild(span);
        });
        bindDetachButtons();
        syncModalHighlights();
    }

    function bindDetachButtons() {
        membersList.querySelectorAll('.detach-member-btn').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const userId = btn.dataset.userId;
                try {
                    const data = await window.appFetch(`/issues/${issueId}/members/${userId}`, { method: 'DELETE' });
                    renderMembers(data.members);
                } catch (e) {
                    // Fail quietly inline; list simply stays as-is.
                }
            });
        });
    }

    function syncModalHighlights() {
        const assigned = assignedUserIds();
        modalMemberButtons.forEach((btn) => {
            const isAssigned = assigned.includes(btn.dataset.userId);
            btn.classList.toggle('bg-brand-600', isAssigned);
            btn.classList.toggle('text-white', isAssigned);
            btn.classList.toggle('border-brand-600', isAssigned);
            btn.classList.toggle('border-gray-300', !isAssigned);
        });
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

    modalMemberButtons.forEach((btn) => {
        btn.addEventListener('click', async () => {
            const userId = btn.dataset.userId;
            const isAssigned = assignedUserIds().includes(userId);

            try {
                let data;
                if (isAssigned) {
                    data = await window.appFetch(`/issues/${issueId}/members/${userId}`, { method: 'DELETE' });
                } else {
                    data = await window.appFetch(`/issues/${issueId}/members`, {
                        method: 'POST',
                        body: { user_id: userId },
                    });
                }
                renderMembers(data.members);
            } catch (e) {
                // Fail quietly; modal highlight stays as-is.
            }
        });
    });

    bindDetachButtons();
})();
