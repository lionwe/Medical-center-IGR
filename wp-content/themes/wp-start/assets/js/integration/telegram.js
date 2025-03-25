import { getUTMs } from "./validation.js";
export function telegram(
  { id, token } = {},
  message = "",
  sexes = undefined,
  error = undefined
) {
  if (!message || !id || !token) return;

  fetch(`https://api.telegram.org/bot${token}/sendMessage`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      chat_id: id,
      text: message,
      parse_mode: "HTML",
    }),
  })
    .then((response) => response.json())
    .then((response) => {
      if (response.ok) {
        sexes?.(response);
      } else {
        error?.(response);
      }
    });
}
export function createMessageForTelegtam(data) {
  const entries = Object.entries(data);
  entries.push(...getUTMs());
  return entries.reduce((acc, [key, value]) => {
    if (!value) return acc; // Повертаємо вже накопичений результат
    const dict = {
      name: "Ім'я",
      surname: "Прізвище",
      phone: "Телефон",
      message: "Повідомлення",
      email: "Пошта",
    };
    return acc + `<b>${dict[key] || key}:</b> ${value}\n`;
  }, "");
}
