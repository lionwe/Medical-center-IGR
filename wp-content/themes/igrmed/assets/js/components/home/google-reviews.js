export default class GoogleReviewsControls {
    constructor() {
        this.section = document.querySelector(".google-reviews");
        if (!this.section) {
            return;
        }

        this.embed = this.section.querySelector(".google-reviews__embed");
        this.prevButton = this.section.querySelector(".js-google-reviews-prev");
        this.nextButton = this.section.querySelector(".js-google-reviews-next");

        if (!this.embed || !this.prevButton || !this.nextButton) {
            return;
        }

        this.bindedClickPrev = (event) => this.handleClick(event, "prev");
        this.bindedClickNext = (event) => this.handleClick(event, "next");

        this.prevButton.addEventListener("click", this.bindedClickPrev);
        this.nextButton.addEventListener("click", this.bindedClickNext);

        this.observeEmbed();
        this.refreshNativeControls();
    }

    observeEmbed() {
        this.observer = new MutationObserver(() => {
            this.refreshNativeControls();
            this.updateButtonState();
        });

        this.observer.observe(this.embed, { childList: true, subtree: true, attributes: true });

        // Trustindex can render with delay; short polling improves first bind reliability.
        this.pollAttempts = 0;
        this.pollTimer = window.setInterval(() => {
            this.refreshNativeControls();
            this.updateButtonState();

            this.pollAttempts += 1;
            if ((this.nativePrev && this.nativeNext) || this.pollAttempts >= 20) {
                window.clearInterval(this.pollTimer);
            }
        }, 500);
    }

    refreshNativeControls() {
        this.nativePrev = this.findNativeButton("prev");
        this.nativeNext = this.findNativeButton("next");
        const foundTrack = this.findNativeTrack();
        if (foundTrack !== this.nativeTrack) {
            if (this.nativeTrack && this.bindedTrackScroll) {
                this.nativeTrack.removeEventListener("scroll", this.bindedTrackScroll);
            }

            this.nativeTrack = foundTrack;
            this.bindedTrackScroll = () => this.updateButtonState();

            if (this.nativeTrack) {
                this.nativeTrack.addEventListener("scroll", this.bindedTrackScroll, { passive: true });
            }
        }
    }

    findNativeButton(direction) {
        const selectorGroups = {
            prev: [
                ".ti-prev",
                ".ti-widget .ti-prev",
                ".ti-widget [class*='prev']",
                ".trustindex-slider-container .slick-prev",
                ".trustindex-widget .slick-prev",
                ".trustindex-widget .swiper-button-prev",
                "[aria-label*='Prev']",
                "[aria-label*='Previous']",
                "[aria-label*='Поперед']",
            ],
            next: [
                ".ti-next",
                ".ti-widget .ti-next",
                ".ti-widget [class*='next']",
                ".trustindex-slider-container .slick-next",
                ".trustindex-widget .slick-next",
                ".trustindex-widget .swiper-button-next",
                "[aria-label*='Next']",
                "[aria-label*='Following']",
                "[aria-label*='Наступ']",
            ],
        };

        const selectors = selectorGroups[direction];
        for (const selector of selectors) {
            const button = this.embed.querySelector(selector);
            if (!button) {
                continue;
            }

            // Skip custom theme controls and keep only Trustindex internals.
            if (button.classList.contains("js-google-reviews-prev") || button.classList.contains("js-google-reviews-next")) {
                continue;
            }

            if (button.closest(".google-reviews__nav")) {
                continue;
            }

            if (button) {
                return button;
            }
        }

        return null;
    }

    findNativeTrack() {
        const selectors = [
            ".ti-reviews-container-wrapper",
            ".trustindex-slider-container",
            ".slick-track",
            ".swiper-wrapper",
        ];

        for (const selector of selectors) {
            const track = this.embed.querySelector(selector);
            if (track) {
                return track;
            }
        }

        return null;
    }

    handleClick(event, direction) {
        event.preventDefault();
        event.stopPropagation();

        if (!this.nativePrev && !this.nativeNext && typeof window.grw_boot === "function") {
            window.grw_boot();
            this.refreshNativeControls();
        }

        const nativeButton = direction === "prev" ? this.nativePrev : this.nativeNext;
        if (nativeButton) {
            nativeButton.click();
        } else {
            this.slideTrack(direction);
        }

        this.scheduleStateSync();
    }

    slideTrack(direction) {
        if (!this.nativeTrack) {
            return;
        }

        const slideStep = Math.max(this.nativeTrack.clientWidth * 0.8, 240);
        const delta = direction === "next" ? slideStep : -slideStep;

        this.nativeTrack.scrollBy({ left: delta, behavior: "smooth" });
    }

    updateButtonState() {
        this.toggleDisabledClass(this.prevButton, this.isDisabled(this.nativePrev, "prev"));
        this.toggleDisabledClass(this.nextButton, this.isDisabled(this.nativeNext, "next"));
    }

    isDisabled(button, direction) {
        const disabledByTrack = this.isTrackEdge(direction);

        if (!button) {
            return disabledByTrack;
        }

        const hasDisabledAttr = button.hasAttribute("disabled") || button.getAttribute("aria-disabled") === "true";
        const disabledByClass = button.classList.contains("disabled")
            || button.classList.contains("swiper-button-disabled")
            || button.classList.contains("slick-disabled");

        return Boolean(hasDisabledAttr || disabledByClass || disabledByTrack);
    }

    isTrackEdge(direction) {
        if (!this.nativeTrack) {
            return false;
        }

        // Trustindex may move slides with transform instead of scroll.
        // Use slide geometry as a primary source of truth.
        const edgeByGeometry = this.getTrackEdgeByGeometry(direction);
        if (edgeByGeometry !== null) {
            return edgeByGeometry;
        }

        const maxScrollLeft = this.nativeTrack.scrollWidth - this.nativeTrack.clientWidth;
        const current = this.nativeTrack.scrollLeft;
        const threshold = 2;

        if (direction === "prev") {
            return current <= threshold;
        }

        if (direction === "next") {
            return current >= (maxScrollLeft - threshold);
        }

        return false;
    }

    getTrackEdgeByGeometry(direction) {
        if (!this.nativeTrack) {
            return null;
        }

        const items = this.nativeTrack.querySelectorAll(".ti-review-item");
        if (!items.length) {
            return null;
        }

        const firstItem = items[0];
        const lastItem = items[items.length - 1];
        if (!firstItem || !lastItem) {
            return null;
        }

        const trackRect = this.nativeTrack.getBoundingClientRect();
        const firstRect = firstItem.getBoundingClientRect();
        const lastRect = lastItem.getBoundingClientRect();
        const threshold = 2;

        const atStart = firstRect.left >= (trackRect.left - threshold);
        const atEnd = lastRect.right <= (trackRect.right + threshold);

        if (direction === "prev") {
            return atStart;
        }

        if (direction === "next") {
            return atEnd;
        }

        return null;
    }

    scheduleStateSync() {
        const sync = () => {
            this.refreshNativeControls();
            this.updateButtonState();
        };

        // Trustindex updates asynchronously; check state in short steps.
        [80, 180, 320, 520].forEach((delay) => {
            window.setTimeout(sync, delay);
        });
    }

    toggleDisabledClass(button, isDisabled) {
        button.classList.toggle("swiper-button-disabled", isDisabled);
    }
}
