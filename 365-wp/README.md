# 365 Technologies — Catalog Site (Railway, Node/Express)

A dark, modern, catalog-first rebuild. No WordPress, no Docker, no
pricing, no cart — this is a visual browsing experience for what 365
Technologies designs, styled with design cues pulled from anfieldind.com
and grhamerica.com (bold hero, image-forward category/product grids, spec
tables, CTA bands) but with its own distinct look: dark background,
Space Grotesk display type, orange/teal accent, glow gradients, hover-
elevated cards.

Plain Node.js + Express + EJS — Railway's most reliable deploy path
(auto-detects `package.json`, runs `npm install` then `npm start`).

## Structure

```
365-static/
├── package.json
├── server.js                 # catalog data model + all routes
├── railway.json
├── public/
│   ├── css/style.css          # dark theme, catalog grids, spec tables
│   └── js/main.js
└── views/
    ├── partials/
    │   ├── header.ejs          # nav w/ Catalog dropdown (data-driven)
    │   └── footer.ejs
    ├── home.ejs                 # hero + category preview grid
    ├── catalog.ejs                # all categories
    ├── catalog-category.ejs        # items grid within a category
    ├── catalog-product.ejs          # spec table, no price, related items
    ├── who-we-are.ejs
    ├── contact-us.ejs                # real working form
    └── 404.ejs
```

## The catalog

`server.js` holds the catalog as plain JS data — three categories
matching 365's real disciplines (Gas Springs, Hydraulic Design, Pneumatic
Design), each with a handful of items. The category descriptions are
365's real copy. The **items within each category are generic,
industry-standard product types** (e.g. "Compression Gas Springs,"
"Hydraulic Motors") — not real SKUs, not fabricated model numbers — built
to demonstrate the catalog layout with something more honest than
Lorem Ipsum. Specs are placeholder fields ("Custom to application")
rather than invented numbers.

**To swap in the real catalog:** edit the `catalog` array in `server.js`.
Each item is:

```js
{
  slug: "compression-gas-springs",
  name: "Compression Gas Springs",
  blurb: "One line, shown on the card and category page.",
  specs: [["Field label", "Value"], ["Another field", "Value"]],
}
```

No template changes needed — add/remove/edit items and the grids, detail
pages, and related-items strips update automatically.

## Run locally

```bash
npm install
npm start
```

Visit `http://localhost:3000`.

## Deploy to Railway

Same as before: push to GitHub, Railway auto-detects Node via
`package.json`, generates a domain under Settings → Networking. No
database, no install wizard, no Dockerfile.

## Contact form

`/contact-us` posts to itself, validates server-side, and — once
`SMTP_HOST` (and related `SMTP_*` vars) are set on the Railway service —
emails submissions to `info@go365tech.com` via `wp_mail`-style SMTP send
through Nodemailer. Without SMTP configured, submissions are logged
server-side and the visitor still sees a normal success state, so the
form works end-to-end on staging right now.

## What still needs real input

- **Images** — every media slot (category tiles, item cards, product
  hero) is a gradient placeholder with a text label. Drop real photos
  into `public/img/` and swap the placeholder `<div>`/`<span>` for an
  `<img>` in the relevant template, or send me the files.
- **Real catalog data** — replace the placeholder items/specs in
  `server.js` with actual SKUs, photos, and specs when ready.
- **Logo** — currently a plain "365" mark in CSS.
- **SMTP credentials** — for the contact form to actually deliver email.

## Converting to WordPress later

This stays a separate, later step. The catalog's data shape (categories
→ items → specs) maps cleanly onto a WordPress custom post type +
taxonomy setup when that conversion happens — nothing here needs to be
rebuilt from scratch for it.
