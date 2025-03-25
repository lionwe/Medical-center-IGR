import IMask from "imask";

document.querySelectorAll('input[type="tel"]').forEach((input) => {
  const mask = IMask(input, {
    mask: "+{380} (00) 000-00-00",
  });

  input?.addEventListener("input", () => {
    if (
      mask.unmaskedValue.slice(0, 3)[2] !== "0" &&
      mask.unmaskedValue.slice(0, 3)[2]
    ) {
      const newInput = mask.unmaskedValue.slice(2);
      mask.unmaskedValue = "380" + newInput;
    }
    if (mask.unmaskedValue.length == 12) {
      setValid(input);
    }
  });
});
document.querySelectorAll('input[name="name"]').forEach((input) => {
  input.addEventListener("input", ({ target, data }) => {
    if (/\d/.test(data)) return setError(target);
    isNameValid(target.value, () => {
      setValid(target);
    });
  });
});

const isPhoneValid = (phone, valid = undefined, invalid = undefined) => {
  const cleanedPhone = phone?.replace(/[\s()-]/g, "");
  const regex = /^\+380\d{9}$/;
  if (regex.test(cleanedPhone)) {
    valid?.();
  } else {
    invalid?.();
  }
};
const isNameValid = (name, valid = undefined, invalid = undefined) => {
  if (name?.length < 3) {
    invalid?.();
  }
  const regex = /^[a-zA-Zа-яА-ЯіїєґІЇЄҐ\s]+$/;
  if (regex.test(name)) {
    valid?.();
  } else {
    invalid?.();
  }
};
const setError = (el) => {
  el.setAttribute("error", "");
};
const setValid = (el) => {
  el.removeAttribute("error");
};

function getUTMs() {
  const UTM_KEYS = [
    "utm_source",
    "utm_medium",
    "utm_campaign",
    "utm_term",
    "utm_content",
  ];
  const urlParams = new URLSearchParams(window.location.search);
  const storedParams = JSON.parse(sessionStorage.getItem("utm_params")) || {};

  UTM_KEYS.forEach((key) => {
    if (urlParams.has(key)) {
      storedParams[key] = urlParams.get(key);
      urlParams.delete(key);
    }
  });

  sessionStorage.setItem("utm_params", JSON.stringify(storedParams));

  const newUrl = `${window.location.origin}${window.location.pathname}${
    urlParams.toString() ? "?" + urlParams.toString() : ""
  }`;
  window.history.replaceState({}, document.title, newUrl);

  return Object.entries(storedParams);
}

getUTMs();

export { isPhoneValid, isNameValid, setError, getUTMs };
