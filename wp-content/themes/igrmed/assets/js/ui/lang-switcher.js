const PANEL_CLOSE_MS = 380;

const removeAnimClasses = (root) => {
  root.classList.remove("anim-options", "show-shadow");
};

const closePanel = (root) => {
  const btn = root.querySelector(".header__lang-trigger");
  const panel = root.querySelector(".header__lang-dropdown");

  if (!root.classList.contains("show-options")) {
    return;
  }

  removeAnimClasses(root);

  window.setTimeout(() => {
    root.classList.remove("show-options");
    if (btn) {
      btn.setAttribute("aria-expanded", "false");
    }
    if (panel) {
      panel.setAttribute("aria-hidden", "true");
    }
  }, PANEL_CLOSE_MS);
};

const openPanel = (root) => {
  const btn = root.querySelector(".header__lang-trigger");
  const panel = root.querySelector(".header__lang-dropdown");

  root.classList.add("show-options");
  if (btn) {
    btn.setAttribute("aria-expanded", "true");
  }
  if (panel) {
    panel.setAttribute("aria-hidden", "false");
  }

  requestAnimationFrame(() => {
    window.setTimeout(() => root.classList.add("anim-options"), 50);
  });
  window.setTimeout(() => root.classList.add("show-shadow"), 200);
};

const initLangSwitcher = () => {
  const roots = document.querySelectorAll("[data-lang-switcher]");
  if (!roots.length) {
    return;
  }

  roots.forEach((root) => {
    const btn = root.querySelector(".header__lang-trigger");
    const panel = root.querySelector(".header__lang-dropdown");
    if (!btn || !panel) {
      return;
    }

    panel.addEventListener("click", (e) => e.stopPropagation());

    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = root.classList.contains("show-options");
      roots.forEach((other) => {
        if (other !== root) {
          closePanel(other);
        }
      });
      if (isOpen) {
        closePanel(root);
      } else {
        openPanel(root);
      }
    });
  });

  document.addEventListener("click", () => {
    roots.forEach(closePanel);
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      roots.forEach(closePanel);
    }
  });
};

document.addEventListener("DOMContentLoaded", initLangSwitcher);
