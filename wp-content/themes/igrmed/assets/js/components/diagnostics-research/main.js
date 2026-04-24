if (document.querySelector(".diagnostics-research-diagnostics")) {
  import("./diagnostics-toggle").catch((error) => {
    console.error("Failed to load diagnostics toggle module:", error);
  });
}
