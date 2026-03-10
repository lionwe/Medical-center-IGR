import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

export default class BlogListSwiper {
    constructor() {
        this.wrapper = document.querySelector('.js-blog-slider');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        new Swiper(this.wrapper, {
            modules: [Navigation, Pagination],
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.js-blog-next',
                prevEl: '.js-blog-prev',
            },
            pagination: {
                el: '.blog-list__pagination',
                clickable: true,
            },
        });
    }
}
