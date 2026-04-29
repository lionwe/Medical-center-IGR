import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

export default class DoctorsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-doctors-swiper');

        if (!this.wrapper) {
            return;
        }

        // Фікс #2: зберігаємо наявність dummy slides ДО будь-яких мутацій DOM,
        // щоб повторний init() після destroy() мав коректне значення.
        this._hasDummySlides = !!this.wrapper.querySelector('.doctors__slide--dummy');

        // Фікс #1: зберігаємо debounced-обробник як властивість,
        // щоб removeEventListener міг відв'язати саме його.
        this._resizeHandler = this._debounce(() => {
            this._handleResize();
        }, 100);

        this.init();
    }

    init() {
        this.isMobile = window.innerWidth < 768;
        this.nextBtn = document.querySelector('.js-doctors-next');

        this.offset = this._hasDummySlides && !this.isMobile ? 2 : 0;

        if (this.isMobile) {
            this.wrapper.querySelectorAll('.doctors__slide--dummy').forEach(slide => slide.remove());
        }

        try {
            this.swiper = new Swiper(this.wrapper, {
                modules: [Navigation],
                slidesPerView: 'auto',
                spaceBetween: 15,
                initialSlide: this.isMobile ? 1 : 2,
                navigation: {
                    nextEl: '.js-doctors-next',
                    prevEl: '.js-doctors-prev',
                    disabledClass: 'swiper-button-disabled',
                },
                breakpoints: {
                    0: {
                        centeredSlides: true,
                        slidesPerView: 1.6,
                    },
                    768: {
                        centeredSlides: false,
                        slidesPerView: 'auto',
                    },
                },
                on: {
                    init: (swiper) => this._updateCustomActiveSlide(swiper),
                    slideChange: (swiper) => this._updateCustomActiveSlide(swiper),
                },
            });
        } catch (error) {
            console.error('Failed to initialize DoctorsSwiper:', error);
            return;
        }

        window.addEventListener('resize', this._resizeHandler);

        // Додаємо ResizeObserver для кращої реакції на зміни розміру
        if ('ResizeObserver' in window) {
            this._resizeObserver = new ResizeObserver(() => {
                this._handleResize();
            });
            this._resizeObserver.observe(this.wrapper);
        }
    }

    _handleResize() {
        const newIsMobile = window.innerWidth < 768;
        // Якщо змінився mobile/desktop режим - перестворюємо слайдер
        if (newIsMobile !== this.isMobile) {
            this.destroy();
            this.init();
        } else if (this.swiper) {
            // Якщо режим той самий - просто оновлюємо слайдер
            this.swiper.update();
            this.swiper.updateSize();
            // Оновлюємо клас active слайда
            this._updateCustomActiveSlide(this.swiper);
        }
    }

    _updateCustomActiveSlide(swiper) {
        if (!swiper?.slides) return;

        // Оновлюємо активний слайд
        swiper.slides.forEach(slide => slide.classList.remove('custom-active'));
        const targetIndex = swiper.activeIndex + this.offset;
        if (swiper.slides[targetIndex]) {
            swiper.slides[targetIndex].classList.add('custom-active');
        }

        // Динамічно рахуємо кількість реальних слайдів
        const realSlides = swiper.slides.filter(slide => !slide.classList.contains('doctors__slide--dummy'));
        const realSlidesCount = realSlides.length;
        const maxIndex = realSlidesCount - 1;

        const isAtEnd = swiper.activeIndex >= maxIndex;
        swiper.allowSlideNext = !isAtEnd;
        this.nextBtn?.classList.toggle('swiper-button-disabled', isAtEnd);
    }

    destroy() {
        if (this.swiper) {
            this.swiper.destroy(true, true);
            this.swiper = null;
        }

        window.removeEventListener('resize', this._resizeHandler);

        if (this._resizeObserver) {
            this._resizeObserver.disconnect();
            this._resizeObserver = null;
        }
    }

    _debounce(func, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }
}