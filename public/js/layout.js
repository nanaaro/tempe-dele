(function () {
  const burger = document.querySelector('.burger');
  const menu = document.getElementById('mobile-menu');

  if (!burger || !menu) return;

  burger.addEventListener('click', () => {
    const isOpen = menu.classList.toggle('open');
    burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });
})();
