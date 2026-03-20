import { clickOn } from "../../events/click";

/**
 * Handle AJAX Load More and Collapse for Blog Archive
 */
const initBlogLoadMore = () => {

    clickOn('.js-load-more-blog', async (event) => {
        event.preventDefault();
        event.stopPropagation();

        const button = event.target.closest('.js-load-more-blog');
        if (!button || button.classList.contains('loading')) return;

        const grid = document.querySelector('.js-blog-grid');
        const textElement = button.querySelector('.btn__text');
        if (!grid || !textElement) return;

        const currentPage = parseInt(button.getAttribute('data-paged')) || 1;
        const maxPages = parseInt(button.getAttribute('data-max')) || 1;
        const nonce = button.getAttribute('data-nonce');

        const textLoad = button.getAttribute('data-text-load') || 'Читати більше';
        const textCollapse = button.getAttribute('data-text-collapse') || 'Згорнути';

        // Collapse/Expand mode (all content loaded)
        if (button.classList.contains('is-all-loaded')) {
            const ajaxItems = grid.querySelectorAll('.js-ajax-item');

            if (button.classList.contains('is-collapsed')) {
                // Expand with animation
                ajaxItems.forEach((item, index) => {
                    item.style.display = '';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, index * 100);
                });
                textElement.innerText = textCollapse;
                button.classList.remove('is-collapsed');
            } else {
                // Collapse
                ajaxItems.forEach(item => item.style.display = 'none');
                textElement.innerText = textLoad;
                button.classList.add('is-collapsed');

                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            return;
        }

        // AJAX loading mode
        button.classList.add('loading');
        const originalBtnText = textElement.innerText;
        textElement.innerText = 'Завантаження...';

        const nextPage = currentPage + 1;
        const formData = new FormData();
        formData.append('action', 'load_blog_posts');
        formData.append('nonce', nonce);
        formData.append('paged', nextPage);

        try {
            const response = await fetch(window.params.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success && data.data.html) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.data.html;

                // Get only valid children from tempDiv
                const items = Array.from(tempDiv.children);
                items.forEach((item, index) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'blog-archive-list__item js-ajax-item';
                    wrapper.style.opacity = '0';
                    wrapper.style.transform = 'translateY(20px)';
                    wrapper.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    wrapper.appendChild(item);
                    grid.appendChild(wrapper);

                    // Trigger animation with delay
                    setTimeout(() => {
                        wrapper.style.opacity = '1';
                        wrapper.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                button.setAttribute('data-paged', nextPage);

                // Check if all pages loaded
                if (nextPage >= maxPages) {
                    button.classList.add('is-all-loaded');
                    textElement.innerText = textCollapse;
                } else {
                    textElement.innerText = textLoad;
                }
            } else {
                console.error('AJAX Load error: No posts or success false', data);
                textElement.innerText = textLoad;
            }
        } catch (error) {
            console.error('AJAX Load error:', error);
            textElement.innerText = textLoad;
        } finally {
            button.classList.remove('loading');
        }
    });
};

document.addEventListener('DOMContentLoaded', initBlogLoadMore);

export default initBlogLoadMore;
