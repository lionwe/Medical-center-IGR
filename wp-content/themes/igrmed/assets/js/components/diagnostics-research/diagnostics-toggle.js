function initDiagnosticsGenderToggle() {
  const ajaxParams = window.params || {};
  const ajaxUrl =
    typeof ajaxParams.ajax_url === "string" && ajaxParams.ajax_url.trim() !== ""
      ? ajaxParams.ajax_url
      : "/wp-admin/admin-ajax.php";
  const selectors = {
    section: ".diagnostics-research-diagnostics",
    toggle: ".js-diagnostics-toggle",
    radio: ".diagnostics-research-diagnostics__radio",
    panel: ".diagnostics-research-diagnostics__panel",
    accordion: ".js-diagnostics-accordion",
    accordionItem: ".diagnostics-research-diagnostics__accordion-item",
    accordionTrigger: ".diagnostics-research-diagnostics__accordion-trigger",
    accordionContent: ".diagnostics-research-diagnostics__accordion-content",
    listBlocks: ".list-blocks",
    hiddenServiceItem: ".is-hidden-service",
    collapsibleServiceItem: ".is-collapsible-service",
    listMoreButton: ".diagnostics-research-diagnostics__list-more",
    status: ".diagnostics-research-diagnostics__status",
  };

  const syncListLinesColumnEnds = (scope = document) => {
    const lists = scope.querySelectorAll(".list-lines");

    lists.forEach((list) => {
      const allItems = Array.from(list.querySelectorAll("li"));
      allItems.forEach((item) => item.classList.remove("is-column-last"));

      const visibleItems = allItems.filter((item) => item.offsetParent !== null);
      if (!visibleItems.length) return;

      const lastByColumn = new Map();
      visibleItems.forEach((item) => {
        const rect = item.getBoundingClientRect();
        const key = Math.round(rect.left);
        const current = lastByColumn.get(key);

        if (!current || rect.top > current.top) {
          lastByColumn.set(key, { item, top: rect.top });
        }
      });

      lastByColumn.forEach(({ item }) => item.classList.add("is-column-last"));
    });
  };

  const syncAccordionHeights = (scope = document) => {
    const accordions = scope.querySelectorAll(selectors.accordion);
    accordions.forEach((accordion) => {
      const items = accordion.querySelectorAll(selectors.accordionItem);
      items.forEach((item) => {
        const trigger = item.querySelector(selectors.accordionTrigger);
        const content = item.querySelector(selectors.accordionContent);
        if (!trigger || !content) return;

        const isOpen = trigger.classList.contains("is-open");
        item.classList.toggle("is-open", isOpen);
        content.classList.toggle("is-open", isOpen);
        content.style.maxHeight = isOpen ? "none" : "0px";
      });
    });

    syncListLinesColumnEnds(scope);
  };

  const openAccordionContent = (item, content) => {
    item.classList.add("is-open");
    content.classList.add("is-open");
    content.style.maxHeight = `${content.scrollHeight}px`;

    const onEnd = () => {
      if (item.classList.contains("is-open")) {
        content.style.maxHeight = "none";
      }
      content.removeEventListener("transitionend", onEnd);
    };

    content.addEventListener("transitionend", onEnd);
  };

  const closeAccordionContent = (item, content) => {
    item.classList.remove("is-open");
    content.classList.remove("is-open");

    if (content.style.maxHeight === "none" || !content.style.maxHeight) {
      content.style.maxHeight = `${content.scrollHeight}px`;
    }

    content.offsetHeight;
    requestAnimationFrame(() => {
      content.style.maxHeight = "0px";
    });
  };

  const bindAccordion = (accordion) => {
    if (!accordion || accordion.dataset.bound === "true") return;

    const items = accordion.querySelectorAll(selectors.accordionItem);
    items.forEach((item) => {
      const trigger = item.querySelector(selectors.accordionTrigger);
      const content = item.querySelector(selectors.accordionContent);
      if (!trigger || !content) return;

      trigger.addEventListener("click", () => {
        const isOpen = trigger.classList.contains("is-open");

        accordion.querySelectorAll(`${selectors.accordionItem}.is-open`).forEach((activeItem) => {
          if (activeItem === item) return;
          const activeTrigger = activeItem.querySelector(selectors.accordionTrigger);
          const activeContent = activeItem.querySelector(selectors.accordionContent);
          if (!activeTrigger || !activeContent) return;

          activeTrigger.classList.remove("is-open");
          activeTrigger.setAttribute("aria-expanded", "false");
          closeAccordionContent(activeItem, activeContent);
        });

        trigger.classList.toggle("is-open", !isOpen);
        trigger.setAttribute("aria-expanded", String(!isOpen));

        if (isOpen) {
          closeAccordionContent(item, content);
        } else {
          openAccordionContent(item, content);
        }

        requestAnimationFrame(() => syncListLinesColumnEnds());
      });
    });

    accordion.addEventListener("click", (event) => {
      const moreButton = event.target.closest(selectors.listMoreButton);
      if (!moreButton || !accordion.contains(moreButton)) return;

      const content = moreButton.closest(selectors.accordionContent);
      if (!content) return;

      const listBlocks = content.querySelector(selectors.listBlocks);
      if (!listBlocks) return;

      const collapsibleItems = listBlocks.querySelectorAll(selectors.collapsibleServiceItem);
      if (!collapsibleItems.length) return;

      const textEl = moreButton.querySelector(".diagnostics-research-diagnostics__list-more-text");
      const moreLabel = moreButton.dataset.moreLabel || "Всі процедури";
      const lessLabel = moreButton.dataset.lessLabel || "Згорнути";
      const isExpanded = moreButton.getAttribute("aria-expanded") === "true";

      collapsibleItems.forEach((item) => {
        item.classList.toggle("is-hidden-service", isExpanded);
      });

      moreButton.setAttribute("aria-expanded", String(!isExpanded));
      if (textEl) {
        textEl.textContent = isExpanded ? moreLabel : lessLabel;
      }

      if (content.classList.contains("is-open") && content.style.maxHeight !== "none") {
        content.style.maxHeight = `${content.scrollHeight}px`;
      }

      requestAnimationFrame(() => syncListLinesColumnEnds(content));
    });

    accordion.dataset.bound = "true";
  };

  const bindAccordionsInScope = (scope = document) => {
    scope.querySelectorAll(selectors.accordion).forEach((accordion) => {
      bindAccordion(accordion);
    });
  };

  const setToggleLoading = (toggle, radios, isLoading) => {
    radios.forEach((item) => {
      item.disabled = isLoading;
    });
    toggle.classList.toggle("is-loading", isLoading);
  };

  const buildAjaxBody = (postId) => {
    const body = new URLSearchParams();
    body.append("action", "igrmed_load_diagnostics_men");
    body.append("nonce", ajaxParams.nonce || "");
    body.append("post_id", String(postId || ""));
    return body;
  };

  const loadMenPanel = async (section, panel) => {
    if (!section || !panel || panel.dataset.loaded === "true") return;

    const postId = section.dataset.postId;
    const status = panel.querySelector(selectors.status);
    const loadingText = section.dataset.loadingLabel || "Loading...";
    const errorText = section.dataset.errorLabel || "Failed to load data.";

    section.classList.add("is-loading");
    if (status) {
      status.textContent = loadingText;
    }

    try {
      const response = await fetch(ajaxUrl, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: buildAjaxBody(postId).toString(),
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const data = await response.json();
      if (!data?.success || !data?.data?.html) {
        throw new Error("Invalid response");
      }

      panel.innerHTML = data.data.html;
      panel.dataset.loaded = "true";
      bindAccordionsInScope(panel);
      requestAnimationFrame(() => syncAccordionHeights(panel));
    } catch (error) {
      if (status) {
        status.textContent = errorText;
      } else {
        panel.innerHTML = `<div class="diagnostics-research-diagnostics__status">${errorText}</div>`;
      }
      panel.dataset.loaded = "error";
    } finally {
      section.classList.remove("is-loading");
    }
  };

  const toggles = document.querySelectorAll(selectors.toggle);

  if (!toggles.length) return;

  toggles.forEach((toggle) => {
    const section = toggle.closest(selectors.section);
    const radios = toggle.querySelectorAll(selectors.radio);
    const panels = section
      ? section.querySelectorAll(selectors.panel)
      : [];

    if (!radios.length) return;

    radios.forEach((radio) => {
      radio.addEventListener("change", async () => {
        if (!radio.checked) return;

        const target = radio.dataset.toggle;
        if (!target) return;
        const activePanel = section?.querySelector(`${selectors.panel}.is-active`);
        if (activePanel?.dataset.panel === target) return;

        const menPanel = section?.querySelector(`${selectors.panel}[data-panel="men"]`);
        const shouldLoadMen = target === "men" && menPanel && menPanel.dataset.loaded !== "true";

        if (shouldLoadMen) {
          setToggleLoading(toggle, radios, true);
          await loadMenPanel(section, menPanel);
          setToggleLoading(toggle, radios, false);
        }

        if (panels.length) {
          panels.forEach((panel) => {
            const isCurrent = panel.dataset.panel === target;
            panel.hidden = !isCurrent;
            panel.classList.toggle("is-active", isCurrent);

            if (isCurrent) {
              requestAnimationFrame(() => syncAccordionHeights(panel));
            }
          });
        }
      });
    });
  });

  bindAccordionsInScope(document);

  syncAccordionHeights();
  window.addEventListener("resize", () => syncListLinesColumnEnds());
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initDiagnosticsGenderToggle);
} else {
  initDiagnosticsGenderToggle();
}
