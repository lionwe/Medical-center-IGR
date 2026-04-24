export default class AboutGallerySwiper {
    constructor() {
        this.wrapper = document.querySelector(".js-about-gallery-slider");
        if (!this.wrapper) {
            return;
        }

        this.track = this.wrapper.querySelector(".js-about-gallery-track");
        this.prevBtn = this.wrapper.querySelector(".js-about-gallery-prev");
        this.nextBtn = this.wrapper.querySelector(".js-about-gallery-next");
        this.slides = this.wrapper.querySelectorAll(".about-gallery__slide");

        if (!this.track || !this.prevBtn || !this.nextBtn || this.slides.length === 0) {
            return;
        }

        this.currentIndex = 0;
        this.init();
    }

    getMaxIndex() {
        return this.slides.length - 1;
    }

    updateSlider() {
        const maxIndex = this.getMaxIndex();

        if (this.currentIndex > maxIndex) this.currentIndex = maxIndex;
        if (this.currentIndex < 0) this.currentIndex = 0;

        if (this.currentIndex === 0) {
            this.prevBtn.setAttribute('disabled', 'disabled');
        } else {
            this.prevBtn.removeAttribute('disabled');
        }

        if (this.currentIndex === maxIndex) {
            this.nextBtn.setAttribute('disabled', 'disabled');
        } else {
            this.nextBtn.removeAttribute('disabled');
        }

        const inactiveSlideWidth = window.innerWidth <= 1024 ? this.slides[0].offsetWidth : 330;
        const gap = 20;

        const shift = this.currentIndex * (inactiveSlideWidth + gap);

        this.track.style.transform = `translateX(-${shift}px)`;

        this.slides.forEach(slide => slide.classList.remove("active"));
        this.slides[this.currentIndex].classList.add("active");
    }

    init() {
        this.updateSlider();

        this.nextBtn.addEventListener("click", () => {
            if (this.currentIndex < this.getMaxIndex()) {
                this.currentIndex++;
                this.updateSlider();
            }
        });

        this.prevBtn.addEventListener("click", () => {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.updateSlider();
            }
        });

        window.addEventListener("resize", () => this.updateSlider());
    }
}
