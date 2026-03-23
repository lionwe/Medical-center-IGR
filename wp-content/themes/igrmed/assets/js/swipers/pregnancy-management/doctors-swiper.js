export default class PregnancyDoctorsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-pregnancy-doctors-swiper');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        const track = this.wrapper.querySelector('.swiper-wrapper');
        const slides = Array.from(this.wrapper.querySelectorAll('.swiper-slide'));
        const prevBtn = document.querySelector('.js-pregnancy-doctors-prev');
        const nextBtn = document.querySelector('.js-pregnancy-doctors-next');

        if (!track || !slides.length || !prevBtn || !nextBtn) {
            return;
        }

        let currentIndex = 0;
        let cardWidthSmall = 213; // Відповідає convert-to-rem(213px) в CSS
        let gap = 24; // Відповідає spaceBetween в CSS

        // Функція для отримання актуальних розмірів
        function getActualSizes() {
            const firstSlide = slides[0];
            if (firstSlide && slides.length > 1) {
                // Отримуємо ширину першого слайда
                const computedStyle = window.getComputedStyle(firstSlide);
                cardWidthSmall = parseFloat(computedStyle.width);
                
                // Примусово встановлюємо gap для track
                gap = 24;
                track.style.gap = `${gap}px`;
                track.style.display = 'flex';
                
                console.log('Gap set to:', gap, 'px');
            }
        }

        function updateSlider() {
            // Оновлюємо активний стан слайдів
            slides.forEach((slide, index) => {
                if (index === currentIndex) {
                    slide.classList.add('swiper-slide-active');
                } else {
                    slide.classList.remove('swiper-slide-active');
                }
            });

            // Зсуваємо трек вліво з актуальними розмірами
            const shift = currentIndex * (cardWidthSmall + gap);
            track.style.transform = `translateX(-${shift}px)`;

            // Оновлюємо стан кнопок
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
        }

        // Обробник ресайзу з debounce для оптимізації
        let resizeTimer;
        function handleResize() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                getActualSizes();
                updateSlider();
            }, 250);
        }

        // Клік "Вперед"
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentIndex < slides.length - 1) {
                currentIndex++;
                updateSlider();
            }
        });

        // Клік "Назад"
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });

        // Клік по неактивному слайду робить його активним
        slides.forEach((slide, index) => {
            slide.addEventListener('click', () => {
                if (index !== currentIndex) {
                    currentIndex = index;
                    updateSlider();
                }
            });
        });

        // Ініціалізація
        getActualSizes();
        updateSlider();

        // Обробка зміни розміру вікна
        window.addEventListener('resize', handleResize);
    }
}