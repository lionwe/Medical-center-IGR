import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';

const MOBILE_QUERY = '(max-width: 768px)';
const heroSwiperElement = document.querySelector('.js-hero-swiper');

if (heroSwiperElement) {
    const mediaQuery = window.matchMedia(MOBILE_QUERY);
    const paginationElement = heroSwiperElement.querySelector('.hero__cards-pagination');
    let heroSwiper = null;

    const initHeroSwiper = () => {
        if (heroSwiper) {
            return;
        }

        heroSwiperElement.classList.add('swiper');

        heroSwiper = new Swiper(heroSwiperElement, {
            modules: [Pagination],
            slidesPerView: 1,
            spaceBetween: 12,
            pagination: {
                el: paginationElement,
                clickable: true,
            },
        });
    };

    const destroyHeroSwiper = () => {
        if (!heroSwiper) {
            return;
        }

        heroSwiper.destroy(true, true);
        heroSwiper = null;
        heroSwiperElement.classList.remove('swiper');
    };

    const handleHeroSwiperState = () => {
        if (mediaQuery.matches) {
            initHeroSwiper();
            return;
        }

        destroyHeroSwiper();
    };

    handleHeroSwiperState();

    if (typeof mediaQuery.addEventListener === 'function') {
        mediaQuery.addEventListener('change', handleHeroSwiperState);
    } else if (typeof mediaQuery.addListener === 'function') {
        mediaQuery.addListener(handleHeroSwiperState);
    }
}
