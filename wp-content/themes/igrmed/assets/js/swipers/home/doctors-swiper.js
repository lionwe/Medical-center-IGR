import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

export default class DoctorsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-doctors-swiper');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        const forceCenter = (swiper) => {
            requestAnimationFrame(() => {
                swiper.update();
                swiper.slideTo(swiper.activeIndex, 0, false);
            });
        };

        new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: 'auto',
            centeredSlides: true,
            centerInsufficientSlides: true,
            spaceBetween: 16,
            initialSlide: 2,
            speed: 450,
            grabCursor: true,
            navigation: {
                prevEl: '.js-doctors-prev',
                nextEl: '.js-doctors-next',
                disabledClass: 'swiper-button-disabled',
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.5,
                    centeredSlides: true,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 'auto',
                    centeredSlides: true,
                    spaceBetween: 16,
                },
            },
            on: {
                init(swiper) {
                    forceCenter(swiper);
                },
                resize(swiper) {
                    forceCenter(swiper);
                },
            },
        });
    }
}
