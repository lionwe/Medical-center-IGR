/**
 * Live Search Module
 *
 * Handles both desktop inline search and mobile popup search.
 */

const initSearch = () => {
  // ─── Desktop search ───
  const searchContainer = document.querySelector("#header-search");
  // ─── Mobile popup ───
  const popup = document.querySelector("#mobile-search-popup");
  const mobileSearchBtn = document.querySelector(".header__search-btn-bar");

  let debounceTimer;

  /**
   * Shared AJAX search function.
   * @param {string} query - Search term.
   * @param {HTMLElement} resultsEl - Container for results HTML.
   * @param {HTMLElement} stateEl - Element that receives is-loading / is-searching classes.
   */
  const performSearch = (query, resultsEl, stateEl) => {
    if (query.length < 2) {
      resultsEl.innerHTML = "";
      stateEl.classList.remove("is-loading", "is-searching");
      return;
    }

    stateEl.classList.add("is-loading", "is-searching");

    const formData = new FormData();
    formData.append("action", "igrmed_live_search");
    formData.append("nonce", params.nonce);
    formData.append("s", query);
    formData.append("lang", params.current_lang || "");

    fetch(params.ajax_url, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        stateEl.classList.remove("is-loading");

        if (data.success && data.data && data.data.html) {
          resultsEl.innerHTML = data.data.html;
        } else {
          resultsEl.innerHTML = "";
        }
      })
      .catch((error) => {
        console.error("Search error:", error);
        stateEl.classList.remove("is-loading", "is-searching");
      });
  };

  // ─────────────────────────────────────────
  // Desktop search (only if element exists)
  // ─────────────────────────────────────────
  if (searchContainer) {
    const input = searchContainer.querySelector(".header__search-input");
    const resultsContainer = searchContainer.querySelector(
      ".header__search-results"
    );
    const closeBtn = searchContainer.querySelector(
      ".header__search-icon-wrapper--close"
    );

    input.addEventListener("input", (e) => {
      const query = e.target.value.trim();
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(
        () => performSearch(query, resultsContainer, searchContainer),
        300
      );
    });

    const clearSearch = () => {
      input.value = "";
      resultsContainer.innerHTML = "";
      resultsContainer.classList.remove("is-visible");
      searchContainer.classList.remove("is-searching", "is-loading");
    };

    closeBtn.addEventListener("click", clearSearch);

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        resultsContainer.classList.remove("is-visible");
      }
    });

    input.addEventListener("focus", () => {
      if (resultsContainer.innerHTML.trim() !== "") {
        resultsContainer.classList.add("is-visible");
      }
    });

    document.addEventListener("click", (e) => {
      if (!searchContainer.contains(e.target)) {
        resultsContainer.classList.remove("is-visible");
      }
    });

    window.addEventListener(
      "scroll",
      () => {
        if (resultsContainer.classList.contains("is-visible")) {
          resultsContainer.classList.remove("is-visible");
        }
      },
      { passive: true }
    );

    // Auto show/hide results container when content changes
    const observer = new MutationObserver(() => {
      if (resultsContainer.innerHTML.trim() !== "") {
        resultsContainer.classList.add("is-visible");
      } else {
        resultsContainer.classList.remove("is-visible");
      }
    });
    observer.observe(resultsContainer, { childList: true });
  }

  // ─────────────────────────────────────────
  // Mobile search popup
  // ─────────────────────────────────────────
  if (popup && mobileSearchBtn) {
    const popupInput = popup.querySelector(".header__search-popup-input");
    const popupResults = popup.querySelector(".header__search-popup-results");
    const popupClose = popup.querySelector(".header__search-popup-close");
    const popupOverlay = popup.querySelector(".header__search-popup-overlay");

    const openPopup = () => {
      popup.classList.add("is-open");
      document.body.style.overflow = "hidden";

      // Trigger animation in next frame
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          popup.classList.add("is-visible");
          popupInput.focus();
        });
      });
    };

    const closePopup = () => {
      popup.classList.remove("is-visible");
      popup.classList.remove("is-loading");

      // Wait for transition to finish, then hide
      const onEnd = () => {
        popup.classList.remove("is-open");
        popup.removeEventListener("transitionend", onEnd);
        document.body.style.overflow = "";

        // Clear state
        popupInput.value = "";
        popupResults.innerHTML = "";
      };

      popup.addEventListener("transitionend", onEnd);

      // Fallback if transitionend doesn't fire
      setTimeout(onEnd, 400);
    };

    mobileSearchBtn.addEventListener("click", openPopup);
    popupClose.addEventListener("click", closePopup);
    popupOverlay.addEventListener("click", closePopup);

    // Close on ESC
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && popup.classList.contains("is-open")) {
        closePopup();
      }
    });

    // Search input handler
    popupInput.addEventListener("input", (e) => {
      const query = e.target.value.trim();
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(
        () => performSearch(query, popupResults, popup),
        300
      );
    });
  }
};

export default initSearch;
