import { load } from "../../events/load";

class ContactsFormBlock {
    constructor(container, successClass, wrapperDisplayType) {
        this.container = container;
        this.formWrapper = container.querySelector(".contacts-info__form");
        this.form = container.querySelector(".reintegration-form form") || container.querySelector("form");
        this.successClass = successClass;
        this.wrapperDisplayType = wrapperDisplayType;
        this.submitBtn = this.form?.querySelector('button[type="submit"]');
        this.submitBtnText = this.submitBtn?.querySelector(".btn__text");
        this.originalBtnText = this.submitBtnText?.textContent?.trim() || "Надіслати";
        this.loadingTimeout = null;
        this.resetSuccessTimeout = null;
        this.successView = null;

        if (!this.formWrapper || !this.form) {
            return;
        }

        this.init();
    }

    init() {
        this.ensureRequiredFields();
        this.createSuccessView();
        this.initPhoneMask();
        this.initValidation();
        this.initSubmitHandler();
        this.initSuccessHandler();
    }

    ensureRequiredFields() { }

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

        this.formWrapper.parentNode.insertBefore(this.successView, this.formWrapper.nextSibling);
    }

    initPhoneMask() {
        const inputs = Array.from(this.form.querySelectorAll('.contacts-form__input, input.wpcf7-text, input.wpcf7-tel, input[type="text"], input[type="tel"]'));

        let phoneInput = inputs.find(input =>
            input.type === 'tel' ||
            (input.id && input.id.toLowerCase().includes('phone')) ||
            (input.name && input.name.toLowerCase().includes('phone')) ||
            (input.name && input.name.toLowerCase().includes('tel')) ||
            (input.name && input.name.toLowerCase().includes('телефон')) ||
            (input.placeholder && input.placeholder.toLowerCase().includes('телефон'))
        );

        // Fallback: If not found, assume it's the 3rd input (Name, Surname, Phone)
        if (!phoneInput && inputs.length >= 3) {
            phoneInput = inputs[2];
        }

        if (!phoneInput) {
            console.log("Contacts Form JS: Phone input not found");
            return;
        }

        // Store it on the class so validateForm can reliably use it
        this.phoneInputNode = phoneInput;

        phoneInput.addEventListener("input", (event) => {
            const input = event.target;
            let value = input.value.replace(/\D/g, "");

            if (!value) {
                input.value = "";
                return;
            }

            if (!value.startsWith("38")) {
                value = `38${value}`;
            }

            value = value.substring(0, 12);

            let formatted = "+";
            if (value.length > 0) formatted += value.substring(0, 2);
            if (value.length > 2) formatted += ` (${value.substring(2, 5)}`;
            if (value.length > 5) formatted += `) ${value.substring(5, 8)}`;
            if (value.length > 8) formatted += `-${value.substring(8, 10)}`;
            if (value.length > 10) formatted += `-${value.substring(10, 12)}`;

            input.value = formatted;
        });

        phoneInput.addEventListener("keydown", (event) => {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            if (!input.value.trim()) {
                return;
            }

            if (cursorPosition <= 4 && (event.key === "Backspace" || event.key === "Delete")) {
                event.preventDefault();
            }
        });

        phoneInput.addEventListener("focus", (event) => {
            const input = event.target;
            setTimeout(() => {
                const valueLength = input.value.length;
                input.setSelectionRange(valueLength, valueLength);
            }, 0);
        });
    }

    initValidation() {
        const inputs = this.form.querySelectorAll(".contacts-form__input, input, textarea");
        inputs.forEach((input) => {
            input.addEventListener("input", () => {
                input.classList.remove("is-invalid");
                this.removeErrorStyling(input);
            });
        });
    }

    removeErrorStyling(input) {
        input.style.borderColor = "";
        input.style.backgroundColor = "";
    }

    applyErrorStyling(input) {
        input.style.borderColor = "red";
        input.style.backgroundColor = "rgba(255, 0, 0, 0.1)";
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
            this.loadingTimeout = setTimeout(() => {
                this.setLoadingState(false);
            }, 5000);
        });
    }

    initSuccessHandler() {
        document.addEventListener("reintegrationFormSubmitted", (event) => {
            const submittedForm = event?.detail?.form;
            const response = event?.detail?.data;

            if (!submittedForm || !this.container.contains(submittedForm)) {
                return;
            }

            clearTimeout(this.loadingTimeout);
            this.setLoadingState(false);

            if (!response?.success && !response?.data?.success) {
                return;
            }

            this.resetForm();
            this.showSuccessView();

            clearTimeout(this.resetSuccessTimeout);
            this.resetSuccessTimeout = setTimeout(() => {
                this.hideSuccessView();
            }, 5000);
        });
    }

    validateForm() {
        const requiredInputs = this.form.querySelectorAll("input[required], textarea[required], select[required]");
        let isValid = true;
        let firstInvalidInput = null;

        requiredInputs.forEach((input) => {
            input.classList.remove("is-invalid");
            this.removeErrorStyling(input);
            const value = input.value.trim();

            if (input === this.phoneInputNode || input.type === "tel" || input.name?.toLowerCase().includes("phone")) {
                const digits = value.replace(/\D/g, "");
                if (digits.length !== 12) {
                    isValid = false;
                    input.classList.add("is-invalid");
                    this.applyErrorStyling(input);
                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                }
                return;
            }

            if (!value) {
                isValid = false;
                input.classList.add("is-invalid");
                this.applyErrorStyling(input);
                if (!firstInvalidInput) {
                    firstInvalidInput = input;
                }
            }
        });

        if (!isValid && firstInvalidInput) {
            firstInvalidInput.focus();
        }

        return isValid;
    }

    setLoadingState(isLoading) {
        if (!this.submitBtn || !this.submitBtnText) {
            return;
        }

        if (isLoading) {
            this.submitBtnText.textContent = "Надсилання...";
            this.submitBtn.disabled = true;
            this.submitBtn.style.opacity = "0.7";
            this.submitBtn.style.pointerEvents = "none";
            return;
        }

        this.submitBtnText.textContent = this.originalBtnText;
        this.submitBtn.disabled = false;
        this.submitBtn.style.opacity = "";
        this.submitBtn.style.pointerEvents = "";
    }

    resetForm() {
        const inputs = this.form.querySelectorAll(".contacts-form__input, input, textarea");
        inputs.forEach((input) => {
            if (input.type !== "hidden" && input.type !== "submit" && input.type !== "button") {
                input.value = "";
            }
            input.classList.remove("is-invalid");
            this.removeErrorStyling(input);
        });

        this.setLoadingState(false);
    }

    showSuccessView() {
        if (!this.successView) {
            return;
        }

        this.formWrapper.style.display = "none";
        this.successView.style.display = "flex";

        const formIntro = this.container.querySelector('.contacts-info__form-intro');
        if (formIntro) {
            formIntro.style.display = 'none';
        }
    }

    hideSuccessView() {
        if (!this.successView) {
            return;
        }

        this.successView.style.display = "none";
        this.formWrapper.style.display = this.wrapperDisplayType;

        const formIntro = this.container.querySelector('.contacts-info__form-intro');
        if (formIntro) {
            formIntro.style.display = '';
        }
    }
}

const initContactsForm = () => {
    document.querySelectorAll(".contacts-info__right").forEach((container) => {
        new ContactsFormBlock(container, "contacts-form__success", "block");
    });
};

load(initContactsForm);

export default initContactsForm;
