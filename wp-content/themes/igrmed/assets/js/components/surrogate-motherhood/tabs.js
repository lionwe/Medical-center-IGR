export default class SurrogateTabs {
    constructor() {
        this.container = document.querySelector('.surrogate-motherhood-content__section--tabs');
        if (!this.container) return;

        // Desktop elements (flat nav + panels)
        this.tabs        = this.container.querySelectorAll('.surrogate-motherhood-content__tabs .surrogate-motherhood-content__tab-btn');
        this.panels      = this.container.querySelectorAll('.surrogate-motherhood-content__tabs .surrogate-motherhood-content__tab-panel');

        // Mobile elements (accordion items — each wraps its own btn + panel)
        this.accordionItems = this.container.querySelectorAll('.surrogate-motherhood-content__accordion-item');

        this.ajaxParams = window.params || {};
        this.ajaxUrl    = this.getAjaxUrl();
        this.postId     = this.container.dataset.postId || '';

        this.init();
    }

    getAjaxUrl() {
        return (typeof this.ajaxParams.ajax_url === 'string' && this.ajaxParams.ajax_url.trim() !== '')
            ? this.ajaxParams.ajax_url
            : '/wp-admin/admin-ajax.php';
    }

    init() {
        // Desktop tab clicks
        this.tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                this.switchDesktopTab(tab.dataset.tab);
            });
        });

        // Mobile accordion clicks
        this.accordionItems.forEach(item => {
            const btn   = item.querySelector('.surrogate-motherhood-content__tab-btn');
            const panel = item.querySelector('.surrogate-motherhood-content__tab-panel');
            if (!btn || !panel) return;

            btn.addEventListener('click', () => {
                this.toggleAccordionItem(btn, panel);
            });
        });

        window.addEventListener('resize', this.debounce(() => {
            if (!this.isMobile()) {
                // Ensure at least the first desktop tab is active when returning to desktop
                const hasActive = Array.from(this.tabs).some(t => t.classList.contains('is-active'));
                if (!hasActive && this.tabs.length > 0) {
                    this.switchDesktopTab(this.tabs[0].dataset.tab);
                }
            }
        }, 250));
    }

    // ─── Desktop ────────────────────────────────────────────────────────────────

    switchDesktopTab(tabId) {
        this.tabs.forEach(tab => tab.classList.toggle('is-active', tab.dataset.tab === tabId));

        this.panels.forEach(panel => {
            const isTarget = panel.dataset.panel === tabId;
            panel.hidden = !isTarget;
            panel.classList.toggle('is-active', isTarget);

            if (isTarget && panel.dataset.loaded !== 'true') {
                this.loadTabContent(tabId, panel);
            }
        });
    }

    // ─── Mobile accordion ────────────────────────────────────────────────────────

    toggleAccordionItem(btn, panel) {
        const tabId   = btn.dataset.tab;
        const isOpen  = btn.classList.contains('is-active');

        // Toggle current item only (don't close others on mobile)
        if (!isOpen) {
            btn.classList.add('is-active');
            panel.hidden = false;
            panel.classList.add('is-active');

            if (panel.dataset.loaded !== 'true') {
                this.loadTabContent(tabId, panel);
            }
        } else {
            btn.classList.remove('is-active');
            panel.hidden = true;
            panel.classList.remove('is-active');
        }
    }

    // ─── Shared AJAX ────────────────────────────────────────────────────────────

    async loadTabContent(tabType, panel) {
        if (!this.postId || panel.dataset.loaded === 'true') return;

        const status      = panel.querySelector('.surrogate-motherhood-content__tab-status');
        const loadingText = this.container.dataset.loadingLabel || 'Завантаження...';
        const errorText   = this.container.dataset.errorLabel   || 'Не вдалося завантажити дані. Спробуйте ще раз.';

        this.container.classList.add('is-loading');
        if (status) status.textContent = loadingText;

        try {
            const body = new URLSearchParams();
            body.append('action',   'igrmed_load_surrogate_tabs');
            body.append('nonce',    this.ajaxParams.nonce || '');
            body.append('post_id',  this.postId);
            body.append('tab_type', tabType);

            const response = await fetch(this.ajaxUrl, {
                method:      'POST',
                credentials: 'same-origin',
                headers:     { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body:        body.toString(),
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            if (!data?.success || !data?.data?.html) throw new Error('Invalid response structure');

            panel.innerHTML      = data.data.html;
            panel.dataset.loaded = 'true';

            // If the same tab exists in the other DOM tree (desktop/mobile mirror),
            // sync its loaded state and content too so AJAX fires only once.
            const mirrorPanel = this.getMirrorPanel(panel, tabType);
            if (mirrorPanel && mirrorPanel.dataset.loaded !== 'true') {
                mirrorPanel.innerHTML      = data.data.html;
                mirrorPanel.dataset.loaded = 'true';
            }
        } catch (error) {
            console.error('Failed to load tab content:', error);
            const errHtml = `<div class="surrogate-motherhood-content__tab-status">${errorText}</div>`;
            panel.innerHTML      = errHtml;
            panel.dataset.loaded = 'error';
        } finally {
            this.container.classList.remove('is-loading');
        }
    }

    /**
     * Returns the matching panel in the other DOM tree (desktop ↔ mobile).
     * Avoids double AJAX requests when both trees share the same tab IDs.
     */
    getMirrorPanel(currentPanel, tabId) {
        const allPanels = this.container.querySelectorAll(`.surrogate-motherhood-content__tab-panel[data-panel="${tabId}"]`);
        return Array.from(allPanels).find(p => p !== currentPanel) || null;
    }

    // ─── Utils ───────────────────────────────────────────────────────────────────

    isMobile() {
        return window.matchMedia('(max-width: 768px)').matches;
    }

    debounce(fn, delay) {
        let id;
        return (...args) => { clearTimeout(id); id = setTimeout(() => fn.apply(this, args), delay); };
    }
}