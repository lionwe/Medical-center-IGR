function initGynProceduresMore() {
  const buttons = document.querySelectorAll('.js-gyn-procedures-more');
  if (!buttons.length) return;

  buttons.forEach((btn) => {
    const list = btn.closest('.gyn-content__list-blocks');
    if (!list) return;

    btn.addEventListener('click', () => {
      const isExpanded = btn.getAttribute('aria-expanded') === 'true';
      const collapsible = list.querySelectorAll('.is-collapsible-service');
      const textEl = btn.querySelector('.gyn-content__list-more-text');
      const moreLabel = btn.dataset.moreLabel || 'Всі процедури';
      const lessLabel = btn.dataset.lessLabel || 'Сховати';

      if (!isExpanded) {
        collapsible.forEach((item, index) => {
          item.style.opacity = '0';
          item.style.transform = 'translateY(20px)';
          item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
          item.classList.remove('is-hidden-service');

          setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
          }, index * 60);
        });
      } else {
        collapsible.forEach((item) => {
          item.classList.add('is-hidden-service');
          item.style.opacity = '';
          item.style.transform = '';
          item.style.transition = '';
        });
      }

      btn.setAttribute('aria-expanded', String(!isExpanded));
      if (textEl) {
        textEl.textContent = isExpanded ? moreLabel : lessLabel;
      }
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initGynProceduresMore);
} else {
  initGynProceduresMore();
}
