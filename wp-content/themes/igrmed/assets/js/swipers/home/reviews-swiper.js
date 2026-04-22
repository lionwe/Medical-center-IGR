import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';

export default class ReviewsSwiper {
    constructor() {
        console.log('[DEBUG] ReviewsSwiper constructor called');
        this.wrapper = document.querySelector('.js-reviews-swiper');
        console.log('[DEBUG] Wrapper found:', !!this.wrapper);
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        console.log('[DEBUG] ReviewsSwiper init() called');
        console.log('[DEBUG] Swiper CSS check:', document.querySelector('link[href*="swiper"]') ? 'loaded' : 'NOT loaded');
        const swiper = new Swiper(this.wrapper, {
            modules: [Navigation, Autoplay],
            slidesPerView: 1,
            spaceBetween: 20,
            speed: 400,
            grabCursor: true,
            navigation: {
                prevEl: '.js-reviews-prev',
                nextEl: '.js-reviews-next',
                disabledClass: 'swiper-button-disabled',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
                pauseOnMouseEnter: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
            },
        });
        console.log('[DEBUG] Swiper instance created:', swiper);
        console.log('[DEBUG] Swiper slides count:', swiper.slides.length);
    }
}
