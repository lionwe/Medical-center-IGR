/**
 * Header nav: open submenus on hover (desktop + mobile overlay).
 */
const initNavDropdown = () => {
  const CLOSE_DELAY_MS = 800;
  const menus = document.querySelectorAll(
    ".header__menu .nav-list, .header__mobile-menu-nav .nav-list"
  );

  if (!menus.length) {
    return;
  }

  const closeSiblings = (li) => {
    const parent = li.parentElement;
    if (!parent) {
      return;
    }
    parent.querySelectorAll(":scope > .menu-item-has-children.is-open").forEach((sibling) => {
      if (sibling !== li) {
        sibling.classList.remove("is-open");
        const link = sibling.querySelector(":scope > a");
        if (link) {
          link.setAttribute("aria-expanded", "false");
        }
      }
    });
  };

  const closeAllInMenu = (menu) => {
    menu.querySelectorAll(".menu-item-has-children.is-open").forEach((li) => {
      li.classList.remove("is-open");
      const link = li.querySelector(":scope > a");
      if (link) {
        link.setAttribute("aria-expanded", "false");
      }
    });
  };

  menus.forEach((menu) => {
    const isDesktopMenu = Boolean(menu.closest(".header__menu"));

    menu.querySelectorAll(".menu-item-has-children").forEach((li) => {
      const link = li.querySelector(":scope > a");
      const sub = li.querySelector(":scope > .sub-menu");
      if (!link || !sub) {
        return;
      }

      const clearCloseTimer = () => {
        const timerId = Number.parseInt(li.dataset.closeTimerId || "0", 10);
        if (timerId) {
          window.clearTimeout(timerId);
          delete li.dataset.closeTimerId;
        }
      };

      const scheduleClose = () => {
        clearCloseTimer();
        const id = window.setTimeout(() => {
          li.classList.remove("is-open");
          link.setAttribute("aria-expanded", "false");
          delete li.dataset.closeTimerId;
        }, CLOSE_DELAY_MS);
        li.dataset.closeTimerId = String(id);
      };

      link.setAttribute("aria-haspopup", "true");
      link.setAttribute("aria-expanded", "false");

      if (isDesktopMenu) {
        li.addEventListener("mouseenter", () => {
          clearCloseTimer();
          closeSiblings(li);
          li.classList.add("is-open");
          link.setAttribute("aria-expanded", "true");
        });

        // Close on mouseleave (desktop only)
        li.addEventListener("mouseleave", () => {
          // Delay close to prevent flicker when moving cursor into submenu.
          scheduleClose();
        });

        // If user enters submenu or returns to the item, keep it open.
        sub.addEventListener("mouseenter", clearCloseTimer);
        sub.addEventListener("mouseleave", scheduleClose);
      }

      // Keep click functionality for mobile/touch
      const toggle = (event) => {
        event.preventDefault();
        event.stopPropagation();
        clearCloseTimer();
        const wasOpen = li.classList.contains("is-open");
        closeSiblings(li);
        if (wasOpen) {
          li.classList.remove("is-open");
          link.setAttribute("aria-expanded", "false");
        } else {
          li.classList.add("is-open");
          link.setAttribute("aria-expanded", "true");
        }
      };

      link.addEventListener("click", toggle);
      link.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault();
          toggle(event);
        }
      });
    });

    menu.addEventListener("click", (event) => {
      const anchor = event.target.closest("a");
      if (!anchor) {
        return;
      }
      const item = anchor.closest("li");
      if (!item || !menu.contains(item)) {
        return;
      }
      if (item.classList.contains("menu-item-has-children") && anchor === item.querySelector(":scope > a")) {
        return;
      }
      closeAllInMenu(menu);
    });
  });

  document.addEventListener("click", (event) => {
    menus.forEach((menu) => {
      if (!menu.contains(event.target)) {
        closeAllInMenu(menu);
      }
    });
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") {
      return;
    }
    menus.forEach((menu) => closeAllInMenu(menu));
  });
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initNavDropdown);
} else {
  initNavDropdown();
}
