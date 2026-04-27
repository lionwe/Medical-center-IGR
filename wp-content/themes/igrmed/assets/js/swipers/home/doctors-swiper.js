import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

export default class DoctorsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-doctors-swiper');
        if (this.wrapper) {
            this.init();
        }
    }

    updateCustomActiveSlide(swiper) {
        // 1. Оновлюємо класи
        swiper.slides.forEach(slide => {
            slide.classList.remove('custom-active');
        });

        const targetIndex = swiper.activeIndex + 2;

        if (swiper.slides[targetIndex]) {
            swiper.slides[targetIndex].classList.add('custom-active');
        }

        // 2. Блокуємо скрол вперед, якщо дійшли до останнього реального слайду
        const realSlidesCount = swiper.slides.filter(slide => !slide.classList.contains('doctors__slide--dummy')).length;
        const maxIndex = realSlidesCount - 1;
        const nextBtn = document.querySelector('.js-doctors-next');

        if (swiper.activeIndex >= maxIndex) {
            swiper.allowSlideNext = false;
            nextBtn.classList.add('swiper-button-disabled');
        } else {
            swiper.allowSlideNext = true;
            nextBtn.classList.remove('swiper-button-disabled');
        }

        setTimeout(() => {
            swiper.updateSize();
        }, 400);
    }

    init() {
        new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: 'auto',
            spaceBetween: 15,
            initialSlide: 2,
            navigation: {
                nextEl: '.js-doctors-next',
                prevEl: '.js-doctors-prev',
                disabledClass: 'swiper-button-disabled',
            },
            on: {
                init: (swiper) => {
                    this.updateCustomActiveSlide(swiper);
                },
                slideChange: (swiper) => {
                    this.updateCustomActiveSlide(swiper);
                }
            }
        });
    }
}
