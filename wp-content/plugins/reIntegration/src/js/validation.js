export class Form {
  constructor(form) {
    this.form = form;
    this.init();
  }
  init() {
    this.form.querySelectorAll("input").forEach((input) => {
      input.addEventListener("input", handleInput);
    });
  }
  validate() {
    if (!this.form) {
      console.error("Form is not defined");
      return false;
    }
    this.valid = true;

    const requiredFields = this.form.querySelectorAll("[required]");
    requiredFields.forEach((field) => {
      if (field.value.trim() === "") {
        field.setAttribute("aria-invalid", "true");
        this.valid = false;
      } else {
        field.removeAttribute("aria-invalid");
      }
      const minLength = field.dataset.minLength;
      if (minLength) {
        let input = field.value.trim();
        if (field.name == "phone") {
          input = input.replace(/[^0-9]/g, "");
        }
        if (input && input.length < minLength) {
          field.setAttribute("aria-invalid", "true");
          this.valid = false;
        }
      }
    });
    return this.valid;
  }
}

function handleInput({ target: input }) {
  input.removeAttribute("aria-invalid");
}
