import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';

export default class AdvantagesSwiper {
    constructor() {
        this.wrapper = document.querySelector('.advantages__list--swiper');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        new Swiper(this.wrapper, {
            modules: [Pagination],
            slidesPerView: 1.4,
            spaceBetween: 12,
            pagination: {
                el: '.advantages__list-pagination',
                clickable: true,
            },
        });
    }
}
