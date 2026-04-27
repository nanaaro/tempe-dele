import './bootstrap';

// Simple collapse toggle (Flowbite-like)
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-collapse-toggle]');
  if (!btn) return;

  const targetId = btn.getAttribute('data-collapse-toggle');
  const target = document.getElementById(targetId);
  if (!target) return;

  target.classList.toggle('hidden');

  const expanded = btn.getAttribute('aria-expanded') === 'true';
  btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
});

// Modal Loading
    const overlay = document.getElementById('loadingOverlay');

    function showLoading() {
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
    }

    function hideLoading() {
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100');
    }

    document.addEventListener('submit', showLoading);

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (a && a.href && !a.href.startsWith('#')
            && !a.href.startsWith('javascript')
            && !a.target) {
            showLoading();
        }
    });

    window.addEventListener('pageshow', hideLoading);

