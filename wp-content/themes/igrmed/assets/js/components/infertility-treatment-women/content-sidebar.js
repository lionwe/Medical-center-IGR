function initContentSidebarNavigation() {
  const isDesktop = window.matchMedia("(min-width: 1200px)").matches;
  if (!isDesktop) return;

  const categoriesList = document.querySelector(".js-content-sidebar");
  if (!categoriesList) return;

  const sidebar = categoriesList.closest(".content-sidebar");
  const sidebarPanel = sidebar ? sidebar.querySelector(".content-sidebar__panel") : null;
  const contentSection = categoriesList.closest(".catalog-wrap");

  const STICKY_OFFSET = 100;

  const links = Array.from(
    categoriesList.querySelectorAll(".js-content-sidebar-link[data-target]"),
  );
  if (!links.length) return;

  const linksMap = new Map(
    links.map((link) => [link.dataset.target || "", link]).filter(([id]) => id !== ""),
  );

  const sections = links
    .map((link) => {
      const targetId = link.dataset.target || "";
      const target = targetId ? document.getElementById(targetId) : null;
      return target ? { id: targetId, target } : null;
    })
    .filter(Boolean);

  if (!sections.length) return;

  let activeSectionId = "";

  const setActiveLink = (sectionId) => {
    if (!sectionId || activeSectionId === sectionId) return;
    activeSectionId = sectionId;

    links.forEach((link) => {
      const isActive = (link.dataset.target || "") === sectionId;
      link.classList.toggle("content-sidebar__link--active", isActive);
      if (isActive) {
        link.setAttribute("aria-current", "location");
      } else {
        link.removeAttribute("aria-current");
      }
    });
  };

  const scrollToTarget = (target) => {
    if (!target) return;

    const y = target.getBoundingClientRect().top + window.scrollY - STICKY_OFFSET;

    window.scrollTo({
      top: y,
      behavior: "smooth",
    });
  };

  categoriesList.addEventListener("click", (event) => {
    const clickedLink = event.target.closest(".js-content-sidebar-link[data-target]");
    if (!clickedLink || !categoriesList.contains(clickedLink)) return;

    event.preventDefault();

    const targetId = clickedLink.dataset.target || "";
    const target = targetId ? document.getElementById(targetId) : null;
    if (!target) return;

    setActiveLink(targetId);
    scrollToTarget(target);

    if (window.history && typeof window.history.pushState === "function") {
      window.history.pushState(null, "", `#${targetId}`);
    }
  });

  const updateActiveByScrollPosition = () => {
    const anchorY = window.scrollY + STICKY_OFFSET + 20;
    let candidateId = sections[0].id;

    sections.forEach((sectionEntry) => {
      const sectionTop = sectionEntry.target.getBoundingClientRect().top + window.scrollY;
      if (sectionTop <= anchorY) {
        candidateId = sectionEntry.id;
      }
    });

    const scrollBottom = window.scrollY + window.innerHeight;
    const documentBottom = document.documentElement.scrollHeight;
    const isNearPageBottom = scrollBottom >= documentBottom - 2;

    if (isNearPageBottom) {
      candidateId = sections[sections.length - 1].id;
    }

    setActiveLink(candidateId);
  };

  let activeRafId = 0;
  const requestActiveUpdate = () => {
    if (activeRafId) return;
    activeRafId = window.requestAnimationFrame(() => {
      activeRafId = 0;
      updateActiveByScrollPosition();
    });
  };

  window.addEventListener("scroll", requestActiveUpdate, { passive: true });
  window.addEventListener("resize", requestActiveUpdate);

  const hashId = window.location.hash ? window.location.hash.replace("#", "") : "";
  if (hashId && linksMap.has(hashId)) {
    setActiveLink(hashId);
  } else {
    setActiveLink(sections[0].id);
  }
  requestActiveUpdate();

  const hasSidebarPinning = Boolean(sidebar && sidebarPanel && contentSection);
  if (!hasSidebarPinning) return;

  const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

  const setupSidebarPinning = () => {
    sidebar.style.position = "relative";
    sidebarPanel.style.position = "absolute";
    sidebarPanel.style.top = "0";
    sidebarPanel.style.left = "0";
    sidebarPanel.style.width = "100%";
    sidebarPanel.style.willChange = "transform";
  };

  const updateSidebarPinning = () => {
    const sidebarTop = sidebar.getBoundingClientRect().top + window.scrollY;
    const panelHeight = sidebarPanel.offsetHeight;
    const maxTranslate = Math.max(sidebar.offsetHeight - panelHeight, 0);
    const desiredTranslate = window.scrollY + STICKY_OFFSET - sidebarTop;
    const clampedTranslate = clamp(desiredTranslate, 0, maxTranslate);
    sidebarPanel.style.transform = `translateY(${Math.round(clampedTranslate)}px)`;
  };

  let rafId = 0;
  const requestSidebarUpdate = () => {
    if (rafId) return;
    rafId = window.requestAnimationFrame(() => {
      rafId = 0;
      updateSidebarPinning();
    });
  };

  setupSidebarPinning();
  updateSidebarPinning();
  window.addEventListener("scroll", requestSidebarUpdate, { passive: true });
  window.addEventListener("resize", requestSidebarUpdate);

  return () => {
    window.removeEventListener("scroll", requestActiveUpdate);
    window.removeEventListener("resize", requestActiveUpdate);
    window.removeEventListener("scroll", requestSidebarUpdate);
    window.removeEventListener("resize", requestSidebarUpdate);
    if (activeRafId) window.cancelAnimationFrame(activeRafId);
    if (rafId) window.cancelAnimationFrame(rafId);
  };
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initContentSidebarNavigation);
} else {
  initContentSidebarNavigation();
}
