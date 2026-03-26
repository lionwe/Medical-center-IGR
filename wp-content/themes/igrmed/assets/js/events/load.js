export function load(callback) {
  if (document.readyState === "complete" || document.readyState === "interactive") {
    callback();
  } else {
    list.push(callback);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  list.forEach((callback) => callback());
});

const list = [];

window.load = load;
