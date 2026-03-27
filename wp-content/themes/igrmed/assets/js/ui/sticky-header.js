const initHeaderVisibility = () => {
  const header = document.querySelector(".header");
  if (!header) {
    return;
  }

  let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
  let isTicking = false;
  const threshold = 10;

  const canHideHeader = () =>
    !document.body.classList.contains("mobile-menu-open");

  const updateHeaderState = () => {
    const currentScrollTop =
      window.pageYOffset || document.documentElement.scrollTop;

    if (!canHideHeader()) {
      header.classList.remove("is-hidden");
      lastScrollTop = currentScrollTop;
      isTicking = false;
      return;
    }

    if (currentScrollTop < 0) {
      isTicking = false;
      return;
    }

    if (Math.abs(currentScrollTop - lastScrollTop) <= threshold) {
      isTicking = false;
      return;
    }

    if (currentScrollTop > 50) {
      header.classList.add("is-scrolled");
    } else {
      header.classList.remove("is-scrolled");
    }

    if (
      currentScrollTop > lastScrollTop &&
      currentScrollTop > header.offsetHeight
    ) {
      header.classList.add("is-hidden");
    } else {
      header.classList.remove("is-hidden");
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
  });
};

document.addEventListener("DOMContentLoaded", initHeaderVisibility);
