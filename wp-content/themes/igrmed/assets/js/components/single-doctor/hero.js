export default class DoctorTabs {
    constructor() {
        this.container = document.querySelector('.js-doctor-tabs');
        if (!this.container) return;

        this.tabs = this.container.querySelectorAll('.doctor-hero__tab');
        this.contents = this.container.querySelectorAll('.doctor-hero__tab-content');

        this.init();
    }

    init() {
        this.tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('data-tab');
                this.switchTab(target);
            });
        });
    }

    switchTab(tabId) {
        // Update tabs
        this.tabs.forEach(tab => {
            if (tab.getAttribute('data-tab') === tabId) {
                tab.classList.add('is-active');
            } else {
                tab.classList.remove('is-active');
            }
        });

        // Update contents
        this.contents.forEach(content => {
            if (content.getAttribute('data-content') === tabId) {
                content.classList.add('is-active');
            } else {
                content.classList.remove('is-active');
            }
        });
    }
}
