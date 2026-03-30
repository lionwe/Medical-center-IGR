import SurrogateTabs from './tabs.js';

const init = () => new SurrogateTabs();

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}
