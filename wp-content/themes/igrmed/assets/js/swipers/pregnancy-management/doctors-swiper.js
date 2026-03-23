/**
 * Десктопний слайдер лікарів (ведення вагітності).
 * Лише ручний translateX + кнопки — без Swiper.
 * На мобільних не ініціалізується (див. doctors-swiper-mobile.js).
 */
export default class PregnancyDoctorsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-pregnancy-doctors-swiper');
        this.desktopBreakpoint = 1024;
        this.inited = false;
        this.abortController = null;

        if (!this.wrapper) {
            return;
        }

        if (this.isDesktop()) {
            this.init();
        }

        this.bindResize();
    }

    isDesktop() {
        return window.innerWidth >= this.desktopBreakpoint;
    }

    destroy() {
        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }

        const track = this.wrapper?.querySelector('.swiper-wrapper');
        if (track) {
            track.style.transform = '';
            track.style.gap = '';
            track.style.display = '';
        }

        this.inited = false;
    }

    bindResize() {
        let resizeTimer;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (this.isDesktop() && !this.inited) {
                    this.init();
                } else if (!this.isDesktop() && this.inited) {
                    this.destroy();
                }
            }, 250);
        });
    }

    init() {
        if (!this.isDesktop() || this.inited) {
            return;
        }

        const track = this.wrapper.querySelector('.swiper-wrapper');
        const slides = Array.from(this.wrapper.querySelectorAll('.swiper-slide'));
        const prevBtn = document.querySelector('.js-pregnancy-doctors-prev');
        const nextBtn = document.querySelector('.js-pregnancy-doctors-next');

        if (!track || !slides.length || !prevBtn || !nextBtn) {
            return;
        }

        this.abortController = new AbortController();
        const { signal } = this.abortController;

        let currentIndex = 0;
        let cardWidthSmall = 253; // Відповідає convert-to-rem(253px) в CSS
        let gap = 24; // Відповідає spaceBetween в CSS

        const getActualSizes = () => {
            const firstSlide = slides[0];
            if (firstSlide && slides.length > 1) {
                const computedStyle = window.getComputedStyle(firstSlide);
                cardWidthSmall = parseFloat(computedStyle.width);

                gap = 24;
                track.style.gap = `${gap}px`;
                track.style.display = 'flex';

                console.log('Gap set to:', gap, 'px');
            }
        };

        const updateSlider = () => {
            slides.forEach((slide, index) => {
                if (index === currentIndex) {
                    slide.classList.add('swiper-slide-active');
                } else {
                    slide.classList.remove('swiper-slide-active');
                }
            });

            const shift = currentIndex * (cardWidthSmall + gap);
            track.style.transform = `translateX(-${shift}px)`;

            if (currentIndex === 0) {
                prevBtn.classList.add('swiper-button-disabled');
                prevBtn.disabled = true;
            } else {
                prevBtn.classList.remove('swiper-button-disabled');
                prevBtn.disabled = false;
            }

            if (currentIndex === slides.length - 1) {
                nextBtn.classList.add('swiper-button-disabled');
                nextBtn.disabled = true;
            } else {
                nextBtn.classList.remove('swiper-button-disabled');
                nextBtn.disabled = false;
            }
        };

        let resizeTimer;
        const handleResize = () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                getActualSizes();
                updateSlider();
            }, 250);
        };

        nextBtn.addEventListener(
            'click',
            (e) => {
                e.preventDefault();
                if (currentIndex < slides.length - 1) {
                    currentIndex++;
                    updateSlider();
                }
            },
            { signal },
        );

        prevBtn.addEventListener(
            'click',
            (e) => {
                e.preventDefault();
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            },
            { signal },
        );

        slides.forEach((slide, index) => {
            slide.addEventListener(
                'click',
                () => {
                    if (index !== currentIndex) {
                        currentIndex = index;
                        updateSlider();
                    }
                },
                { signal },
            );
        });

        window.addEventListener('resize', handleResize, { signal });

        getActualSizes();
        updateSlider();

        this.inited = true;
    }
}
