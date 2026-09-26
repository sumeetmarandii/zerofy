# ZeroFy

A simple, responsive website for ZeroFy, a service provider for website development, deployment, and maintenance. The Zlog, the site blog, is built with Jekyll and hosted on GitHub Pages.

## Project Structure

- `index.html` — Home page
- `about.html` — About Us page
- `contact.html` — Contact page
- `zorum.html` — Forum page
- `zlog/` — The Zlog index, rendered by Jekyll
- `_posts/` — Zlog entries, one Markdown file per post
- `demo-site-1.html` — Demo: **Minimalism**
- `demo-site-2.html` — Demo: **Futuristic**
- `demo-site-3.html` — Demo: **Pixel art**
- `demo-site-4.html` — Demo: **Clay style**
- `demo-site-5.html` — Demo: **Retro**
- `demo-site-6.html` — Demo: **Pop art**
- `demo-site-7.html` — Demo: **Maximalism**
- `demo-site-8.html` — Demo: **Editorial**
- `404.html` — Error page
- `_config.yml` — Jekyll configuration
- `_layouts/` — `zlog-base`, `zlog-index`, and `zlog-post`
- `_includes/` — Zlog head, header, and footer
- `css/styles.css` — Shared styles
- `css/zlog.css` — Zlog components, built on the shared `zerofy` palette
- `css/demo-themes.css` — The shared `zerofy` palette plus one palette per demo theme, each in light and dark
- `js/script.js` — Mobile navigation and demo dropdown behavior
- `js/theme.js` — Light/dark mode switch for the demo pages and the Zlog
- `Gemfile` — The Markdown parser, `kramdown-parser-gfm`

## The Zlog

The Zlog lives at `/zlog/` and is the only part of the site that goes through
Jekyll. Every other page is a hand-written static HTML file with **no front
matter**, so Jekyll copies it through untouched. The two can be mixed freely in
one repository.

### How the Zlog is themed

`_layouts/zlog-base.html` sets `data-theme="zerofy"` on `<html>`, so the Zlog
loads `css/demo-themes.css` and picks up the **same Avocado Harvest palette as
the main site** — oat cream, avocado green, harvest amber, hard offset shadows,
and Georgia instead of a webfont. `css/zlog.css` then adds only the blog
components on top. Because the colour mode lives on `<html data-mode>` and is
stored under `zerofy-demo-mode`, the Zlog shares the light/dark choice with the
main pages and all eight demos.

`demo-site-5.html` deliberately does **not** use this theme. It keeps its own
Retro palette, so the demo gallery still shows a distinct theme, and it is not
affected by changes to the live-site palette.

### Writing a post

Add one Markdown file to `_posts/`, named `YYYY-MM-DD-a-short-slug.md`:

```markdown
---
title: "The title of the entry"
date: 2026-09-20 10:30:00 +0530
description: "One or two sentences, used on the blog index and in search results."
tags: ["deployment", "github"]
---

The body of the post, in GFM. Tables, lists and code blocks are all styled.
```

`title`, `description` and `tags` are optional; `date` comes from the filename.
The index page is generated automatically, so there is nothing else to edit.
Posts publish at `/zlog/:year/:month/:day/:title/`.

## Navigation

- **Header** — Home, About, a `Demo Sites` dropdown, and Contact. The current
  page carries `aria-current="page"`. Zlog and Zorum are deliberately kept out
  of the top bar to keep it short.
- **Footer** — Home, About, Zlog, Contact, Terms and Privacy, with a `New` badge
  on the Zlog link. On the Zlog itself, that link is the one marked
  `aria-current="page"`.

`zorum.html` is still built and reachable by direct URL, but no navigation links
to it at the moment. It stays listed in the structure above so it is easy to
find and re-link later.

The `New` badge is styled from the same CSS custom properties as the rest of the
chrome (`--btn-bg` and `--btn-fg`, falling back to `--primary`). Each demo theme
therefore re-skins it with no per-theme rules.

`404.html` deliberately has no navigation, following the existing convention.

## Demo sites

The live pages and the Zlog share one theme. Each page sets `data-theme` and
`data-mode` on `<html>` and picks up its colour combination from
`css/demo-themes.css`. The eight demos each keep a palette of their own. Every
page has a light and a dark mode:

| Page | Theme | Light palette | Dark palette |
| --- | --- | --- | --- |
| `index.html`, `about.html`, `contact.html`, `terms.html`, `privacy.html`, `zorum.html`, `404.html` | ZeroFy (Avocado Harvest) | Oat cream, avocado green, harvest amber | Deep olive, lime and gold |
| `zlog/` and its posts | ZeroFy (Avocado Harvest) | Same as the main site | Same as the main site |
| `demo-site-1.html` | Minimalism | Paper white on warm grey, monochrome | Near-black, softened contrast |
| `demo-site-2.html` | Futuristic | Icy blue-white, electric blue | Space blue-black, neon cyan |
| `demo-site-3.html` | Pixel art | Cream and ink, red accent | Arcade green on purple-black |
| `demo-site-4.html` | Clay style | Warm cream, terracotta | Clay brown, coral accent |
| `demo-site-5.html` | Retro | Cream paper, mustard and rust, heavy rules | Walnut brown, orange and gold |
| `demo-site-6.html` | Pop art | Flat yellow, pink and cyan, black ink outlines | Deep violet, lifted pink and cyan |
| `demo-site-7.html` | Maximalism | Violet with yellow, pink and teal (dark by design) | Near-black violet, brighter neons |
| `demo-site-8.html` | Editorial | Warm paper, ink black, one red rule | Warm near-black, cream, soft red |

