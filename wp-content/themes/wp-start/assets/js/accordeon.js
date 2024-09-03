function get_buttons(name, data_name) {
  return document.querySelectorAll(
    `.accordeon-${name}[data-name="${data_name}"]`
  );
}
function get_accordeon(name, data_name) {
  return document.querySelector(`.accordeon.${name}[data-name="${data_name}"]`);
}
function openAccordeon(name, data_name) {
  const a = {
    a: get_accordeon(name, data_name),
    b: get_buttons(name, data_name),
  };
  a.a.classList.add("open");
  [...a.b].forEach((button) => {
    button.classList.add("active");
  });
}
function closeAccordeons(name, element = document){
	element.querySelectorAll(`.accordeon.${name}`).forEach(accordeon => {
		accordeon.classList.remove("open", "autoHeight");
		const data_name = accordeon.dataset.name;
		document.querySelectorAll(`.accordeon-${name}[data-name="${data_name}"]`).forEach(button => {
			button.classList.remove("active");
		})
	})
	element.querySelectorAll(`.accordeon-${name}`).forEach(button => {
		button.classList.remove("active");
	})
}
function toggleAccordeon(name, data_name) {
	const a = {
		a: get_accordeon(name, data_name),
		b: get_buttons(name, data_name),
	};
	a.a.classList.toggle("open");
	[...a.b].forEach((button) => {
		button.classList.toggle("active");
	});
}
import { setProp, getProp } from "./events/properties.js";
let accordeons = {
}
import { load } from "./events/load.js";
import { clickOn } from "./events/click.js";
load(() => {
	document.querySelectorAll('[class*="accordeon-"]').forEach(accordeon => {
		const acc = {
			name: [...accordeon.classList].filter(className => className.startsWith("accordeon-"))[0].split("accordeon-")[1],
			data_name: accordeon.dataset.name,
			button: accordeon
		}
		if(!accordeons[acc.name]) {
			accordeons[acc.name] = [];
		}
		accordeons[acc.name].push(acc.data_name);
	})
	document.querySelectorAll('.accordeon').forEach(accordeon => {
		setProp(accordeon.querySelector('.content'), "--_content-height", accordeon.querySelector('.content').scrollHeight + "px");
		setProp(accordeon.querySelector('.content'), "--_transition-speed", (accordeon.querySelector('.content').scrollHeight / getProp(accordeon.querySelector('.content'), "--_pixelsPerSeccond")).toFixed(3) * 1000 + "ms");
		accordeon.querySelector('.content').addEventListener("transitionend", e=>{
			if(e.propertyName === "height") {
				document.dispatchEvent(new CustomEvent("accordeon_changed", {detail: {accordeon: accordeon, open: accordeon.classList.contains("open")}}));
				if(e.target.closest(".accordeon").classList.contains("open")){
					e.target.closest(".accordeon").classList.add("autoHeight");
				}
			}
		})
		accordeon.querySelector('.content').addEventListener("transitionstart", e=>{
			e.target.closest(".accordeon").classList.remove("autoHeight");
		})
	})
	Object.keys(accordeons).forEach(name => {
		accordeons[name].forEach(data_name => {
			clickOn(`.accordeon-${name}[data-name="${data_name}"], .accordeon-${name}[data-name="${data_name}"] *:not(.content)`, e => {
				document.querySelector(`.accordeon.${name}[data-name="${data_name}"]`).classList.remove("autoHeight")
				function handler(e){
					openAccordeon(name, data_name);
					if(e.propertyName === "height") {
						get_accordeon('show-more', 'price').removeEventListener("transitionend", handler);
					}
				}
				setTimeout(() => {
					if(e.target.classList.contains("inReadmore") && !get_accordeon('show-more', 'price').classList.contains("open")){
						get_accordeon('show-more', 'price').addEventListener("transitionend", handler)
						openAccordeon('show-more', 'price')
					}else{
						if(name === "show-more" && data_name === "price"){
							closeAccordeons('price', get_accordeon('show-more', 'price'));
						}
						toggleAccordeon(name, data_name);
					}
				}, 10);
			})
		})
	})
})
export { openAccordeon, get_buttons, get_accordeon, closeAccordeons, toggleAccordeon };


document.addEventListener("accordeon_changed", () => {
	ScrollTrigger.refresh();
	ScrollTrigger.refresh();
})
// Documentation

// accordeon-{name} - class for button
// accordeon - class for accordeon
// data-name - name/id of accordeon
// content - class for content of accordeon
// open - class for open accordeon
// autoHeight - class for auto height accordeon
// --_content-height - css variable for content height
// --_transition-speed - css variable for transition speed
// accordeon_changed - event for accordeon changed
// openAccordeon - function for open accordeon
// get_buttons - function for get buttons
// get_accordeon - function for get accordeon
// closeAccordeons - function for close accordeons
// toggleAccordeon - function for toggle accordeon
// setProp - function for set css property
// getProp - function for get css property	