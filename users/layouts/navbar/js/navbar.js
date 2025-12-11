// Fungsi untuk efek scroll navbar
window.addEventListener('scroll', function() {
  const navbar = document.getElementById('navbar');
  if (window.scrollY > 50) {
    navbar.classList.add('shrink');
  } else {
    navbar.classList.remove('shrink');
  }
});

// Fungsi untuk burger menu
document.addEventListener('DOMContentLoaded', function() {
  const burgerMenu = document.getElementById('burgerMenu');
  const navLinks = document.querySelector('.nav-links');
  
  burgerMenu.addEventListener('click', function() {
    burgerMenu.classList.toggle('active');
    navLinks.classList.toggle('active');
  });
  
  // Tutup menu saat link diklik (di mobile)
  const navItems = document.querySelectorAll('.nav-links a');
  navItems.forEach(item => {
    item.addEventListener('click', function() {
      burgerMenu.classList.remove('active');
      navLinks.classList.remove('active');
    });
  });
});