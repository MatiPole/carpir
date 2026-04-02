(function () {
  var header = document.getElementById('site-header');
  if (!header) return;
  var toggle = header.querySelector('[data-nav-toggle]');
  var nav = document.getElementById('navbar-nav');
  var backdrop = header.querySelector('.navbar-backdrop');
  if (!toggle || !nav || !backdrop) return;

  function isOpen() {
    return header.classList.contains('header--menu-open');
  }

  function setOpen(open) {
    header.classList.toggle('header--menu-open', open);
    toggle.classList.toggle('active', open);
    nav.classList.toggle('active', open);
    document.body.style.overflow = open ? 'hidden' : '';
    document.documentElement.style.overflow = open ? 'hidden' : '';
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
  }

  toggle.addEventListener('click', function () {
    setOpen(!isOpen());
  });
  backdrop.addEventListener('click', function () {
    setOpen(false);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });
  window.addEventListener('resize', function () {
    if (window.innerWidth > 768) setOpen(false);
  });
  nav.querySelectorAll('.nav-link').forEach(function (a) {
    a.addEventListener('click', function () {
      setOpen(false);
    });
  });
})();
