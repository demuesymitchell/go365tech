# 365 Technologies — staging site (Railway, no Docker/WordPress)

Plain Node.js + Express + EJS. No database, no PHP, no Apache, no Docker
layer to fight with — this is Railway's most reliable deploy path
(auto-detects `package.json`, runs `npm install` then `npm start`).

This is **not** WordPress. It's a fast, tangible staging build to present
now. Converting it into the WordPress theme is a separate, later step —
the page structure, copy, and design here are exactly what that theme
gets built from, so nothing here is wasted work.

## Design direction

The visual language borrows deliberately from the two reference sites
(anfieldind.com, grhamerica.com) rather than the old default-WordPress-theme
look:

- A dark **utility bar** above the main nav (phone/email/social + a
  standing "Request a Quote" CTA) — matches Anfield's header pattern.
- A bold **stat band** right under the hero, using facts 365 already
  states about itself (50+ years combined experience, 3 core disciplines,
  4 markets served) — no invented numbers, styled like GRH's proof band.
- A 3-column **"Why 365 Technologies" trust section** — mirrors Anfield's
  "Why people choose us."
- An **Applications** page/section (new) — a real page using markets 365
  already claims to serve (automotive, agricultural, medical, industrial),
  styled as an icon-tile grid like GRH's application category grid.
- A dark **CTA band** before the footer and a **floating quick-contact
  widget** (call/email) — both patterns lifted from GRH.
- Heavier type (Inter, 800-weight headlines), a punchier accent color, and
  card hover elevation throughout instead of flat text blocks.

## What's in here

```
365-static/
├── package.json
├── server.js              # Express app: routes, contact form handling
├── railway.json             # pins the Node builder + start command
├── public/
│   ├── css/style.css
│   └── js/main.js
└── views/
    ├── partials/
    │   ├── header.ejs       # nav, logo, header CTA
    │   └── footer.ejs       # contact/social/quick links
    ├── home.ejs
    ├── what-we-do.ejs
    ├── gas-springs.ejs
    ├── hydraulic-design.ejs
    ├── pneumatic-design.ejs
    ├── who-we-are.ejs
    ├── applications.ejs     # new — markets served, icon-tile grid
    ├── contact-us.ejs       # real working form
    └── 404.ejs
```

## 1. Test locally first (2 minutes, no Docker needed)

```bash
npm install
npm start
```

Visit `http://localhost:3000`. If anything's broken, you'll see it
immediately in the terminal instead of in a Railway build log.

## 2. Push to GitHub, deploy on Railway

1. If you're replacing the old WordPress attempt in the same Railway
   service: delete the old `Dockerfile` and `wp-content/` etc. from the
   repo (or just point this at a **new** Railway service — cleaner, and
   keeps the old attempt around for reference if you want it later).
2. `git add . && git commit -m "365 Technologies staging site (Node/Express)"`, push.
3. Railway → **New Project → Deploy from GitHub repo** (or, if reusing
   the existing service, just push — it'll redeploy automatically).
   Railway will detect Node via `package.json` and build with Nixpacks —
   no Dockerfile needed, no MPM/Apache config to fight with.
4. **Settings → Networking → Generate Domain.** Railway's Node builder
   sets `PORT` automatically and this app reads `process.env.PORT`, so no
   port configuration needed on your end.
5. Deploy. That's it — no database to provision, no install wizard to run.

## 3. The contact form

`/contact-us` is a real form (First/Last name, Email, Subject, Message)
that posts to itself and validates server-side. By default — with no
extra configuration — submissions are just logged server-side (visible in
Railway's deploy/runtime logs) and the visitor still sees a normal success
message, so the form works end-to-end on staging right now.

To make it actually **send email**, set these variables on the Railway
service (Variables tab) — any standard SMTP provider works (Gmail app
password, Postmark, SendGrid SMTP, etc.):

```
SMTP_HOST = smtp.yourprovider.com
SMTP_PORT = 587
SMTP_SECURE = false
SMTP_USER = your-smtp-username
SMTP_PASS = your-smtp-password
SMTP_FROM = "365 Technologies Website" <info@go365tech.com>
```

Once `SMTP_HOST` is set, submissions email straight to `info@go365tech.com`
with reply-to set to whoever filled out the form.

## 4. What still needs your input

- **Images** — every photo slot (`feature-row__media`) is a labeled
  placeholder block, same as before. Drop real images into `public/img/`
  and swap the placeholder `<div>` for an `<img>` tag in the relevant
  `.ejs` file, or send me the files and I'll wire them in.
- **Logo** — currently a plain circular "365" mark in CSS. Swap for the
  real logo file the same way.
- **SMTP credentials** — for the contact form to actually deliver email
  (see above).

## 5. Converting this to WordPress later

When you're ready: the page structure (What We Do → 3 sub-pages, Who We
Are, Contact Us), the exact copy, and the design/CSS here map directly
onto WordPress page templates and a `functions.php`/theme build — this
site *is* the spec for that theme. That conversion is a separate,
scoped task whenever you want to kick it off.
