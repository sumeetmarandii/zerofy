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
- `404.html` — Error page
- `css/styles.css` — Shared styles
- `css/demo-themes.css` — Demo palettes (one per theme, light and dark) and demo-only components
- `js/script.js` — Mobile navigation and demo dropdown behavior
- `js/theme.js` — Light/dark mode switch for the demo pages

## Demo sites

Each demo page sets `data-theme` on `<html>` and ships its own colour
combination in `css/demo-themes.css`. Every demo also has a light and a dark
mode:

| Page | Theme | Light palette | Dark palette |
| --- | --- | --- | --- |
| `demo-site-1.html` | Minimalism | Paper white on warm grey, monochrome | Near-black, softened contrast |
| `demo-site-2.html` | Futuristic | Icy blue-white, electric blue | Space blue-black, neon cyan |
| `demo-site-3.html` | Pixel art | Cream and ink, red accent | Arcade green on purple-black |
| `demo-site-4.html` | Clay style | Warm cream, terracotta | Clay brown, coral accent |

The mode switcher in the header stores the choice in `localStorage` under
`zerofy-demo-mode`, so it carries across all four demos. Until the visitor
picks a mode, the pages follow `prefers-color-scheme`.

## Key features

- Minimal, user-friendly design
- Responsive layout for desktop, tablet, and mobile
- Easy navigation with clear headings and buttons
- `Demo Sites` dropdown in the primary navigation, with a floating desktop menu and an indented mobile sub-menu
- Four themed demo pages, each with its own colour combination and a light/dark mode switch
- SEO-friendly titles and meta descriptions
- GitHub Pages-compatible file structure

## Deployment

1. Create a GitHub repository for the project.
2. Push all files to the repository.
3. In repository settings, enable GitHub Pages from the `main` branch and root folder.
4. Open the published site URL from GitHub Pages.

## Customization

- Update page copy to reflect your brand and services.
- Add more blog posts by creating additional page files and linking them from `zlog.html`.
- Replace placeholder contact information with your actual email or contact details.
- Add an optional logo or custom branding colors in `css/styles.css`.
- Adjust a demo palette in `css/demo-themes.css`. Each theme defines its tokens twice: once for light mode, once for `[data-mode='dark']`.
- Add a new theme by copying a token block, then set `data-theme` on the new page's `<html>` element.

## Notes

- The contact form currently does not submit to a backend. You can connect it to an email service or form provider later.
- Keep page copy short and simple for the best user experience.

## Change log

- `log.md` contains an update history for the project.
- Recent additions: `zorum.html` forum page, `404.html`, `README.md`, and renamed blog post page to `zlog-post.html`.
