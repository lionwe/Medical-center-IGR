import { load } from "../../events/load";

class CtaFormBlock {
  constructor(container, successClass, wrapperDisplayType) {
    this.container = container;
    this.formWrapper = container.querySelector(".cta__window") || container;
    this.form = container.querySelector("form");

    this.successClass = successClass;
    this.wrapperDisplayType = wrapperDisplayType;
    this.submitBtn = this.form?.querySelector('button[type="submit"]');

    // Надійний селектор тексту всередині вашої кнопки btn-split
    this.submitBtnTextNode =
      this.submitBtn?.querySelector(".btn__text") ||
      this.submitBtn?.querySelector(".btn-split__text") ||
      this.submitBtn;

    this.originalBtnText =
      this.submitBtnTextNode?.textContent?.trim() || "Надіслати";
    this.loadingTimeout = null;
    this.resetSuccessTimeout = null;
    this.successView = null;

    if (!this.formWrapper || !this.form) {
      return;
    }

    this.init();
  }

  init() {
    this.createSuccessView();
    this.initPhoneMask();
    this.initValidation();
    this.initSubmitHandler();
    this.initSuccessHandler();
  }

  createSuccessView() {
    this.successView = document.createElement("div");
    this.successView.className = this.successClass;
    this.successView.style.display = "none";
    this.successView.innerHTML = `
            <div class="${this.successClass}__content">
                <h3 class="${this.successClass}__title">ДЯКУЄМО! ВАШУ ЗАЯВКУ <br> УСПІШНО НАДІСЛАНО</h3>
                <span class="${this.successClass}__text">Ми з вами зв'яжемось!</span>
            </div>
        `;

    this.formWrapper.appendChild(this.successView);
  }

  initPhoneMask() {
    const phoneInput = this.form.querySelector(
      'input[type="tel"], input[name*="phone"], input[name*="tel"]'
    );
    if (!phoneInput) return;

    phoneInput.addEventListener("input", (event) => {
      const input = event.target;
      let value = input.value.replace(/\D/g, "");
      if (!value) {
        input.value = "";
        return;
      }
      if (!value.startsWith("38")) value = `38${value}`;
      value = value.substring(0, 12);
      let formatted = "+";
      if (value.length > 0) formatted += value.substring(0, 2);
      if (value.length > 2) formatted += ` (${value.substring(2, 5)}`;
      if (value.length > 5) formatted += `) ${value.substring(5, 8)}`;
      if (value.length > 8) formatted += `-${value.substring(8, 10)}`;
      if (value.length > 10) formatted += `-${value.substring(10, 12)}`;
      input.value = formatted;
    });
  }

  initValidation() {
    this.form.querySelectorAll("input, textarea").forEach((input) => {
      input.addEventListener("input", () => {
        input.classList.remove("is-invalid");
        input.style.borderColor = "";
      });
    });
  }

  initSubmitHandler() {
    this.form.addEventListener("submit", (event) => {
      if (!this.validateForm()) {
        event.preventDefault();
        event.stopImmediatePropagation();
        return;
      }
      this.setLoadingState(true);
      clearTimeout(this.loadingTimeout);
      this.loadingTimeout = setTimeout(
        () => this.setLoadingState(false),
        10000
      );
    });
  }

  initSuccessHandler() {
    document.addEventListener("reintegrationFormSubmitted", (event) => {
      const submittedForm = event?.detail?.form;
      if (!submittedForm || !this.container.contains(submittedForm)) return;
      clearTimeout(this.loadingTimeout);
      this.setLoadingState(false);
      this.showSuccessView();
      clearTimeout(this.resetSuccessTimeout);
      this.resetSuccessTimeout = setTimeout(() => this.hideSuccessView(), 5000);
    });
  }

  validateForm() {
    const requiredInputs = this.form.querySelectorAll("input[required]");
    let isValid = true;
    requiredInputs.forEach((input) => {
      const value = input.value.trim();
      if (!value) {
        isValid = false;
        input.classList.add("is-invalid");
        input.style.borderColor = "red";
      }
    });
    return isValid;
  }

  setLoadingState(isLoading) {
    if (!this.submitBtn || !this.submitBtnTextNode) return;
    if (isLoading) {
      this.submitBtnTextNode.textContent = "Надсилання...";
      this.submitBtn.disabled = true;
      this.submitBtn.style.opacity = "0.7";
      return;
    }
    this.submitBtnTextNode.textContent = this.originalBtnText;
    this.submitBtn.disabled = false;
    this.submitBtn.style.opacity = "";
  }

  showSuccessView() {
    if (!this.successView) return;
    const currentHeight = this.formWrapper.offsetHeight;
    this.formWrapper.style.minHeight = `${currentHeight}px`;

    // Приховуємо все всередині вікна крім подяки
    Array.from(this.formWrapper.children).forEach((child) => {
      if (child !== this.successView) child.style.display = "none";
    });

    this.successView.style.display = "flex";
    this.formWrapper.style.display = "flex";
    this.formWrapper.style.flexDirection = "column";
    this.formWrapper.style.justifyContent = "center";
    this.formWrapper.style.alignItems = "center";
  }

  hideSuccessView() {
    if (!this.successView) return;
    this.successView.style.display = "none";
    Array.from(this.formWrapper.children).forEach((child) => {
      if (child !== this.successView) child.style.display = "";
    });
    this.formWrapper.style.minHeight = "";
    this.formWrapper.style.display = "";
  }
}

const initCtaForm = () => {
  document.querySelectorAll(".cta").forEach((container) => {
    new CtaFormBlock(container, "cta__form-success", "block");
  });
};

load(initCtaForm);
export default initCtaForm;
