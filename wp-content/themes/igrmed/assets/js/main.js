/**
 * Main Entry Point
 */

// Critical CSS
import "../css/main.scss";

// Immediate Utilities & UI
import "./utils";
import "./events/load"; // Import load helper early to sync state
import "./ui/main";

/**
 * Lazy Loading Components
 * Verified selectors against PHP templates to ensure stable dynamic imports.
 */
const components = [
  {
    selector: "#home",
    loader: () => import(/* webpackChunkName: "comp-home" */ "./components/home/main"),
  },
  {
    selector: "#infertility-treatment-women",
    loader: () =>
      import(
        /* webpackChunkName: "comp-infertility" */ "./components/infertility-treatment-women/main"
      ),
  },
  {
    selector: "#diagnostics-research",
    loader: () =>
      import(
        /* webpackChunkName: "comp-diagnostics" */ "./components/diagnostics-research/main"
      ),
  },
  {
    selector: "#single-doctor",
    loader: () =>
      import(/* webpackChunkName: "comp-doctor" */ "./components/single-doctor/main"),
  },
  {
    selector: "#archive-blog",
    loader: () =>
      import(/* webpackChunkName: "comp-archive-blog" */ "./components/archive-blog/main"),
  },
  {
    selector: "#single-blog",
    loader: () =>
      import(/* webpackChunkName: "comp-single-blog" */ "./components/single-blog/main"),
  },
  {
    selector: "#surrogate-motherhood",
    loader: () =>
      import(
        /* webpackChunkName: "comp-surrogate" */ "./components/surrogate-motherhood/main"
      ),
  },
  {
    selector: "#price-list, .price-page",
    loader: () => import(/* webpackChunkName: "comp-price" */ "./components/price/price"),
  },
  {
    selector: "#privacy-policy, .privacy-policy, .privacy-policy-page",
    loader: () =>
      import(
        /* webpackChunkName: "comp-privacy" */ "./components/privacy-policy/privacy-policy"
      ),
  },
  {
    // Common selectors for forms (Footer, Contacts, etc.)
    selector: ".footer__form-block, .contacts-info__right, .reintegration-form",
    loader: () => import(/* webpackChunkName: "comp-forms" */ "./components/forms/main"),
  },
  {
    // CTA section (used on many pages)
    selector: ".cta",
    loader: () => import(/* webpackChunkName: "comp-cta" */ "./components/home/cta"),
  },
  {
    selector: ".backdrop",
    loader: () => import(/* webpackChunkName: "comp-popups" */ "./popups/main"),
  },
  {
    selector: ".swiper, .pregnancy-doctors, .advantages-preg, .licenses-certificates",
    loader: () => import(/* webpackChunkName: "comp-swipers" */ "./swipers/main"),
  },
  {
    selector: ".pregnancy-management-swiper, #pregnancy-management",
    loader: () =>
      import(
        /* webpackChunkName: "comp-pregnancy-swiper" */ "./swipers/pregnancy-management/main"
      ),
  },
];

components.forEach(({ selector, loader }) => {
  if (document.querySelector(selector)) {
    loader().catch((err) => {
      console.error(`Failed to load module for selector: ${selector}`, err);
    });
  }
});

// Polyfill check for DOMContentLoaded if script loaded late
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    // Initializers already started above
  });
}
