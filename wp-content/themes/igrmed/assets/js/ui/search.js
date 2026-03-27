/**
 * Live Search Module
 */

const initSearch = () => {
  const searchContainer = document.querySelector("#header-search");
  if (!searchContainer) return;

  const input = searchContainer.querySelector(".header__search-input");
  const resultsContainer = searchContainer.querySelector(".header__search-results");
  const closeBtn = searchContainer.querySelector(".header__search-icon-wrapper--close");
  const mobileSearchBtn = document.querySelector(".header__search-btn-bar");

  let debounceTimer;

  const performSearch = (query) => {
    if (query.length < 2) {
      resultsContainer.innerHTML = "";
      resultsContainer.classList.remove("is-visible");
      searchContainer.classList.remove("is-searching");
      return;
    }

    searchContainer.classList.add("is-loading");
    searchContainer.classList.add("is-searching");

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
        searchContainer.classList.remove("is-loading");

        if (data.success && data.data && data.data.html) {
          resultsContainer.innerHTML = data.data.html;
          resultsContainer.classList.add("is-visible");
        } else {
          resultsContainer.innerHTML = "";
          resultsContainer.classList.remove("is-visible");
        }
      })
      .catch((error) => {
        console.error("Search error:", error);
        searchContainer.classList.remove("is-loading");
        searchContainer.classList.remove("is-searching");
      });
  };

  input.addEventListener("input", (e) => {
    const query = e.target.value.trim();
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => performSearch(query), 300);
  });

  // Clear search
  const clearSearch = () => {
    input.value = "";
    resultsContainer.innerHTML = "";
    resultsContainer.classList.remove("is-visible");
    searchContainer.classList.remove("is-searching");
    searchContainer.classList.remove("is-loading");
  };

  closeBtn.addEventListener("click", clearSearch);

  // Close results on ESC
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      resultsContainer.classList.remove("is-visible");
    }
  });

  // Show results on focus if there's content
  input.addEventListener("focus", () => {
    if (resultsContainer.innerHTML.trim() !== "") {
      resultsContainer.classList.add("is-visible");
    }
  });

  // Close results on click outside
  document.addEventListener("click", (e) => {
    if (!searchContainer.contains(e.target)) {
      resultsContainer.classList.remove("is-visible");
    }
  });

  // Hide results on scroll (User requirement 1)
  window.addEventListener("scroll", () => {
    if (resultsContainer.classList.contains("is-visible")) {
      resultsContainer.classList.remove("is-visible");
    }
  }, { passive: true });

  // Mobile search button logic
  if (mobileSearchBtn) {
    mobileSearchBtn.addEventListener("click", () => {
      // If header is hidden or menu is closed, we might need to open it
      // But for now, let's just focus the input if it's visible
      // Or if it's in the mobile menu (which it isn't yet)

      // If we want to support mobile search, we should probably toggle a search overlay
      // For now, let's just focus the desktop input if it's accessible
      input.focus();
    });
  }
};

export default initSearch;
