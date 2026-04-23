const initMobileMenu = () => {
  const menu = document.querySelector("[data-mobile-menu]");
  const toggle = document.querySelector("[data-mobile-menu-toggle]");
  const header = document.querySelector(".header");
  const scrollLockKey = "scroll-lock-y";

  if (!menu || !toggle) {
    return;
  }

  const preventTouchMove = (e) => {
    e.preventDefault();
  };

  const lockScroll = () => {
    const y = window.scrollY || window.pageYOffset || 0;
    document.body.dataset[scrollLockKey] = String(y);

    document.documentElement.classList.add("noOverflow");
    document.body.classList.add("noOverflow");
    document.body.classList.add("fixed-position");

    document.body.style.position = "fixed";
    document.body.style.top = `-${y}px`;
    document.body.style.left = "0";
    document.body.style.right = "0";
    document.body.style.width = "100%";

    document.addEventListener("touchmove", preventTouchMove, { passive: false });
  };

  const unlockScroll = () => {
    const y = Number.parseInt(document.body.dataset[scrollLockKey] || "0", 10) || 0;

    document.removeEventListener("touchmove", preventTouchMove);

    document.documentElement.classList.remove("noOverflow");
    document.body.classList.remove("noOverflow");
    document.body.classList.remove("fixed-position");

    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.left = "";
    document.body.style.right = "";
    document.body.style.width = "";

    delete document.body.dataset[scrollLockKey];
    window.scrollTo(0, y);
  };

  const closeMenu = () => {
    menu.classList.remove("is-open");
    toggle.classList.remove("is-active");
    toggle.setAttribute("aria-expanded", "false");
    menu.setAttribute("aria-hidden", "true");
    document.body.classList.remove("mobile-menu-open");
    unlockScroll();
  };

  const openMenu = () => {
    if (header) {
      header.classList.remove("is-hidden");
    }
    menu.classList.add("is-open");
    toggle.classList.add("is-active");
    toggle.setAttribute("aria-expanded", "true");
    menu.setAttribute("aria-hidden", "false");
    document.body.classList.add("mobile-menu-open");
    lockScroll();
  };

  toggle.addEventListener("click", () => {
    if (menu.classList.contains("is-open")) {
      closeMenu();
      return;
    }

    openMenu();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && menu.classList.contains("is-open")) {
      closeMenu();
    }
  });
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initMobileMenu);
} else {
  initMobileMenu();
}
