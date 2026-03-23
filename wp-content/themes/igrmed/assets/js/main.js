import "./utils";
import "./components/home/main";
import "./components/infertility-treatment-women/main";
import "./components/diagnostics-research/main";
import "./components/single-doctor/main";
import "./components/archive-blog/main";
import "./components/single-blog/main";
import "./components/surrogate-motherhood/main";
import "./ui/main";


import "./swipers/main";
import "./swipers/pregnancy-management/main";
import "./components/forms/main";
if (document.querySelector(".backdrop")) {
  import("./popups/main").catch((error) => {
    console.error("Failed to load Popups module:", error);
  });
}

import "../css/main.scss";
