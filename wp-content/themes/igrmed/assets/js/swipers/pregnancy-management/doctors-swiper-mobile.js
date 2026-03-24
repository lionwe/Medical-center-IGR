/**
 * Мобільний слайдер лікарів — тільки Swiper (≤1023px).
 * Десктопна логіка в doctors-swiper.js, без Swiper.
 */
import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

export default class PregnancyDoctorsSwiperMobile {
    constructor() {
        this.wrapper = document.querySelector('.js-pregnancy-doctors-swiper');
        this.mobileBreakpoint = 1023;
        this.swiper = null;

        if (!this.wrapper) {
            return;
        }

        this.init();
        this.bindResize();
    }

    isMobile() {
        return window.innerWidth <= this.mobileBreakpoint;
    }

    bindResize() {
        let resizeTimer = null;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (this.isMobile() && !this.swiper) {
                    this.init();
                } else if (!this.isMobile() && this.swiper) {
                    this.swiper.destroy(true, true);
                    this.swiper = null;
                }
            }, 250);
        });
    }

    init() {
        if (!this.isMobile() || this.swiper) {
            return;
        }

        const forceCenter = (swiperInstance) => {
            requestAnimationFrame(() => {
                swiperInstance.update();
                swiperInstance.slideTo(swiperInstance.activeIndex, 0, false);
            });
        };

        this.swiper = new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: 1.5,
            centeredSlides: true,
            centerInsufficientSlides: true,
            spaceBetween: 10,
            speed: 450,
            grabCursor: true,
            navigation: {
                prevEl: '.js-pregnancy-doctors-prev',
                nextEl: '.js-pregnancy-doctors-next',
                disabledClass: 'swiper-button-disabled',
            },
            on: {
                init(swiperInstance) {
                    forceCenter(swiperInstance);
                },
                resize(swiperInstance) {
                    forceCenter(swiperInstance);
                },
            },
        });
    }
}
