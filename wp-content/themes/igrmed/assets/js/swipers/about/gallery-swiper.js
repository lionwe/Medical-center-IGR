import Swiper from "swiper";
import { Navigation } from "swiper/modules";

export default class AboutGallerySwiper {
    constructor() {
        this.wrapper = document.querySelector(".js-about-gallery-swiper");
        if (!this.wrapper) {
            return;
        }

        const syncLayout = (swiper) => {
            requestAnimationFrame(() => {
                swiper.update();
                swiper.slideTo(swiper.activeIndex, 0, false);
            });
        };

        this.swiper = new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: "auto",
            centeredSlides: false,
            watchOverflow: true,
            observer: true,
            observeParents: true,
            initialSlide: 0,
            speed: 450,
            spaceBetween: 20,
            slideToClickedSlide: true,
            slidesPerGroup: 1,
            navigation: {
                prevEl: ".js-about-gallery-prev",
                nextEl: ".js-about-gallery-next",
                disabledClass: "swiper-button-disabled",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 12,
                },
                768: {
                    slidesPerView: "auto",
                    spaceBetween: 16,
                },
                992: {
                    slidesPerView: "auto",
                    spaceBetween: 20,
                },
            },
            on: {
                init(swiper) {
                    syncLayout(swiper);
                },
                slideChangeTransitionStart(swiper) {
                    syncLayout(swiper);
                },
                resize(swiper) {
                    syncLayout(swiper);
                },
            },
        });
    }
}
