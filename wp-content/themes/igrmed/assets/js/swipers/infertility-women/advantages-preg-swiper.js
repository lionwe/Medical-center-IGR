import Swiper from "swiper";
import { Autoplay, FreeMode } from "swiper/modules";

let advantagesSwiper = null;

function initAdvantagesSwiper() {
  const slider = document.querySelector(".js-advantages-preg-swiper");

  if (!slider) return;

  const isDesktop = window.innerWidth >= 768;

  // destroy previous instance before re-init with new mode
  if (advantagesSwiper) {
    advantagesSwiper.destroy(true, true);
    advantagesSwiper = null;
  }

  advantagesSwiper = new Swiper(slider, {
    modules: [Autoplay, FreeMode],
    slidesPerView: isDesktop ? "auto" : 1.1,
    spaceBetween: isDesktop ? 16 : 12,
    speed: isDesktop ? 6000 : 450,
    loop: false,
    watchOverflow: true,
    resistanceRatio: isDesktop ? 1 : 0.85,
    grabCursor: !isDesktop,
    allowTouchMove: !isDesktop,
    freeMode: {
      enabled: isDesktop,
      momentum: false,
    },
    autoplay: isDesktop
      ? {
          delay: 0,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        }
      : false,
  });
}

// init on page load
window.addEventListener("load", initAdvantagesSwiper);

let resizeTimeout;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(() => {
    initAdvantagesSwiper();
  }, 200);
});