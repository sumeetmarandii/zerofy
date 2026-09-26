# ZeroFy

A simple, responsive website for ZeroFy, a service provider for website development, deployment, and maintenance.

## Project Structure

- `index.html` — Home page
- `about.html` — About Us page
- `contact.html` — Contact page
- `zlog.html` — Blog listing page
- `zlog-post.html` — Single blog post page
- `zorum.html` — Forum page
- `demo-site-1.html` — Demo: **Minimalism**
- `demo-site-2.html` — Demo: **Futuristic**
- `demo-site-3.html` — Demo: **Pixel art**
- `demo-site-4.html` — Demo: **Clay style**
- `demo-site-5.html` — Demo: **Retro**
- `demo-site-6.html` — Demo: **Pop art**
- `demo-site-7.html` — Demo: **Maximalism**
- `demo-site-8.html` — Demo: **Editorial**
- `404.html` — Error page
- `css/styles.css` — Shared styles
- `css/demo-themes.css` — Demo palettes (one per theme, light and dark) and demo-only components
- `js/script.js` — Mobile navigation and demo dropdown behavior
- `js/theme.js` — Light/dark mode switch for the demo pages

## Demo sites

Each demo page sets `data-theme` and `data-mode` on `<html>` and ships its own
colour combination in `css/demo-themes.css`. Every demo has a light and a dark
mode:

| Page | Theme | Light palette | Dark palette |
| --- | --- | --- | --- |
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
paper-based.

### How a theme is wired

1. `data-theme` selects a palette block in `css/demo-themes.css`. Each theme is
   defined twice: once for light mode, once for `[data-mode='dark']`.
2. The shared header, nav, buttons and footer read those same tokens, so they
   re-skin automatically. A second "character" block then adds the
   theme-specific kicker, title, card, tag and hero-ground treatments.
3. An inline script in each page head resolves the saved mode before first
   paint, so the page never flashes the wrong colours.

The mode switcher in the header stores the choice in `localStorage` under
`zerofy-demo-mode`, so it carries across all eight demos. Until the visitor
picks a mode, the pages follow `prefers-color-scheme`.

Each demo's gallery links to the other seven, and every page with a `Demo Sites`
dropdown lists all eight. `404.html` has no navigation.

## Key features

- Minimal, user-friendly design
- Responsive layout for desktop, tablet, and mobile
- Easy navigation with clear headings and buttons
- `Demo Sites` dropdown in the primary navigation, with a floating desktop menu and an indented mobile sub-menu
- Eight themed demo pages, each with its own colour combination and a light/dark mode switch
- Cross-linked demo gallery on every demo page
- SEO-friendly titles and meta descriptions
- GitHub Pages-compatible file structure

## Deployment

1. Create a GitHub repository for the project.
2. Push all files to the repository.
3. In repository settings, enable GitHub Pages from the `main` branch and root folder.
4. Open the published site URL from GitHub Pages.

There is no build step. To preview locally, serve the folder (for example
`python3 -m http.server`) and open `index.html`.

## Customization

- Update page copy to reflect your brand and services.
- Add more blog posts by creating additional page files and linking them from `zlog.html`.
- Replace placeholder contact information with your actual email or contact details.
- Add an optional logo or custom branding colors in `css/styles.css`.
- Adjust a demo palette in `css/demo-themes.css`. Each theme defines its tokens twice: once for light mode, once for `[data-mode='dark']`.
- Add a new theme by copying a palette block and a character block, setting `data-theme` on the new page's `<html>` element, and adding the page to the `Demo Sites` dropdown on every page that has one.

## Notes

- The contact form currently does not submit to a backend. You can connect it to an email service or form provider later.
- The demo pages load Inter from Google Fonts. Retro and Editorial use local Georgia instead, so they need no webfont.
- Keep page copy short and simple for the best user experience.

## Change log

- `log.md` contains an update history for the project.
- Recent additions: `demo-site-5.html` to `demo-site-8.html` (Retro, Pop art, Maximalism, Editorial), and a `Demo Sites` dropdown linking all eight demos.
