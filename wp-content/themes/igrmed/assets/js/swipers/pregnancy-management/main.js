import PregnancyDoctorsSwiper from './doctors-swiper';
import PregnancyDoctorsSwiperMobile from './doctors-swiper-mobile';

const init = () => {
    new PregnancyDoctorsSwiper();
    new PregnancyDoctorsSwiperMobile();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}
