// Change logo randomly on pages with .logo image
document.addEventListener("DOMContentLoaded", () => {
  const logos = [
    "images/logos/logo5.png",
    "images/logos/logo6.png",
    "images/logos/logo7.png"
  ];

  const logoElem = document.querySelector(".logo");
  if (logoElem) {
    logoElem.src = logos[Math.floor(Math.random() * logos.length)];
  }
});