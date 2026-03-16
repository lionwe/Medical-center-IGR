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
        const activeText = section.querySelector(".js-about-gallery-active-text");
        const applySlidesOffsetAfter = (swiper) => {
            const offsetAfter = window.innerWidth < 768 ? 0 : 1050;
            swiper.params.slidesOffsetAfter = offsetAfter;
            swiper.update();
        };

        const animateTopText = () => {
            if (!activeText) {
                return;
            }

            activeText.classList.remove("is-animating");
            void activeText.offsetWidth;
            activeText.classList.add("is-animating");
        };

        const updateTopText = (swiper) => {
            if (!activeText) {
                return;
            }

            const activeSlide = swiper.slides?.[swiper.activeIndex] ?? null;
            const sourceText = activeSlide?.querySelector(".js-about-gallery-slide-text");
            activeText.innerHTML = sourceText ? sourceText.innerHTML : "";
            animateTopText();
        };

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
            slidesOffsetAfter: window.innerWidth < 768 ? 0 : 1050,
            navigation: {
                prevEl: prevButton,
                nextEl: nextButton,
                disabledClass: "swiper-button-disabled",
            },
            on: {
                init(swiper) {
                    applySlidesOffsetAfter(swiper);
                    updateTopText(swiper);
                },
                slideChange(swiper) {
                    updateTopText(swiper);
                },
                resize(swiper) {
                    applySlidesOffsetAfter(swiper);
                },
            },
        });
    }
}
