const initHeaderVisibility = () => {
  const header = document.querySelector(".header");
  if (!header) {
    return;
  }

  let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
  let isTicking = false;
  const getIsMobile = () =>
    window.matchMedia &&
    window.matchMedia("(max-width: 992px)").matches;

  // Ignore micro scroll deltas (esp. iOS momentum scrolling)
  const getDeltaIgnoreThreshold = () => (getIsMobile() ? 18 : 10);

  // Require some real intent before toggling visibility (reduces “jitter”)
  const getToggleThreshold = () => (getIsMobile() ? 44 : 12);

  let accumulatedDelta = 0;

  const canHideHeader = () =>
    !document.body.classList.contains("mobile-menu-open");

  const updateHeaderState = () => {
    const currentScrollTop =
      window.pageYOffset || document.documentElement.scrollTop;

    if (!canHideHeader()) {
      header.classList.remove("is-hidden");
      lastScrollTop = currentScrollTop;
      accumulatedDelta = 0;
      isTicking = false;
      return;
    }

    if (currentScrollTop < 0) {
      isTicking = false;
      return;
    }

    if (currentScrollTop > 10) {
      header.classList.add("is-scrolled");
    } else {
      header.classList.remove("is-scrolled");
    }

    const delta = currentScrollTop - lastScrollTop;
    const absDelta = Math.abs(delta);

    if (absDelta <= getDeltaIgnoreThreshold()) {
      isTicking = false;
      return;
    }

    accumulatedDelta += delta;

    // If direction flips, reset accumulator to avoid flicker
    if (
      (accumulatedDelta > 0 && delta < 0) ||
      (accumulatedDelta < 0 && delta > 0)
    ) {
      accumulatedDelta = delta;
    }

    const shouldToggle = Math.abs(accumulatedDelta) >= getToggleThreshold();

    if (
      shouldToggle &&
      accumulatedDelta > 0 &&
      currentScrollTop > header.offsetHeight
    ) {
      header.classList.add("is-hidden");
      accumulatedDelta = 0;
    } else if (shouldToggle && accumulatedDelta < 0) {
      header.classList.remove("is-hidden");
      accumulatedDelta = 0;
    }

    lastScrollTop = currentScrollTop;
    isTicking = false;
  };

  const onScroll = () => {
    if (!isTicking) {
      window.requestAnimationFrame(updateHeaderState);
      isTicking = true;
    }
  };

  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", () => {
    if (!canHideHeader()) {
      header.classList.remove("is-hidden");
    }
    updateHeaderState();
  });

  updateHeaderState();
  window.requestAnimationFrame(updateHeaderState);
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initHeaderVisibility);
} else {
  initHeaderVisibility();
}
