const navToggle = document.querySelector('.mobile-nav-toggle');
const siteNav = document.querySelector('.site-nav');
const pageLoader = document.getElementById('page-loader');
const pageTransition = document.getElementById('page-transition');
const cookieBanner = document.getElementById('cookie-banner');
const cookieAccept = document.getElementById('cookie-accept');
const cookieDeny = document.getElementById('cookie-deny');

if (cookieBanner && cookieAccept && cookieDeny) {
  const consentKey = 'zerofy-cookie-consent';
  const hasConsent = localStorage.getItem(consentKey);

  if (!hasConsent) {
    cookieBanner.classList.add('is-visible');
  }

  const dismissBanner = () => {
    cookieBanner.classList.remove('is-visible');
  };

  cookieAccept.addEventListener('click', () => {
    localStorage.setItem(consentKey, 'accepted');
    dismissBanner();
  });

  cookieDeny.addEventListener('click', () => {
    localStorage.setItem(consentKey, 'denied');
    dismissBanner();
    window.location.href = '404.html';
  });
}

const hideLoader = () => {
  if (pageLoader) {
    pageLoader.classList.add('is-hidden');
  }

  if (pageTransition) {
    pageTransition.classList.remove('is-active');
  }
};

const revealElements = document.querySelectorAll('.reveal');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (revealElements.length) {
  if (prefersReducedMotion) {
    revealElements.forEach((element) => element.classList.add('is-visible'));
  } else {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.16 });

    revealElements.forEach((element) => revealObserver.observe(element));
  }
}

const heroCard = document.querySelector('.hero-card');
if (heroCard && !prefersReducedMotion) {
  heroCard.addEventListener('mousemove', (event) => {
    const rect = heroCard.getBoundingClientRect();
    const offsetX = (event.clientX - rect.left) / rect.width - 0.5;
    const offsetY = (event.clientY - rect.top) / rect.height - 0.5;

    heroCard.style.transform = `perspective(1000px) rotateY(${offsetX * 8}deg) rotateX(${offsetY * -8}deg) translateY(-4px)`;
  });

  heroCard.addEventListener('mouseleave', () => {
    heroCard.style.transform = '';
  });
}

const yearEl = document.querySelector('[data-current-year]');
if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}

const contactForm = document.querySelector('.contact-form');
const successPopup = document.getElementById('success-popup');

if (contactForm && successPopup) {
  contactForm.addEventListener('submit', () => {
    successPopup.classList.add('is-visible');
    window.setTimeout(() => {
      successPopup.classList.remove('is-visible');
    }, 2500);
  });
}

document.addEventListener('DOMContentLoaded', hideLoader);
window.addEventListener('load', hideLoader);
window.addEventListener('pageshow', hideLoader);

if (pageTransition) {
  document.querySelectorAll('a[href]').forEach((link) => {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
      return;
    }

    if (link.target === '_blank' || link.hasAttribute('download')) {
      return;
    }

    link.addEventListener('click', (event) => {
      const currentUrl = window.location.href;
      const nextUrl = new URL(href, window.location.href).href;

      if (nextUrl === currentUrl) {
        return;
      }

      event.preventDefault();
      pageTransition.classList.add('is-active');
      window.setTimeout(() => {
        window.location.assign(nextUrl);
      }, 220);
    });
  });
}

if (navToggle && siteNav) {
  const body = document.body;
  const setMenuState = (expanded) => {
    navToggle.setAttribute('aria-expanded', String(expanded));
    navToggle.setAttribute('aria-label', expanded ? 'Close navigation menu' : 'Open navigation menu');
    navToggle.textContent = expanded ? 'Close' : 'Menu';
    siteNav.classList.toggle('open', expanded);
    siteNav.setAttribute('aria-hidden', String(!expanded));
    body.classList.toggle('nav-open', expanded);
  };

  navToggle.addEventListener('click', () => {
    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
    setMenuState(!expanded);
  });

  siteNav.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 720) {
        setMenuState(false);
      }
    });
  });

  document.addEventListener('click', (event) => {
    if (window.innerWidth > 720 || !body.classList.contains('nav-open')) {
      return;
    }

    if (!siteNav.contains(event.target) && !navToggle.contains(event.target)) {
      setMenuState(false);
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 720) {
      setMenuState(false);
    }
  });
}

document.querySelectorAll('.nav-dropdown').forEach((dropdown) => {
  const toggle = dropdown.querySelector('.nav-dropdown-toggle');
  const menu = dropdown.querySelector('.nav-dropdown-menu');

  if (!toggle || !menu) {
    return;
  }

  const setDropdownState = (expanded) => {
    dropdown.classList.toggle('open', expanded);
    toggle.setAttribute('aria-expanded', String(expanded));
  };

  toggle.addEventListener('click', (event) => {
    event.stopPropagation();
    setDropdownState(toggle.getAttribute('aria-expanded') !== 'true');
  });

  menu.addEventListener('click', (event) => {
    if (event.target.closest('a')) {
      setDropdownState(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape' || !dropdown.classList.contains('open')) {
      return;
    }

    setDropdownState(false);
    toggle.focus();
  });

  document.addEventListener('click', (event) => {
    if (dropdown.classList.contains('open') && !dropdown.contains(event.target)) {
      setDropdownState(false);
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 720) {
      setDropdownState(false);
    }
  });
});
