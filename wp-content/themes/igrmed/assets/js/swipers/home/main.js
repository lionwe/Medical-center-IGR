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