/* Light / dark mode switcher for the demo pages.
   The colour mode lives on <html data-mode> and is shared by every demo,
   so switching on one page carries over to the others. */

const MODE_KEY = 'zerofy-demo-mode';
const root = document.documentElement;
const systemQuery = window.matchMedia('(prefers-color-scheme: dark)');

const readStoredMode = () => {
  try {
    return localStorage.getItem(MODE_KEY);
  } catch (error) {
    return null;
  }
};

const writeStoredMode = (mode) => {
  try {
    localStorage.setItem(MODE_KEY, mode);
  } catch (error) {
    /* Storage can be blocked in private mode; the toggle still works. */
  }
};

const syncThemeColor = () => {
  const meta = document.querySelector('meta[name="theme-color"]');
  if (!meta) {
    return;
  }

  const background = window.getComputedStyle(root).getPropertyValue('--bg').trim();
  if (background) {
    meta.setAttribute('content', background);
  }
};

// The palette swatches print the live computed value of each token, so the
// readout under every chip follows the colour mode like the chip itself does.
const syncSwatchValues = () => {
  const styles = window.getComputedStyle(root);

  document.querySelectorAll('[data-swatch-var]').forEach((label) => {
    const token = label.getAttribute('data-swatch-var');
    const value = styles.getPropertyValue(token).trim();
    label.textContent = value ? value.toUpperCase() : token;
  });
};

const applyMode = (mode) => {
  root.setAttribute('data-mode', mode);

  document.querySelectorAll('[data-mode-toggle]').forEach((button) => {
    const isDark = mode === 'dark';
    button.setAttribute('aria-pressed', String(isDark));
    button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');

    const text = button.querySelector('[data-mode-text]');
    if (text) {
      text.textContent = isDark ? 'Light' : 'Dark';
    }
  });

  syncThemeColor();
  syncSwatchValues();
};

const resolveMode = () => {
  const stored = readStoredMode();
  if (stored === 'light' || stored === 'dark') {
    return stored;
  }
  return systemQuery.matches ? 'dark' : 'light';
};

applyMode(resolveMode());

document.querySelectorAll('[data-mode-toggle]').forEach((button) => {
  button.addEventListener('click', () => {
    const next = root.getAttribute('data-mode') === 'dark' ? 'light' : 'dark';
    writeStoredMode(next);
    applyMode(next);
  });
});

// Follow the system preference only while the visitor has not chosen.
const onSystemChange = (event) => {
  if (readStoredMode()) {
    return;
  }
  applyMode(event.matches ? 'dark' : 'light');
};

if (typeof systemQuery.addEventListener === 'function') {
  systemQuery.addEventListener('change', onSystemChange);
} else if (typeof systemQuery.addListener === 'function') {
  systemQuery.addListener(onSystemChange);
}
