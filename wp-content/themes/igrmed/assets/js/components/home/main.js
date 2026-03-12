if (document.querySelector(".advantages")) {
    import("./advantages").catch((error) => {
        console.error("Failed to load advantages module:", error);
    });
}
if (document.querySelector(".why-choose-us")) {
    import("./why-choose-us").then(({ default: WhyChooseUs }) => {
        new WhyChooseUs();
    }).catch((error) => {
        console.error("Failed to load why-choose-us module:", error);
    });
}

if (document.querySelector(".google-reviews")) {
    import("./google-reviews").then(({ default: GoogleReviewsControls }) => {
        new GoogleReviewsControls();
    }).catch((error) => {
        console.error("Failed to load google-reviews module:", error);
    });
}

if (document.querySelector(".cta")) {
    import("./cta").catch((error) => {
        console.error("Failed to load cta module:", error);
    });
}