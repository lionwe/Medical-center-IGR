if (document.querySelector(".diagnostics-research-diagnostics")) {
  import("./diagnostics-toggle").catch((error) => {
    console.error("Failed to load diagnostics toggle module:", error);
  });
}
function initDiagnosticsToggle() {
  const toggles = document.querySelectorAll(".js-diagnostics-toggle");

  if (!toggles.length) return;

  toggles.forEach((toggle) => {
    const buttons = toggle.querySelectorAll(".diagnostics-research-diagnostics__button");

    if (!buttons.length) return;

    buttons.forEach((button) => {
      button.addEventListener("click", () => {
        const target = button.dataset.toggle;

        buttons.forEach((item) => item.classList.remove("is-active"));
        button.classList.add("is-active");

        toggle.classList.toggle("is-men", target === "men");
      });
    });
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initDiagnosticsToggle);
} else {
  initDiagnosticsToggle();
}
