import { getUTMs } from "./validation.js";
export function sheets(data) {
  const url = "";
  fetch(url, {
    method: "POST",
    mode: "no-cors",
    body: JSON.stringify(data),
    headers: {
      "Content-Type": "application/json",
    },
  }).catch((er) => {
    console.error(er);
  });
}
export function createMessageForSheets(data) {
  const entries = Object.entries(data);
  entries.push(...getUTMs());
  return entries.reduce(
    (acc, [key, value]) => {
      if (!value) return acc;
      const dict = {
        name: "Ім'я",
        surname: "Прізвище",
        phone: "Телефон",
        message: "Повідомлення",
        email: "Пошта",
      };
      if (key === "phone") value = value.replace(/\D/, "");
      if (dict[key]) {
        acc[dict[key]] = value;
      } else {
        acc[key] = value;
      }
      return acc;
    },
    {
      Час: new Date().toLocaleString("uk-UA", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      }),
    }
  );
}
