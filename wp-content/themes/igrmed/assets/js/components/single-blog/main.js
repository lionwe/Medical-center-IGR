import BlogShare from './content';

const init = () => new BlogShare();

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", init);
} else {
  init();
}
