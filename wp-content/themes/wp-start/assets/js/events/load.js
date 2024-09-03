export function load(callback) {
	list.push(callback);
}
document.addEventListener("DOMContentLoaded", () => {
	list.forEach((callback) => callback());
})
const list = []; 