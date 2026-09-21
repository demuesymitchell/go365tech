# 365 Technologies — Catalog Site (Railway, Node/Express)

White / blue / silver, search-driven catalog site. No WordPress, no
Docker, no pricing, no cart. Plain Node.js + Express + EJS — Railway's
most reliable deploy path (auto-detects `package.json`, runs
`npm install` then `npm start`).

## What changed in this pass

- **Search.** A search bar lives in the header on every page and again,
  larger, in the homepage hero. It queries `/search?q=...` across every
  item in every category (name, description, and category all match) and
  returns a results grid — the catalog is now fully searchable, not just
  browsable by clicking through categories.
- **Home page rebuilt.** Search-forward hero, then a category grid. No
  stat bands, no CTA bands, no map. Much leaner.
- **Map moved.** The Google Maps embed now lives in exactly one place —
  inside the contact card on `/contact-us` — instead of repeating on
  every page's footer.
- **Footer slimmed down.** Logo, four nav links, social icons, copyright.
  No address, no map, no duplicated content — that's what the Contact
  page is for.
- **New palette.** White background, blue accent (`#2f5fe8`), silver/gray
  neutrals throughout — replaces the previous dark theme entirely.
- **Explicit placeholders.** Every image slot is a dashed-border tile
  labeled "Image Placeholder" rather than a decorative gradient trying to
  pass as a real photo. Category and item grids each end with a dashed
  "+ More coming soon" tile, so the catalog visibly reads as a
  work-in-progress structure rather than a finished, padded-out site.

## Structure

```
365-static/
├── package.json
├── server.js                 # catalog data + search index + all routes
├── railway.json
├── public/
│   ├── css/style.css          # white/blue/silver theme
│   └── js/main.js
└── views/
    ├── partials/
    │   ├── header.ejs          # nav + search bar
    │   └── footer.ejs           # slim — no map, no address
    ├── home.ejs                  # search-forward hero + category grid
    ├── catalog.ejs                 # all categories
    ├── catalog-category.ejs         # items grid within a category
    ├── catalog-product.ejs           # spec table, no price, related items
    ├── search.ejs                     # search results across the catalog
    ├── who-we-are.ejs
    ├── contact-us.ejs                  # the only page with the map
    └── 404.ejs
```

## The catalog + search

`server.js` holds the catalog as plain JS data (see the `catalog` array).
`allItems()` flattens every category's items into one searchable list;
`searchCatalog(query)` does a simple case-insensitive substring match
against each item's name, blurb, and category name. To swap in the real
catalog, edit the `catalog` array — each item is:

```js
{
  slug: "compression-gas-springs",
  name: "Compression Gas Springs",
  blurb: "One line, shown on the card and category page.",
  specs: [["Field label", "Value"], ["Another field", "Value"]],
}
```

No template or search-logic changes needed — add/remove/edit items and
the grids, detail pages, related-items strips, and search all update
automatically.

## Run locally

```bash
npm install
npm start
```

Visit `http://localhost:3000`.

## Deploy to Railway

Same as before: push to GitHub, Railway auto-detects Node via
`package.json`, generate a domain under Settings → Networking. No
database, no install wizard, no Dockerfile.

## Contact form

`/contact-us` posts to itself, validates server-side, and — once
`SMTP_HOST` (and related `SMTP_*` vars) are set on the Railway service —
emails submissions to `info@go365tech.com` via Nodemailer. Without SMTP
configured, submissions are logged server-side and the visitor still
sees a normal success state.

## What still needs real input

- **Images** — every "Image Placeholder" tile needs a real photo. Drop
  files into `public/img/` and swap the placeholder `<span>` for an
  `<img>` in the relevant template, or send me the files.
- **Real catalog data** — replace the placeholder items/specs in
  `server.js` with actual SKUs, photos, and specs when ready.
- **Logo** — currently a plain "365" mark in CSS.
- **SMTP credentials** — for the contact form to actually deliver email.

## Converting to WordPress later

Still a separate, later step. The catalog's data shape (categories →
items → specs) and the search behavior map cleanly onto a WordPress
custom post type + taxonomy + search query when that conversion happens.
