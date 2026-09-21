# 365 Technologies — Catalog Site (Railway, Node/Express)

White / blue / silver, search-driven catalog site. No WordPress, no
Docker, no pricing, no cart. Plain Node.js + Express + EJS — Railway's
most reliable deploy path (auto-detects `package.json`, runs
`npm install` then `npm start`).

## What changed in this pass

- **Footer sticky-to-bottom fixed.** `body` is now a flex column with
  `main` set to `flex: 1 0 auto`, so on short pages the footer sits at
  the bottom of the viewport instead of floating up under the content.
- **Catalog pages now use a left sidebar product tree**, matching
  Anfield's layout: a "Products" header, each category as a collapsible
  group (`<details>/<summary>`, no JS required), sub-items listed under
  the active category, current item highlighted. This replaces the
  previous full-width-grid-only catalog pages. Applies to `/catalog`,
  every category page, every product page, and `/search` — all four now
  share `views/partials/catalog-sidebar.ejs`.
- **Content sits in a rounded white "shell" card** with its own
  breadcrumb bar at the top (`»`-separated, matching the reference), sitting
  on the page's light gray background — instead of breadcrumb + content
  running edge-to-edge.


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
    │   ├── footer.ejs           # slim — no map, no address
    │   └── catalog-sidebar.ejs   # left product-tree sidebar, shared by all catalog/search pages
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
