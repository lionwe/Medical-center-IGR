export function clickOn(element, callback) {
	if(typeof element === "object"){
		element.forEach(selector => {
			list[selector] = callback;
		});
	}else{
		list[element] = callback;
	}
}
document.addEventListener("click", (event) => {
	Object.keys(list).forEach((selector) => {
		if (event.target.matches(selector)) {
			list[selector](event);
		}
	});
})
const list = {
}

clickOn("#results .result, #results .result *", (event) => {
	event.target.closest('#results .result')?.classList.toggle("click");
})



