"use strict";
const navigation = document.querySelector(".main-nav");

const navigationHeight = navigation.offsetHeight;

document.documentElement.style.setProperty(
  "--scroll-padding",
  navigationHeight + "px"
);

//scroll to top

let buttontop = document.getElementById("scrolltop");

const scrollToTop = () => {
  window.scroll({
    top: 0,
    behavior: "smooth",
  });
};

buttontop.addEventListener("click", scrollToTop);
