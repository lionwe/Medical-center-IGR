import "./utils";

if (document.querySelector(".swiper")) {
  import("./swipers/main").catch((error) => {
    console.error("Failed to load Swiper module:", error);
  });
}
if (document.querySelector(".backdrop")) {
  import("./popups/main").catch((error) => {
    console.error("Failed to load Popups module:", error);
  });
}
if (document.querySelector("form")) {
  import("./integration").catch((error) => {
    console.error("Failed to load Forms module:", error);
  });
}

import "../css/main.scss";
