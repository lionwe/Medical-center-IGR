import "./utils";
import "./components/home/main";
import "./mobile-menu";

import "./swipers/main";
if (document.querySelector(".backdrop")) {
  import("./popups/main").catch((error) => {
    console.error("Failed to load Popups module:", error);
  });
}

import "../css/main.scss";
