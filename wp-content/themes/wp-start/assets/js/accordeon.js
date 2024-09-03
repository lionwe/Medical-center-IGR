// document.querySelectorAll(".accordeon").forEach((accordeon) => {
//   const content = accordeon.querySelector(".accordeon__content");
//   content.style.setProperty(
//     "--_transition-speed",
//     (
//       content.scrollHeight /
//       getComputedStyle(content).getPropertyValue("--_pixelsPerSeccond")
//     ).toFixed(3) *
//       1000 +
//       "ms"
//   );
//   accordeon.querySelector(".open-button")?.addEventListener("click", () => {
//     accordeon.classList.toggle("show");
//     if (content) {
//       let variable =
//         getComputedStyle(content).getPropertyValue("--_content-height");
//       if (variable === "0px") {
//         content.style.setProperty(
//           "--_content-height",
//           content.scrollHeight + "px"
//         );
//       } else {
//         content.style.setProperty("--_content-height", "0px");
//       }
//     }
//   });
// });
document.querySelectorAll(".accordeon").forEach(e=>{let t=e.querySelector(".accordeon__content");t.style.setProperty("--_transition-speed",1e3*(t.scrollHeight/getComputedStyle(t).getPropertyValue("--_pixelsPerSeccond")).toFixed(3)+"ms"),e.querySelector(".open-button")?.addEventListener("click",()=>{e.classList.toggle("show"),t&&("0px"===getComputedStyle(t).getPropertyValue("--_content-height")?t.style.setProperty("--_content-height",t.scrollHeight+"px"):t.style.setProperty("--_content-height","0px"))})});