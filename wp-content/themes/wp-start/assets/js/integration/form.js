import { isPhoneValid, isNameValid, setError } from "./validation";

const form = document.querySelector("#cta form");
form.removeAttribute("loading");
form.querySelector("button").removeAttribute("disabled");

form.addEventListener("submit", submit);

function submit(e) {
  e.preventDefault();
  const formData = new FormData(form);
  const data = Object.fromEntries(formData.entries());

  isPhoneValid(data.phone, null, () => {
    setError(form.querySelector('input[type="tel"]'));
  });
  isNameValid(data.name, null, () => {
    setError(form.querySelector('input[name="name"]'));
  });

  if (form.querySelector("[error]")) return;
  form.setAttribute("loading", "");
  send(data);
}

function resetForm() {
  form.reset();
}
async function send(data) {
  const { createMessageForTelegtam, telegram } = await import(
    "./telegram"
  ).catch((e) => {
    console.error(e);
    return {};
  });
  data["Час"] = new Date().toLocaleString();
  telegram(
    {
      id: "",
      token: "",
    },
    createMessageForTelegtam(data),
    async () => {
      const { createMessageForSheets, sheets } = await import("./sheets").catch(
        (e) => {
          console.error(e);
          return {};
        }
      );

      sheets(createMessageForSheets(data));
      form.removeAttribute("loading");
      form.parentElement?.querySelector(".thank-you")?.setAttribute("show", "");
      resetForm();
    },
    (e) => {
      console.error(e);
      form.removeAttribute("loading");
    }
  );
}
