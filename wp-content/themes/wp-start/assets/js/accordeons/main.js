export class Accordeon {
  constructor(selector = "", params = {}) {
    if (!selector) return console.error("No selector provided");
    if (!document.querySelector(selector))
      return console.error("No element found with selector: " + selector);
    if (document.querySelectorAll(selector).length > 1) {
      document.querySelectorAll(selector).forEach((el, i) => {
        new Accordeon(`${selector}:nth-child(${i + 1})`, params);
      });
    } else {
      this.content = params.content || ".content";
      this.triggerElements = params.triggerElements || ".heading";
      this.selector = selector;
      this.element = document.querySelector(this.selector);
      this.on = params.on;
      this.transitionEnd = {
        cb: function () {},
        prop: "height",
        binder: this.transitionHandler.bind(this),
      };
      this.init();
    }
  }
  init() {
    this.updateHeight();
    window.addEventListener("resize", this.updateHeight.bind(this));
    this.element
      .querySelector(this.triggerElements)
      ?.addEventListener("click", this.toggle.bind(this));
    this.on?.init?.bind(this)();
  }
  open() {
    this.element.removeAttribute("autoHeight");
    this.element.removeAttribute("close");
    this.element.setAttribute("open", "");
    this.onTransitionEnd(function (e) {
      e.element.setAttribute("autoHeight", "");
    });
    this.on?.open?.bind(this)();
  }
  close() {
    this.element.removeAttribute("autoHeight");
    this.element.removeAttribute("close");
    const reflow = this.element.offsetHeight; // trigger reflow
    this.element.removeAttribute("open");
    this.element.setAttribute("close", "");
    this.onTransitionEnd(function (e) {
      e.element.removeAttribute("close");
    });
    this.on?.close?.bind(this)();
  }
  toggle() {
    this.element.hasAttribute("open") ? this.close() : this.open();
    this.on?.toggle?.bind(this)();
  }
  onTransitionEnd(cb = function () {}, prop = "height") {
    console.log("onTransitionEnd");
    this.element.removeEventListener(
      "transitionend",
      this.transitionEnd.binder
    );
    this.element.addEventListener("transitionend", this.transitionEnd.binder);
    this.transitionEnd.cb = cb;
  }
  transitionHandler(e) {
    if (e.propertyName === this.transitionEnd.prop) {
      this.transitionEnd.cb?.(this);
      this.element.removeEventListener(
        "transitionend",
        this.transitionEnd.binder
      );
      this.on?.transitionEnd?.bind(this)();
    }
  }
  updateHeight() {
    const height = this.element.querySelector(this.content).scrollHeight + "px";
    this.element.style.setProperty("--_contentHeight", height);
    this.setDuration();
    this.on?.updateHeight?.bind(this)();
  }
  setDuration() {
    const duration =
      this.element.querySelector(this.content).scrollHeight /
        getComputedStyle(this.element).getPropertyValue("--_pps") +
      "s";
    this.element.style.setProperty("--_ts", duration);
  }
}
