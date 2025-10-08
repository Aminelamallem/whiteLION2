import './bootstrap.js';

// J'importe d'abord Bootstrap
import './vendor/bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

// Ensuite SEULEMENT mon CSS pour écraser si besoin
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener("turbo:load", () => {
  const nav = document.getElementById("navbar");
  let lastScroll = window.scrollY;
  const delta = 8;

  // Gestion du menu navbar au scroll
  window.addEventListener("scroll", () => {
    const currentScroll = window.scrollY;
    if(Math.abs(currentScroll - lastScroll) <= delta){
      return;
      // sortir du listenne 
    }

    if (currentScroll > lastScroll) {
      nav.classList.add("hide"); // descente -> afficher
    } else {
      nav.classList.remove("hide"); // montée -> cacher
    }
    lastScroll = currentScroll;
  }, { passive: true });


  
//  uniquement sur les sections avec la classe .sectionJs
document.addEventListener("turbo:load", () => {
  const sections = document.querySelectorAll(".sectionJs");

  setTimeout(() => {
    sections.forEach((section) => {
      section.style.left = "0";
      section.style.opacity = "1";
    });
  }, 350); // 1000=1s après chargement
});


});
