import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

export default class EkzProgramsSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-ekz-programs-slider');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        const isMobile = window.innerWidth < 992;
        
        if (!isMobile) return;

        new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: 1.1,
            spaceBetween: 12,
            navigation: {
                nextEl: '.js-ekz-programs-next',
                prevEl: '.js-ekz-programs-prev',
            },
        });
    }
}
