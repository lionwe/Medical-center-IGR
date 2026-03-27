import Swiper from "swiper";
import { Navigation } from "swiper/modules";

let advantagesSwiper = null;

function initAdvantagesSwiper() {
  const slider = document.querySelector(".js-advantages-preg-swiper");
  const section = slider?.closest(".advantages-preg");

  if (!slider) return;

  const isDesktop = window.innerWidth >= 768;
  const prevEl = section?.querySelector(".js-advantages-preg-prev") || null;
  const nextEl = section?.querySelector(".js-advantages-preg-next") || null;

  // destroy previous instance before re-init with new mode
  if (advantagesSwiper) {
    advantagesSwiper.destroy(true, true);
    advantagesSwiper = null;
  }

  advantagesSwiper = new Swiper(slider, {
    modules: [Navigation],
    slidesPerView: isDesktop ? "auto" : 1.1,
    spaceBetween: isDesktop ? 16 : 12,
    speed: 450,
    loop: false,
    watchOverflow: true,
    resistanceRatio: 0.85,
    grabCursor: true,
    allowTouchMove: true,
    navigation: prevEl && nextEl ? { prevEl, nextEl, disabledClass: "swiper-button-disabled" } : undefined,
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