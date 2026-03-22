export default class SurrogateTabs {
    constructor() {
        console.log('SurrogateTabs initialized');
        this.container = document.querySelector('.surrogate-motherhood-content__section--tabs');
        if (!this.container) {
            console.log('No tabs container found');
            return;
        }

        this.tabs = this.container.querySelectorAll('.surrogate-motherhood-content__tab-btn');
        this.panels = this.container.querySelectorAll('.surrogate-motherhood-content__tab-panel');
        this.ajaxParams = window.params || {};
        this.ajaxUrl = this.getAjaxUrl();
        this.postId = this.container.dataset.postId || '';

        console.log('Tabs found:', this.tabs.length);
        console.log('Post ID:', this.postId);
        console.log('Ajax URL:', this.ajaxUrl);

        this.init();
    }

    getAjaxUrl() {
        return (typeof this.ajaxParams.ajax_url === "string" && this.ajaxParams.ajax_url.trim() !== "")
            ? this.ajaxParams.ajax_url
            : "/wp-admin/admin-ajax.php";
    }

    init() {
        console.log('Initializing tabs...');
        this.tabs.forEach((tab, index) => {
            console.log(`Binding tab ${index}:`, tab.getAttribute('data-tab'));
            tab.addEventListener('click', async () => {
                const target = tab.getAttribute('data-tab');
                console.log('Tab clicked:', target);
                await this.switchTab(target);
            });
        });
    }

    async switchTab(tabId) {
        // Update tabs UI
        this.tabs.forEach(tab => {
            if (tab.getAttribute('data-tab') === tabId) {
                tab.classList.add('is-active');
            } else {
                tab.classList.remove('is-active');
            }
        });

        // Find target panel
        const targetPanel = this.container.querySelector(`[data-panel="${tabId}"]`);
        if (!targetPanel) return;

        // Update panels UI
        this.panels.forEach(panel => {
            if (panel.getAttribute('data-panel') === tabId) {
                panel.hidden = false;
                panel.classList.add('is-active');
            } else {
                panel.hidden = true;
                panel.classList.remove('is-active');
            }
        });

        // Load content via AJAX if not loaded yet
        if (targetPanel.dataset.loaded !== 'true') {
            await this.loadTabContent(tabId, targetPanel);
        }
    }

    async loadTabContent(tabType, panel) {
        console.log('Loading tab content:', tabType);
        if (!this.postId || panel.dataset.loaded === 'true') return;

        const status = panel.querySelector('.surrogate-motherhood-content__tab-status');
        const loadingText = this.container.dataset.loadingLabel || 'Завантаження...';
        const errorText = this.container.dataset.errorLabel || 'Не вдалося завантажити дані. Спробуйте ще раз.';

        console.log('Post ID:', this.postId);
        console.log('Nonce:', this.ajaxParams.nonce ? 'exists' : 'missing');
        console.log('Ajax URL:', this.ajaxUrl);

        this.container.classList.add('is-loading');
        if (status) {
            status.textContent = loadingText;
        }

        try {
            const body = new URLSearchParams();
            body.append('action', 'igrmed_load_surrogate_tabs');
            body.append('nonce', this.ajaxParams.nonce || '');
            body.append('post_id', this.postId);
            body.append('tab_type', tabType);

            console.log('Request body:', body.toString());

            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                },
                body: body.toString(),
            });

            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (!data?.success || !data?.data?.html) {
                throw new Error('Invalid response structure');
            }

            panel.innerHTML = data.data.html;
            panel.dataset.loaded = 'true';
        } catch (error) {
            console.error('Failed to load tab content:', error);
            if (status) {
                status.textContent = errorText;
            } else {
                panel.innerHTML = `<div class="surrogate-motherhood-content__tab-status">${errorText}</div>`;
            }
            panel.dataset.loaded = 'error';
        } finally {
            this.container.classList.remove('is-loading');
        }
    }
}
