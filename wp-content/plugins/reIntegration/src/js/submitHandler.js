import { Form } from "./validation.js";
const forms = [...document.querySelectorAll(".reintegration-form form")].map(
  (form) => {
    return {
      form: form,
      validation: new Form(form),
    };
  }
);
forms.forEach(({ form }) => {
  form.noValidate = true;
  form.addEventListener("submit", handleFormSubmit);
});

function handleFormSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const valid = forms.find((e) => e.form == form)?.validation?.validate();
  console.log(valid);
  if (!valid) return;

  const rawFormData = new FormData(form);
  const formObject = Object.fromEntries(rawFormData.entries());

  // Add utm parameters if they exist
  const utmParams = [
    "utm_source",
    "utm_medium",
    "utm_campaign",
    "utm_term",
    "utm_content",
  ];
  utmParams.forEach((param) => {
    const value = new URLSearchParams(window.location.search).get(param);
    if (value) {
      formObject[param] = value;
      localStorage.setItem(param, value);
    } else if (localStorage.getItem(param)) {
      formObject[param] = localStorage.getItem(param);
    }
  });

  const formData = new FormData();
  formData.append("form_data", JSON.stringify(formObject));
  formData.append("action", "reintegration_send_form");
  formData.append("nonce", reintegration_ajax.nonce);

  fetch(reintegration_ajax.ajax_url, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      console.log("Response received:", response);
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Form submitted successfully:", data);
      document.dispatchEvent(
        new CustomEvent("reintegrationFormSubmitted", {
          detail: {
            form: form,
            data: data,
          },
        })
      );
    })
    .catch((error) => {
      console.error("Error submitting form:", error);
    });
}

const searchParams = new URLSearchParams(window.location.search);
const UTMs = [
  "utm_source",
  "utm_medium",
  "utm_campaign",
  "utm_term",
  "utm_content",
];
UTMs.forEach((utm) => {
  const value = searchParams.get(utm);
  if (value) {
    localStorage.setItem(utm, value);
  }
});
