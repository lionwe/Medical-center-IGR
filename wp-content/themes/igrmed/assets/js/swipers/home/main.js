if (document.querySelector(".advantages")) {
    import("./advantages-swiper").then(({ default: AdvantagesSwiper }) => {
        new AdvantagesSwiper();
    }).catch((error) => {
        console.error("Failed to load advantages swiper:", error);
    });
}

if (document.querySelector(".licenses-certificates")) {
    import("./licenses-certificates").then(({ default: LicensesCertificates }) => {
        new LicensesCertificates();
    }).catch((error) => {
        console.error("Failed to load licenses-certificates swiper:", error);
    });
}

if (document.querySelector(".blog-list")) {
    import("./blog-list").then(({ default: BlogListSwiper }) => {
        new BlogListSwiper();
    }).catch((error) => {
        console.error("Failed to load blog-list swiper:", error);
    });
}

if (document.querySelector(".js-hero-swiper")) {
    import("./hero-swiper").catch((error) => {
        console.error("Failed to load hero swiper:", error);
    });
}

if (document.querySelector(".js-doctors-swiper")) {
    import("./doctors-swiper").then(({ default: DoctorsSwiper }) => {
        new DoctorsSwiper();
    }).catch((error) => {
        console.error("Failed to load doctors swiper:", error);
    });
}
if (document.querySelector(".js-reviews-swiper")) {
    console.log('[DEBUG] Found .js-reviews-swiper selector, loading module');
    import("./reviews-swiper").then(({ default: ReviewsSwiper }) => {
        console.log('[DEBUG] ReviewsSwiper module loaded, initializing');
        new ReviewsSwiper();
    }).catch((error) => {
        console.error("Failed to load reviews swiper:", error);
    });
}