export function clickOn(element, callback) {
    if (typeof element === "object") {
        element.forEach((selector) => {
            list[selector] = callback;
        });
    } else {
        list[element] = callback;
    }
}

document.addEventListener("click", (event) => {
    Object.keys(list).forEach((selector) => {
        const target = event.target.closest(selector);
        if (target) {
            list[selector](event, target);
        }
    });
});

const list = {};
