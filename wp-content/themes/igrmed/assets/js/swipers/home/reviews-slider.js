import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';


export default class ReviewsSlider {
    constructor() {
        this.wrapper = document.querySelector('.js-reviews-swiper'); 
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.js-reviews-next',
                prevEl: '.js-reviews-prev',
            },
            breakpoints: {
                576: { slidesPerView: 2, spaceBetween: 20 },
                992: { slidesPerView: 3, spaceBetween: 20 },
            },
        });
    }
}