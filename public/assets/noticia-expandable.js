(function () {
  function initExpandable(root) {
    root = root || document;
    root.querySelectorAll('[data-noticia-expandable]').forEach(function (wrap) {
      var inner = wrap.querySelector('.noticia-texto-inner');
      var btn = wrap.querySelector('.noticia-ver-mas-btn');
      if (!inner || !btn) return;
      wrap.classList.add('is-collapsed');
      wrap.classList.remove('is-expanded');
      btn.textContent = 'Ver más';
      btn.setAttribute('aria-expanded', 'false');
      requestAnimationFrame(function () {
        var overflow = inner.scrollHeight > inner.clientHeight + 1;
        if (!overflow) {
          wrap.classList.remove('is-collapsed');
          btn.hidden = true;
        } else {
          btn.hidden = false;
        }
      });
      btn.addEventListener('click', function () {
        var open = !wrap.classList.contains('is-expanded');
        wrap.classList.toggle('is-expanded', open);
        wrap.classList.toggle('is-collapsed', !open);
        btn.textContent = open ? 'Ver menos' : 'Ver más';
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });
  }

  window.addEventListener('resize', function () {
    document.querySelectorAll('[data-noticia-expandable]').forEach(function (wrap) {
      if (wrap.classList.contains('is-expanded')) return;
      var inner = wrap.querySelector('.noticia-texto-inner');
      var btn = wrap.querySelector('.noticia-ver-mas-btn');
      if (!inner || !btn) return;
      wrap.classList.add('is-collapsed');
      requestAnimationFrame(function () {
        var overflow = inner.scrollHeight > inner.clientHeight + 1;
        if (!overflow) {
          wrap.classList.remove('is-collapsed');
          btn.hidden = true;
        } else {
          btn.hidden = false;
        }
      });
    });
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initExpandable();
    });
  } else {
    initExpandable();
  }
})();
