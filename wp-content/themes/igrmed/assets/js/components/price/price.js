/**
 * Price Page JavaScript - Filtering and Accordion Logic
 */
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

        this.initCategoryDropdown();
        this.initAccordions();
        this.initFilters();

        this.openFirstVisible();
    }

    openFirstVisible() {
        const firstAcc = document.querySelector('.js-price-accordion');
        if (firstAcc && !firstAcc.classList.contains('is-open')) {
            const content = firstAcc.querySelector('.js-price-accordion-accordeon');
            if (content) {
                this.toggleAccordion(firstAcc, content, true);
            }
        }
    }

    initCategoryDropdown() {
        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            this.container.classList.toggle('is-open');
        });

        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.container.classList.remove('is-open');
            }
        });

        this.dropdownLinks.forEach(link => {
            link.addEventListener('click', () => {
                const catId = link.getAttribute('data-category-id');
                const catName = link.innerText;

                this.container.setAttribute('data-selected-category', catId);
                this.label.innerText = catName;
                this.container.classList.remove('is-open');

                this.filterPriceList();
            });
        });
    }

    initAccordions() {
        const accordions = document.querySelectorAll('.js-price-accordion');
        accordions.forEach(acc => {
            const trigger = acc.querySelector('.js-price-accordion-trigger');
            const content = acc.querySelector('.js-price-accordion-accordeon');

            trigger.addEventListener('click', () => {
                const isOpen = acc.classList.contains('is-open');
                this.toggleAccordion(acc, content, !isOpen);
            });
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
            this.searchInput.addEventListener('input', () => this.filterPriceList());
        }
    }

    filterPriceList() {
        const selectedId = this.container.getAttribute('data-selected-category') || 'all';
        const searchTerm = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';

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
            } else {
                group.style.display = 'none';
            }
        });
    }

    static init() {
        new PriceList();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    PriceList.init();
});

export default PriceList;
