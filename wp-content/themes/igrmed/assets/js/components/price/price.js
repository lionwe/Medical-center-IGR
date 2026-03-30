/**
 * Price Page JavaScript - Filtering and Accordion Logic
 */
import { load } from "../../events/load";

class PriceList {
    constructor() {
        this.container = document.querySelector('.js-price-category');
        if (!this.container) return;

        this.init();
    }

    init() {
        this.trigger = this.container.querySelector('.js-price-category-trigger');
        this.label = this.container.querySelector('.price-list__category-label');
        this.dropdownLinks = document.querySelectorAll('.price-list__dropdown-link');
        this.categoryGroups = document.querySelectorAll('.price-list__category-group');
        this.searchInput = document.querySelector('.price-list__search-input');
        this.emptyMessage = document.querySelector('.js-price-empty');

        this.initCategoryDropdown();
        this.initAccordions();
        this.initFilters();

        this.openFirstVisible();
    }

    openFirstVisible() {
        const firstVisibleAcc = document.querySelector('.js-price-accordion:not([style*="display: none"])');
        if (firstVisibleAcc && !firstVisibleAcc.classList.contains('is-open')) {
            const content = firstVisibleAcc.querySelector('.js-price-accordion-accordeon');
            if (content) {
                this.toggleAccordion(firstVisibleAcc, content, true);
            }
        }
    }

    initCategoryDropdown() {
        if (!this.trigger) return;

        const CLOSE_DELAY_MS = 800;
        let closeTimerId = null;
        const clearCloseTimer = () => {
            if (closeTimerId) {
                window.clearTimeout(closeTimerId);
                closeTimerId = null;
            }
        };
        const scheduleClose = () => {
            clearCloseTimer();
            closeTimerId = window.setTimeout(() => {
                this.container.classList.remove('is-open');
                this.trigger.setAttribute('aria-expanded', 'false');
                closeTimerId = null;
            }, CLOSE_DELAY_MS);
        };
        
        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            clearCloseTimer();
            const isOpen = this.container.classList.toggle('is-open');
            this.trigger.setAttribute('aria-expanded', isOpen);
        });

        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.container.classList.remove('is-open');
                this.trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Hover delay close (desktop): prevents flicker when moving into dropdown.
        this.container.addEventListener('mouseenter', clearCloseTimer);
        this.container.addEventListener('mouseleave', () => {
            if (this.container.classList.contains('is-open')) {
                scheduleClose();
            }
        });

        this.dropdownLinks.forEach(link => {
            link.addEventListener('click', () => {
                const catId = link.getAttribute('data-category-id');
                const catName = link.innerText;

                this.container.setAttribute('data-selected-category', catId);
                this.label.innerText = catName;
                this.container.classList.remove('is-open');
                this.trigger.setAttribute('aria-expanded', 'false');
                clearCloseTimer();

                this.filterPriceList();
            });
        });
    }

    initAccordions() {
        const accordions = document.querySelectorAll('.js-price-accordion');
        accordions.forEach(acc => {
            const trigger = acc.querySelector('.js-price-accordion-trigger');
            const content = acc.querySelector('.js-price-accordion-accordeon');

            if (trigger && content) {
                trigger.addEventListener('click', () => {
                    const isOpen = acc.classList.contains('is-open');
                    this.toggleAccordion(acc, content, !isOpen);
                    trigger.setAttribute('aria-expanded', !isOpen);
                });
            }
        });
    }

    toggleAccordion(acc, content, show) {
        if (!content) return;

        if (show) {
            acc.classList.add('is-open');
            content.style.display = 'block';
            content.setAttribute('open', ''); // For grid compatibility
            const height = content.scrollHeight;
            content.style.height = '0';
            content.style.overflow = 'hidden';
            content.style.transition = 'height 0.3s ease';

            // Force repaint
            content.offsetHeight;

            content.style.height = height + 'px';
            setTimeout(() => {
                content.style.height = '';
                content.style.overflow = '';
            }, 300);
        } else {
            const height = content.scrollHeight;
            content.style.height = height + 'px';
            content.style.overflow = 'hidden';
            content.style.transition = 'height 0.3s ease';

            // Force repaint
            content.offsetHeight;

            content.style.height = '0';
            setTimeout(() => {
                acc.classList.remove('is-open');
                content.removeAttribute('open'); // For grid compatibility
                content.style.display = 'none';
                content.style.height = '';
                content.style.overflow = '';
            }, 300);
        }
    }

    initFilters() {
        if (this.searchInput) {
            this.searchContainer = this.searchInput.closest('.price-list__search');
            this.searchTimeout = null;

            this.searchInput.addEventListener('input', () => {
                if (this.searchContainer) {
                    this.searchContainer.classList.add('is-loading');
                }

                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.filterPriceList();
                    if (this.searchContainer) {
                        this.searchContainer.classList.remove('is-loading');
                    }
                }, 400);
            });
        }
    }

    filterPriceList() {
        const selectedId = this.container.getAttribute('data-selected-category') || 'all';
        const searchTerm = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';

        let totalVisible = 0;

        this.categoryGroups.forEach(group => {
            const groupId = group.getAttribute('data-category-id');
            const matchesCategory = (selectedId === 'all' || selectedId === groupId);

            let matchesSearch = false;
            const directionAccordions = group.querySelectorAll('.price-list__direction-accordion');

            directionAccordions.forEach(acc => {
                const directionName = acc.querySelector('.price-list__direction-name').innerText.toLowerCase();
                const itemContainers = acc.querySelectorAll('.price-list__item-container');
                const content = acc.querySelector('.js-price-accordion-accordeon');
                let accordionHasMatch = directionName.includes(searchTerm);

                itemContainers.forEach(container => {
                    const itemName = container.querySelector('.price-list__item-name').innerText.toLowerCase();
                    if (itemName.includes(searchTerm)) {
                        container.style.display = 'flex';
                        accordionHasMatch = true;
                    } else if (!directionName.includes(searchTerm)) {
                        container.style.display = 'none';
                    } else {
                        container.style.display = 'flex';
                    }
                });

                if (accordionHasMatch) {
                    acc.style.display = '';
                    matchesSearch = true;
                    if (searchTerm.length > 0 && !acc.classList.contains('is-open')) {
                        this.toggleAccordion(acc, content, true);
                    } else if (searchTerm.length === 0 && acc.classList.contains('is-open')) {
                        // Optional: Reset state when search is cleared
                    }
                } else {
                    acc.style.display = 'none';
                    if (acc.classList.contains('is-open')) {
                        this.toggleAccordion(acc, content, false);
                    }
                }
            });

            if (matchesCategory && matchesSearch) {
                group.style.display = '';
                totalVisible++;
            } else {
                group.style.display = 'none';
            }
        });

        if (this.emptyMessage) {
            this.emptyMessage.style.display = totalVisible === 0 ? 'block' : 'none';
        }

        if (searchTerm === '' && totalVisible > 0) {
            const anyOpen = document.querySelector('.js-price-accordion.is-open:not([style*="display: none"])');
            if (!anyOpen) {
                this.openFirstVisible();
            }
        }
    }

    static init() {
        new PriceList();
    }
}

// Use our robust loader instead of raw DOMContentLoaded
load(() => {
    PriceList.init();
});

export default PriceList;
