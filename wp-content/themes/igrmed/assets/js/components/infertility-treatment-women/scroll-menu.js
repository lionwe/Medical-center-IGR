function initInfertilityTreatmentWomenMenu() {
  const isDesktop = window.matchMedia("(min-width: 1200px)").matches;
  if (!isDesktop) return;

  const categoriesList = document.querySelector(".js-content-sidebar");
  if (!categoriesList) return;
  const sidebar = categoriesList.closest(".content-sidebar");
  const sidebarPanel = sidebar ? sidebar.querySelector(".content-sidebar__panel") : null;
  const section = categoriesList.closest(".infertility-treatment-women-content");
  const stickyOffset = 100;

  const links = Array.from(categoriesList.querySelectorAll(".js-content-sidebar-link[data-target]"));
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

    const yOffset = 95;
    const y = target.getBoundingClientRect().top + window.scrollY - yOffset;

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

    if (window.history && typeof window.history.replaceState === "function") {
      window.history.replaceState(null, "", `#${targetId}`);
    }
  });

  const observer = new IntersectionObserver(
    (entries) => {
      let mostVisible = null;

      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        if (!mostVisible || entry.intersectionRatio > mostVisible.intersectionRatio) {
          mostVisible = entry;
        }
      });

      if (!mostVisible) return;

      const visibleId = mostVisible.target.id;
      if (visibleId && linksMap.has(visibleId)) {
        setActiveLink(visibleId);
      }
    },
    {
      root: null,
      rootMargin: "-35% 0px -55% 0px",
      threshold: [0.1, 0.25, 0.5, 0.75],
    },
  );

  sections.forEach((section) => observer.observe(section.target));

  const hashId = window.location.hash ? window.location.hash.replace("#", "") : "";
  if (hashId && linksMap.has(hashId)) {
    setActiveLink(hashId);
  } else {
    setActiveLink(sections[0].id);
  }

  const hasSidebarPinning = Boolean(sidebar && sidebarPanel && section);
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
    const desiredTranslate = window.scrollY + stickyOffset - sidebarTop;
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
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initInfertilityTreatmentWomenMenu);
} else {
  initInfertilityTreatmentWomenMenu();
}
