# 365 Technologies — Catalog Site (Railway, Node/Express)

White / blue / silver, search-driven catalog site. No WordPress, no
Docker, no pricing, no cart. Plain Node.js + Express + EJS — Railway's
most reliable deploy path (auto-detects `package.json`, runs
`npm install` then `npm start`).

## What changed in this pass

- **Homepage hero redesigned.** Removed the "Design Catalog" eyebrow
  label. The hero now has a dark navy gradient background with a
  `<video>` element behind it (autoplay, muted, looped) for you to drop
  a background video into — see `public/video/README.md`. Even with no
  video file present yet, the hero still looks intentional (navy
  gradient + dark overlay, white text) rather than showing a broken
  video icon.
- **All screenshot-extracted images removed, completely.** The
  homepage's three "insight" cards, the blue statement band, the intro
  paragraph with the gas-spring photo strip, and the Who We Are team
  photo / force-chart image are all gone — sections either removed
  entirely (homepage) or reverted to the standard "Image Placeholder"
  tile (Who We Are). The actual image files
  (`home-insight.png`, `home-dna.png`, `home-offerings.png`,
  `gas-spring-strip.png`, `team-photo.png`, `force-chart.png`) have been
  deleted from `public/img/` — only the logo files remain there now.
- Homepage is leaner as a result: video hero → catalog category grid →
  footer. No other sections in between.

- **Real logo files added** — both versions you sent now live in
  `public/img/logo/`: `365-logo-color.png` (the navy circle mark, used in
  the header and footer) and `365-logo-white.png` (a white/transparent
  version, kept in the repo for future use on dark backgrounds — nothing
  currently on the site is dark enough to need it, but it's there when
  you add something that is). The color mark is also wired up as the
  browser favicon.
- **Color palette rebuilt from the logo itself.** Colors were sampled
  directly from `365-color.png` rather than picked freehand:
  - `#2c3d44` / `#1c282d` — the logo's dark navy circle, used as
    `--navy` / `--navy-deep`
  - `#3f6478` / `#2c4a58` — a deepened version of the logo's mid-tone
    blue-gray, used as `--accent` / `--accent-dark` (buttons, links,
    active nav states, focus rings)
  - `#86a3b2` — the logo's lighter blue-gray, `--slate`
  - `#c3e2f1` — the logo's palest tone, `--pale`
  - `--bg-alt` and card-media gradients now tint toward this same family
    instead of neutral gray, so hero backgrounds, alt sections, and the
    placeholder image tiles all read as part of one palette instead of
    generic gray boxes.

  This replaced the earlier, more generic "#2f5fe8" blue used throughout
  every button, link, active state, and card accent — the whole site's
  color now traces back to the actual logo rather than an arbitrary pick.

- **Typeface changed to Helvetica-style.** Headings and body both now use
  `"Helvetica Neue", Helvetica, Arial` first (true Helvetica on
  Mac/iOS), falling back to **Public Sans** — a free, neutral grotesque
  built specifically to look like Helvetica/Arial — for everyone else.
  Dropped the Bricolage Grotesque/Plus Jakarta Sans pairing entirely.
- **Real homepage content restored**, using the actual copy and photos
  from the live site (extracted from the screenshots provided): the
  three "Understanding our insight" / "Design is built into our DNA" /
  "Learn more about our diverse offerings" cards, the blue statement
  band, and the intro paragraph with the gas-spring photo strip. These
  sit between the hero and the catalog category grid on `/`.
- **Real photos on Who We Are**, too — the team meeting photo and the
  hand-force engineering chart, both pulled from the provided
  screenshots, replacing the placeholder tiles on that page.
- **Search bar redesigned** — real magnifying-glass icon (inline SVG)
  instead of the odd arrow button, cleaner pill shape, focus state on
  the input border.
- **Footer tag changed** from "Staging build — Railway" to "Conceptual
  Mock-Up — Mitchell Demuesy".

### About images now

The only images left in `public/img/` are the two logo files. Every
other image slot across the site (category tiles, item cards, product
pages, Who We Are, hero) is a plain "Image Placeholder" tile — no
screenshot-derived photos anywhere anymore. Add real photos whenever
you have them; see "What still needs real input" below.

## Structure

```
365-static/
├── package.json
├── server.js                 # catalog data + search index + all routes
├── railway.json
├── public/
│   ├── css/style.css          # logo-derived palette, white/blue-gray theme
│   ├── js/main.js
│   ├── video/
│   │   └── README.md           # drop hero-background.mp4 here
│   └── img/
│       └── logo/
│           ├── 365-logo-color.png   # header/footer logo + favicon
│           └── 365-logo-white.png    # white version, for future dark contexts
└── views/
    ├── partials/
    │   ├── header.ejs          # nav + search bar
    │   ├── footer.ejs           # slim — no map, no address
    │   └── catalog-sidebar.ejs   # left product-tree sidebar, shared by all catalog/search pages
    ├── home.ejs                  # video hero + category grid
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

- **Hero background video** — drop `hero-background.mp4` into
  `public/video/` (see the README there). Nothing else to configure.
- **Images** — every "Image Placeholder" tile needs a real photo. Drop
  files into `public/img/` and swap the placeholder `<span>` for an
  `<img>` in the relevant template, or send me the files.
- **Real catalog data** — replace the placeholder items/specs in
  `server.js` with actual SKUs, photos, and specs when ready.
- **SMTP credentials** — for the contact form to actually deliver email.

## Converting to WordPress later

Still a separate, later step. The catalog's data shape (categories →
items → specs) and the search behavior map cleanly onto a WordPress
custom post type + taxonomy + search query when that conversion happens.
