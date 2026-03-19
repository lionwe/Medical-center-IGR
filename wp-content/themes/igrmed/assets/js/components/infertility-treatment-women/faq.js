import { clickOn } from "../../events/click";

clickOn(".js-faq-trigger", (event, target) => {
  const header = target || event.target.closest(".js-faq-trigger");
  if (!header) return;

  const item = header.closest(".js-faq-item");
  if (!item) return;

  const content = item.querySelector(".accordeon");
  if (!content) return;

  const isOpen = content.hasAttribute("open");

  if (isOpen) {
    content.removeAttribute("open");
    content.classList.remove("is-open");
    item.classList.remove("is-active");
    header.setAttribute("aria-expanded", "false");
    return;
  }

  content.setAttribute("open", "");
  content.classList.add("is-open");
  item.classList.add("is-active");
  header.setAttribute("aria-expanded", "true");
});
