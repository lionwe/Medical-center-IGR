import { clickOn } from "./events/click";
import { getProp } from "./events/properties";
clickOn("[class*='popup-'], [class*='popup-'] > *", (e) => {
  const popup_name = Array.from(e.target.closest("[class*='popup-']").classList)
    .filter((className) => className.startsWith("popup-"))[0]
    .split("popup-")[1];
  let backdropElement = document.querySelector(`.backdrop.${popup_name}`),
    dataForEvent = {
      popup: backdropElement,
      button: e.target.closest("[class*='popup-']"),
      name: popup_name,
      selector: `.${popup_name}.backdrop`,
    },
    closeFunction = () => {
      document.dispatchEvent(
        new CustomEvent("popupClose", { detail: dataForEvent })
      );
      backdropElement?.classList.remove("show");
      backdropElement?.classList.add("close");
      backdropElement
        ?.querySelector(".close-button")
        ?.removeEventListener("click", closeFunction);
      window.removeEventListener("click", clickOutsideFunction);
      document.documentElement.classList.add("interaction-disabled");
      setTimeout(() => {
        backdropElement?.classList.remove("close");
        document.documentElement.classList.remove("interaction-disabled");
      }, parseInt(getProp(backdropElement, "--_show-speed", 500)));
    },
    clickOutsideFunction = (event) => {
      if (event.target == backdropElement) {
        closeFunction();
      }
    };

  if (backdropElement) {
    backdropElement.classList.add("show");
    document.dispatchEvent(
      new CustomEvent("popupOpen", { detail: dataForEvent })
    );
    backdropElement
      .querySelector(".close-button")
      ?.removeEventListener("click", closeFunction);
    window.removeEventListener("click", clickOutsideFunction);
    setTimeout(() => {
      backdropElement
        .querySelector(".close-button")
        ?.addEventListener("click", closeFunction);
      window.addEventListener("click", clickOutsideFunction);
    }, parseInt(getProp(backdropElement, "--_show-speed", 500)));
  }
});
