/**
 * Price Page JavaScript
 */
const initPricePage = () => {
    const categorySelect = document.querySelector('.js-price-category');
    const trigger = document.querySelector('.js-price-category-trigger');
    
    if (!categorySelect || !trigger) return;

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        categorySelect.classList.toggle('is-open');
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!categorySelect.contains(e.target)) {
            categorySelect.classList.remove('is-open');
        }
    });

    console.log('Price page filters initialized');
};

document.addEventListener('DOMContentLoaded', initPricePage);

export default initPricePage;
