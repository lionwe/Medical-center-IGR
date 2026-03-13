if (document.querySelector(".js-about-gallery-swiper")) {
    import("./gallery-swiper").then(({ default: AboutGallerySwiper }) => {
        new AboutGallerySwiper();
    }).catch((error) => {
        console.error("Failed to load about gallery swiper:", error);
    });
}
