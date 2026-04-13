/**
 * Gynecology procedures collapse functionality
 */
const initGynecologyCollapse = () => {
    const root = document.querySelector('#gy-procedures');
    if (!root) return;

    const button = root.querySelector('.gynecology-content__list-more');
    if (!button) return;

    const textEl = button.querySelector('.gynecology-content__list-more-text');
    const moreLabel = textEl ? textEl.textContent : 'All procedures';
    const lessLabel = 'btn_close';

    button.addEventListener('click', () => {
        const hiddenItems = root.querySelectorAll('.is-hidden-service');
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        if (!isExpanded) {
            hiddenItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                item.classList.remove('is-hidden-service');
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });

            if (textEl) {
                textEl.textContent = lessLabel;
            }
            button.setAttribute('aria-expanded', 'true');
        } else {
            const allItems = root.querySelectorAll('.gynecology-content__procedures-item:not(.gynecology-content__list-more-item)');
            const visibleLimit = window.innerWidth <= 992 ? 5 : 11;
            
            allItems.forEach((item) => {
                item.style.opacity = '';
                item.style.transform = '';
                item.style.transition = '';
            });
            
            allItems.forEach((item, index) => {
                if (index >= visibleLimit) {
                    item.classList.add('is-hidden-service');
                } else {
                    item.classList.remove('is-hidden-service');
                }
            });

            if (textEl) {
                textEl.textContent = moreLabel;
            }
            button.setAttribute('aria-expanded', 'false');
            
            root.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
};

// Main initialization
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGynecologyCollapse);
} else {
    initGynecologyCollapse();
}

// Export for module usage
export { initGynecologyCollapse };
