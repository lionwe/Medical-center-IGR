function setProp(element, property, value) {
	element.style.setProperty(property, value);
}
function getProp(element, property, default_value = "") {
	return getComputedStyle(element).getPropertyValue(property) || default_value;
}
export { setProp, getProp };