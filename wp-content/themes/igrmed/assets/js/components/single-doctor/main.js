import DoctorTabs from "./hero";

const init = () => new DoctorTabs();

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", init);
} else {
  init();
}