Maximalism is dark-on-colour in *both* modes by design — the only difference
between its light and dark palettes is depth. Retro and Editorial stay
paper-based. Retro is the only mustard-and-rust theme, and it belongs to
`demo-site-5.html` alone.

### How a theme is wired

1. `data-theme` selects a palette block in `css/demo-themes.css`. Each theme is
   defined twice: once for light mode, once for `[data-mode='dark']`.
2. The shared header, nav, buttons, footer and bottom bar read those same
   tokens, so they re-skin automatically. A second "character" block then adds
   the theme-specific kicker, title, card, tag and hero-ground treatments.
3. An inline script in each page head resolves the saved mode before first
   paint, so the page never flashes the wrong colours.

The mode switcher in the header stores the choice in `localStorage` under
`zerofy-demo-mode`, so it carries across the live pages, all eight demos and the
Zlog. Until the visitor picks a mode, the pages follow `prefers-color-scheme`.

`--on-accent` is the one token that is not simply the inverse of `--accent`.
Amber is light enough in both modes that body text on it needs a dark ink, so
`--on-accent` carries that ink explicitly instead of reusing `--text`.

Each demo's gallery links to the other seven, and every page with a `Demo Sites`
dropdown lists all eight. `404.html` has no navigation.

## Key features

- Minimal, user-friendly design
- Responsive layout for desktop, tablet, and mobile
- Easy navigation with clear headings and buttons
- A `New` badge on the Zlog link in the footer navigation
- `Demo Sites` dropdown in the primary navigation, with a floating desktop menu and an indented mobile sub-menu
- Eight themed demo pages, each with its own colour combination and a light/dark mode switch
- Cross-linked demo gallery on every demo page
- A light/dark switch on every page, with the choice shared site-wide via `localStorage`
- A Jekyll blog styled on the same palette as the main site
- SEO-friendly titles and meta descriptions
- GitHub Pages-compatible file structure

## Deployment

GitHub Pages builds this repository with Jekyll, so there is no manual build step
to push.

1. Create a GitHub repository for the project.
2. Push all files to the repository.
3. In repository settings, set **Pages → Source** to *Deploy from a branch*,
   `main` / root (`/`).
4. `CNAME` already holds the custom domain, and the `Gemfile` declares the
   gems the build needs.
5. Open the published site URL from GitHub Pages.

To preview locally, serve the build:

```bash
jekyll serve
```

The site is then at `http://127.0.0.1:4000`, with live rebuild as you edit, and
`http://127.0.0.1:4000/zlog/` for the blog. Use `jekyll build` to write the
static output into `_site/`.

> Run `jekyll serve`, not `bundle exec jekyll serve`. Bundler resolves an old
> `safe_yaml` that needs `base64`, a gem Ruby 3.4 no longer ships by default,
> so the `bundle exec` route fails on a current Ruby. GitHub Pages builds with
> its own pinned gems, so this affects local preview only. If you hit it, run
> `bundle install` and add `gem "base64"` to the `Gemfile`, or just use the
> plain command above.

> `url:` in `_config.yml` is set to the custom domain. If the site is ever
> published under a project subpath, change `url` and set `baseurl` to that
> subpath — the Zlog links use `relative_url` and will follow.

## Customization

- Update page copy to reflect your brand and services.
- Add a Zlog entry by dropping a Markdown file into `_posts/`. The index and the
  permalink update on the next build.
- Add a tag in a post's front matter to group entries.
- Adjust the live-site and Zlog palette by editing the `zerofy` blocks in
  `css/demo-themes.css` — the main pages and the blog stay in step automatically.
  `demo-site-5.html` keeps its own Retro palette and is unaffected.
- Replace placeholder contact information with your actual email or contact details.
- Add an optional logo or custom branding colors in `css/styles.css`.
- Adjust a demo palette in `css/demo-themes.css`. Each theme defines its tokens twice: once for light mode, once for `[data-mode='dark']`.
- Add a new theme by copying a palette block and a character block, setting `data-theme` on the new page's `<html>` element, and adding the page to the `Demo Sites` dropdown on every page that has one.
- Add or rename a footer link in `_includes/zlog-footer.html` and in the
  `footer-nav` block of each static page.

## Notes

- The contact form currently does not submit to a backend. You can connect it to an email service or form provider later.
- The demo pages load Inter from Google Fonts. The live site, the Zlog, Retro and Editorial all use local Georgia instead, so they need no webfont.
- Keep page copy short and simple for the best user experience.
- Only `kramdown-parser-gfm` is declared in the `Gemfile`. The Zlog uses no
  Jekyll plugins, so the build depends on nothing beyond what GitHub Pages
  already ships.

## Change log

- `log.md` contains an update history for the project.
- Recent additions: one shared Avocado Harvest theme across the live pages and the
  Zlog, a light/dark switch that is shared site-wide, and the Jekyll-powered Zlog
  at `/zlog/`.
