import Swiper from "swiper";
import { Navigation } from "swiper/modules";

export default class AboutGallerySwiper {
    constructor() {
        this.wrapper = document.querySelector(".js-about-gallery-swiper");
        if (!this.wrapper) {
            return;
        }
        const section = this.wrapper.closest(".about-gallery") || document;
        const prevButton = section.querySelector(".js-about-gallery-prev");
        const nextButton = section.querySelector(".js-about-gallery-next");

        this.swiper = new Swiper(this.wrapper, {
            modules: [Navigation],
            slidesPerView: "auto",
            centeredSlides: false,
            watchOverflow: true,
            observer: true,
            observeParents: true,
            speed: 450,
            spaceBetween: 20,
            slideToClickedSlide: true,
            slidesPerGroup: 1,
            navigation: {
                prevEl: prevButton,
                nextEl: nextButton,
                disabledClass: "swiper-button-disabled",
            },
        });
    }
}
