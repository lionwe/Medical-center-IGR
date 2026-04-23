import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';

export default class ReviewsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-reviews-swiper');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
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
