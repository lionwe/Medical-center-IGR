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