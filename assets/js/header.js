document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const menuClose = document.getElementById('menuClose');
    const mobileMenu = document.getElementById('mobileMenu');
  
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.add('open');
    });
  
    menuClose.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
    });
  });
  