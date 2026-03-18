import "./utils";
import "./components/home/main";
import "./components/diagnostics-research/diagnostics-toggle";
import "./ui/main";

import "./swipers/main";
import "./components/forms/main";
if (document.querySelector(".backdrop")) {
  import("./popups/main").catch((error) => {
    console.error("Failed to load Popups module:", error);
  });
}

import "../css/main.scss";
