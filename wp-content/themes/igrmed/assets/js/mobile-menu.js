const initMobileMenu = () => {
  const menu = document.querySelector("[data-mobile-menu]");
  const toggle = document.querySelector("[data-mobile-menu-toggle]");

  if (!menu || !toggle) {
    return;
  }

  const closeMenu = () => {
    menu.classList.remove("is-open");
    toggle.classList.remove("is-active");
    toggle.setAttribute("aria-expanded", "false");
    menu.setAttribute("aria-hidden", "true");
    document.body.classList.remove("mobile-menu-open");
  };

  const openMenu = () => {
    menu.classList.add("is-open");
    toggle.classList.add("is-active");
    toggle.setAttribute("aria-expanded", "true");
    menu.setAttribute("aria-hidden", "false");
    document.body.classList.add("mobile-menu-open");
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

document.addEventListener("DOMContentLoaded", initMobileMenu);
