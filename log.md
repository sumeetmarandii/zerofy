# Update Log

## 2026-09-26
- Added `demo-site-5.html` (Retro), `demo-site-6.html` (Pop art), `demo-site-7.html` (Maximalism), and `demo-site-8.html` (Editorial), bringing the demo set to eight themes.
- Added a light and dark palette plus a character block for each new theme in `css/demo-themes.css`, covering `retro`, `pop`, `maximalism`, and `editorial`.
- Expanded the `Demo Sites` dropdown to all eight demos on every page that has one, and updated each demo's gallery to link to the other seven.
- Added a `Demo Sites` dropdown to the primary navigation on every page, linking to `demo-site-1.html` through `demo-site-8.html`.
- Built the first four demos as themed samples: `demo-site-1.html` (Minimalism), `demo-site-2.html` (Futuristic), `demo-site-3.html` (Pixel art), and `demo-site-4.html` (Clay style).
- Added `css/demo-themes.css` with a light and dark palette for each theme, plus demo-only components and per-theme character.
- Added `js/theme.js` and a header switcher so every demo has a light/dark mode that persists in `localStorage` and falls back to `prefers-color-scheme`.
- Fixed a mobile sub-menu bug where the desktop `transform: translate(-50%, 0)` (specificity 0-3-0) beat the mobile reset, pushing the sub-menu off screen.
- Fixed the mobile navigation panel clipping its last links by sizing it to the viewport and allowing it to scroll.
- Moved the dropdown `Escape` handler to `document` so it closes even when focus sits outside the dropdown.
- Added `.nav-dropdown` styles for the floating desktop menu and the indented mobile sub-menu.
- Updated `js/script.js` to toggle the dropdown on click, close it on outside click, on `Escape`, and when switching to desktop width.

## 2026-08-11
- Added `zorum.html` as a new forum page for community discussion.
- Updated navigation and footer links across all pages to include `Zorum`.
- Added `404.html` for missing pages and `README.md` for repository guidance.
- Renamed the blog post page to `zlog-post.html` and updated links accordingly.
