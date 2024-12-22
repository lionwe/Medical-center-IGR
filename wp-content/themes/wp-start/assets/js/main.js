import "./utils";
const pageTemplate = params.page_template?.replace(/^.*\/([^/]+)\.php$/, "$1");

// Перевіряємо, чи є елемент з класом 'swiper' на сторінці
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
import "./events/main";

import "../css/main.scss";
