(function () {
  function activateEmbeds() {
    document.querySelectorAll('iframe[data-src]').forEach(function (el) {
      var url = el.getAttribute('data-src');
      if (!url) return;
      el.setAttribute('src', url);
      el.removeAttribute('data-src');
    });
  }

  function schedule() {
    if (typeof requestIdleCallback === 'function') {
      requestIdleCallback(
        function () {
          activateEmbeds();
        },
        { timeout: 3000 }
      );
    } else {
      setTimeout(activateEmbeds, 0);
    }
  }

  if (document.readyState === 'complete') {
    schedule();
  } else {
    window.addEventListener('load', schedule, { once: true });
  }
})();
