import { clickOn } from "../../events/click";

/**
 * Handle AJAX Load More and Collapse for Blog Archive with Prefetching
 */
const initBlogLoadMore = () => {
    const button = document.querySelector('.js-load-more-blog');
    if (!button) return;

    const grid = document.querySelector('.js-blog-grid');
    if (!grid) return;

    let prefetchData = null;
    let isPrefetching = false;

    /**
     * Fetch posts from server
     */
    const fetchPosts = async (page) => {
        const nonce = button.getAttribute('data-nonce');
        const formData = new FormData();
        formData.append('action', 'load_blog_posts');
        formData.append('nonce', nonce);
        formData.append('paged', page);

        try {
            const response = await fetch(window.params.ajax_url, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('AJAX Fetch error:', error);
            return null;
        }
    };

    /**
     * Prefetch the next page
     */
    const prefetchNextPage = async () => {
        const currentPage = parseInt(button.getAttribute('data-paged')) || 1;
        const maxPages = parseInt(button.getAttribute('data-max')) || 1;
        const nextPage = currentPage + 1;

        if (nextPage > maxPages || prefetchData || isPrefetching || button.classList.contains('is-all-loaded')) return;

        isPrefetching = true;
        prefetchData = await fetchPosts(nextPage);
        isPrefetching = false;
    };

    /**
     * Render items into the grid
     */
    const renderItems = (html) => {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const items = Array.from(tempDiv.children);

        items.forEach((item, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'blog-archive-list__item js-ajax-item';
            wrapper.style.opacity = '0';
            wrapper.style.transform = 'translateY(20px)';
            wrapper.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            wrapper.appendChild(item);
            grid.appendChild(wrapper);

            setTimeout(() => {
                wrapper.style.opacity = '1';
                wrapper.style.transform = 'translateY(0)';
            }, index * 100);
        });
    };

    // Prefetch on hover
    button.addEventListener('mouseenter', prefetchNextPage, { once: true });

    // Prefetch when scrolling near the button
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            prefetchNextPage();
        }
    }, { rootMargin: '400px' });
    observer.observe(button);

    clickOn('.js-load-more-blog', async (event) => {
        event.preventDefault();
        
        if (button.classList.contains('loading')) return;

        const textElement = button.querySelector('.btn__text');
        const textLoad = button.getAttribute('data-text-load') || 'Читати більше';
        const textCollapse = button.getAttribute('data-text-collapse') || 'Згорнути';

        // Collapse/Expand mode
        if (button.classList.contains('is-all-loaded')) {
            const ajaxItems = grid.querySelectorAll('.js-ajax-item');
            if (button.classList.contains('is-collapsed')) {
                ajaxItems.forEach((item, index) => {
                    // Reset to initial animation state before showing
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    item.style.display = '';
                    
                    // Trigger animation with a small delay for each item
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, index * 40);
                });
                textElement.innerText = textCollapse;
                button.classList.remove('is-collapsed');
            } else {
                ajaxItems.forEach(item => {
                    item.style.display = 'none';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                });
                textElement.innerText = textLoad;
                button.classList.add('is-collapsed');
                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            return;
        }

        button.classList.add('loading');
        const currentPage = parseInt(button.getAttribute('data-paged')) || 1;
        const nextPage = currentPage + 1;
        const maxPages = parseInt(button.getAttribute('data-max')) || 1;

        let data = prefetchData;
        prefetchData = null; // Clear prefetch cache

        if (!data) {
            textElement.innerText = 'Завантаження...';
            data = await fetchPosts(nextPage);
        }

        if (data && data.success && data.data.html) {
            renderItems(data.data.html);
            button.setAttribute('data-paged', nextPage);

            if (nextPage >= maxPages) {
                button.classList.add('is-all-loaded');
                textElement.innerText = textCollapse;
            } else {
                textElement.innerText = textLoad;
                // Prefetch subsequent page after current one is rendered
                prefetchNextPage();
            }
        } else {
            textElement.innerText = textLoad;
        }

        button.classList.remove('loading');
    });
};

document.addEventListener('DOMContentLoaded', initBlogLoadMore);

export default initBlogLoadMore;
