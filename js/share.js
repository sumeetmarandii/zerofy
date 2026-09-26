/* Copy-link button for the Zlog share row.
   Uses the async Clipboard API where available and falls back to a hidden
   textarea plus execCommand, which is still needed on http:// pages. */

document.querySelectorAll('[data-share-copy]').forEach((button) => {
  const url = button.getAttribute('data-share-copy');
  const idleLabel = button.getAttribute('data-share-label') || 'Copy link';
  const doneLabel = button.getAttribute('data-share-copied') || 'Copied';

  const confirm = () => {
    button.textContent = doneLabel;
    button.classList.add('is-copied');

    window.setTimeout(() => {
      button.textContent = idleLabel;
      button.classList.remove('is-copied');
    }, 2000);
  };

  const fallbackCopy = () => {
    const field = document.createElement('textarea');
    field.value = url;
    field.setAttribute('readonly', '');
    field.style.position = 'fixed';
    field.style.opacity = '0';
    field.style.pointerEvents = 'none';
    document.body.appendChild(field);
    field.select();

    try {
      document.execCommand('copy');
      confirm();
    } catch (error) {
      // Clipboard blocked entirely: leave the label alone so it is still a
      // working retry, rather than claiming a copy that never happened.
    } finally {
      document.body.removeChild(field);
    }
  };

  button.addEventListener('click', () => {
    if (!navigator.clipboard || !navigator.clipboard.writeText) {
      fallbackCopy();
      return;
    }

    navigator.clipboard.writeText(url).then(confirm).catch(fallbackCopy);
  });
});
