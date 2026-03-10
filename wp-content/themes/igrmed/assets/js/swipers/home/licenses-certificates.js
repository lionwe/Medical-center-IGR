import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

export default class LicensesCertificates {
    constructor() {
        this.wrapper = document.querySelector('.js-licenses-slider');
        if (this.wrapper) {
            this.init();
        }
    }

    init() {
        new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: 1,
            centeredSlides: true,
            spaceBetween: 20,
            navigation: {
                nextEl: '.js-licenses-next',
                prevEl: '.js-licenses-prev',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    centeredSlides: false,
                    spaceBetween: 20
                },
                992: {
                    slidesPerView: 3,
                    centeredSlides: false,
                    spaceBetween: 20
                },
                1200: {
                    slidesPerView: 4,
                    centeredSlides: false,
                    spaceBetween: 20
                }
            }
        });
    }
}
