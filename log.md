# Update Log

## 2026-09-27
- Brought the main site and the Zlog under one shared **Avocado Harvest** theme. Added a `zerofy` palette and a theme block to `css/demo-themes.css`, covering oat cream, avocado green, harvest amber and brick red in both light and dark, and tokenized the core values in `css/styles.css`.
- Applied `data-theme="zerofy"`, the theme stylesheet, the pre-paint mode resolver and the shared `zerofy-demo-mode` preference to `index.html`, `about.html`, `contact.html`, `terms.html`, `privacy.html`, `zorum.html` and `404.html`, and added a light/dark control to each header.
- Switched the Zlog from `data-theme="retro"` to `data-theme="zerofy"` and replaced its hardcoded colours and shadows with tokens, so the blog now matches the live pages instead of Demo 5.
- Left `demo-site-5.html` on its original mustard-and-rust Retro palette. It is the only Retro theme and is unaffected by changes to the shared palette.
- Added an `--on-accent` token, because harvest amber is light enough in dark mode that text on it needs a dark ink rather than the inverted `--text`.
- Made the open mobile navigation panel opaque for this theme. The translucent `--panel` let the hero headline and card text read through the links, and the `backdrop-filter` blur could not be relied on to hide it. The override is declared in the responsive block, after the generic `html[data-theme] .site-nav` rule that has the same specificity.
- Verified with a headless-Chrome pass over the DevTools protocol: the mobile menu, the `Demo Sites` dropdown, its Escape and outside-click handling, mode persistence across pages and the Zlog, and horizontal overflow on every page at 390px and 1280px. All 36 checks pass, with no uncaught JS errors.
- Updated `README.md` for the shared theme and the shared light/dark switch.
- Dropped `Zlog` and `Zorum` from the primary navigation, leaving `Home`, `About`, the `Demo Sites` dropdown, and `Contact`. The Zlog stays linked from the footer, which is where its `New` badge lives.
- Moved the `aria-current="page"` marker for the Zlog from the header link to the footer link, so the blog pages keep a "you are here" signal now that the header no longer links them.
- Hid the `Zorum` link from the footer navigation across all 13 static pages and the Zlog footer include. `zorum.html` is still built and reachable by direct URL, just unlinked.
- Added the Zlog, a Jekyll blog at `/zlog/`, replacing the old hand-written `zlog.html` and `zlog-post.html` pages.
- Added `_config.yml`, `_layouts/` (`zlog-base`, `zlog-index`, `zlog-post`), `_includes/`, and a `Gemfile`. The Zlog uses no Jekyll plugins, so the build depends on nothing GitHub Pages does not already ship.
- Added `_posts/` with three starter entries, published at dated permalinks under `/zlog/`.
- Added `css/zlog.css` for the blog components. The blog now sets `data-theme="zerofy"`, so it reuses the shared live-site palette from `css/demo-themes.css` and stays in step with the main pages.
- The Zlog reuses the header, mobile menu and light/dark switcher, and shares the `zerofy-demo-mode` localStorage key with all eight demos.
- Replaced the commented-out `Zlog` and `Zorum` placeholders with live links in the header and footer navigation across all 13 pages, and enabled the `Read the blog` button on `about.html`.
- Marked the current page with `aria-current="page"` in the header and footer navigation, and added styles for that state.
- Added a `Zlog` link to the home page quick links and repointed the Zorum `Browse articles` button at the new blog.
- Added a `New` badge to the Zlog link in the footer navigation, styled from `--btn-bg` and `--btn-fg` so every demo palette re-skins it with no per-theme rules.
- Escaped `Terms & Conditions` as `Terms &amp; Conditions` in the footer navigation while rebuilding it.
- Added a `.gitignore` for `_site/`, `.jekyll-cache/`, and Bundler artefacts.
- Documented the blog and the new build step in `README.md`.
- Cleared the Zlog and published its first entry. Removed the three original posts (`keeping-a-small-site-fast`, `github-pages-deployment-checklist` and `launching-your-first-site`) and replaced them with `Introducing The Zlog`, which covers what the blog is for, what it will not carry, and how it is built. Dated the entry `2026-09-26`: an earlier draft used `2026-09-27`, and Jekyll's default `future: false` silently skips future-dated posts, so the page did not publish until the date was corrected.
- Embedded `assets/Zerofy.webp` as a `<figure>` with a caption, referenced through `relative_url` because the post permalink nests under `/zlog/YYYY/MM/DD/` and a plain relative path would 404. The source is 2000x2000, so it carries explicit `width` and `height` to reserve layout space, plus `loading="lazy"` and `decoding="async"`.
- Added `figure` and `figcaption` rules to `css/zlog.css`, and set prose images to `display: block` with `width: 100%` and `height: auto` so an oversized image can neither distort its aspect ratio nor sit inline with the surrounding text.
- Left the templates alone. The Zlog index empty state and the "That is all of them" hand-off in `zlog-post.html` both already handle a single-entry archive, so no conditional needed changing.
- Re-ran the headless-Chrome pass on the new index and post in light and dark at 1280px and 390px: one card, image decoded at 2000x2000 and rendered at 721x721, square at 358px on mobile, and no horizontal overflow.
- Added a second Zlog entry, `biller`, introducing Biller, the invoice tool at `biller.zerofy.me` that the home page already listed under "Applications we have built". It embeds `assets/biller.webp` in a `<figure>` through `relative_url`, the same treatment as the Zlog launch entry, and needs no CSS or template changes.
- Kept the entry's product claims to what the live app actually shows: a three-section workspace (Details, Recipient, Line items), USD/EUR/GBP/INR/AUD, tax and discount, a live preview, a signature and stamp area, and a guest quota of ten invoices before the paid ₹99/month or ₹999/year workspace. The `/pricing` page currently returns 404, so the entry quotes the prices the home page displays and does not describe the plans beyond that.
- Centred the Zlog post images with `margin: 0.9rem auto 0` on `.zlog-prose img`. The rule is `display: block` at 50% width, so it was hanging left inside the figure; horizontal auto margins centre it without touching the width or the top gap. The caption below was already `text-align: center`, so the two now line up. Verified equal left and right gaps at 1280px (180px), 768px (180px) and 390px (90px), with no horizontal overflow at any of the three.
- Dated the entry `2026-09-26 14:00:00 +0530`. A first draft used `15:00`, which was ahead of the current time, and Jekyll's `future: false` dropped it silently while still reporting a successful build, so the post page was simply absent from `_site`. Ordering is intentional: it sits after the 10:00 Zlog entry, which puts Biller first on the index and makes "Introducing The Zlog" the previous entry.

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
