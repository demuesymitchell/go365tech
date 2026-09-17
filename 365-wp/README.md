# 365 Technologies — WordPress (Railway staging)

Real WordPress, running in Docker, deployable straight to Railway. Because
it's WordPress from day one, there's no "convert to WordPress later" step —
staging *is* the production stack. Going live later is a database
export/import + DNS change, not a rebuild.

## What's in here

```
365-wp/
├── Dockerfile                  # wordpress:php8.3-apache + theme + mu-plugin
├── railway.json                 # tells Railway to build via the Dockerfile
├── docker-compose.yml            # local dev only (WordPress + MySQL)
├── mu-plugins/
│   └── threesixfive-activate.php # auto-activates the theme post-install
└── wp-content/themes/threesixfive/
    ├── style.css                 # theme header + all site CSS
    ├── functions.php
    ├── header.php / footer.php
    ├── front-page.php            # homepage — ported from the live site
    ├── page.php                  # generic fallback template
    ├── page-what-we-do.php       # What We Do — alternating service rows
    ├── page-who-we-are.php       # Who We Are — Market Knowledge / Engineered Service
    ├── page-contact.php          # Contact Us — real working wp_mail() form
    ├── page-placeholder.php      # Products / Applications / Downloads
    ├── index.php                 # blog/archive fallback
    ├── inc/setup.php             # one-click "create pages & menu" tool
    └── assets/js/main.js         # mobile nav
```

## 1. Test locally first (optional but recommended)

```bash
docker compose up --build
```

Visit `http://localhost:8080`, run through the WordPress 5-minute install,
then go to **Appearance → 365 Setup** and click **Create Pages & Menu**.
That one click provisions every page (What We Do + its 3 sub-pages, Who We
Are, Contact Us, and the Products/Applications/Downloads/Blog placeholders)
and builds the nav menu automatically. The theme itself auto-activates on
your first wp-admin visit (see the mu-plugin), so you shouldn't need to
touch Appearance → Themes at all.

## 2. Push to GitHub, then Railway

1. `git init && git add . && git commit -m "365 Technologies WP theme"`, push to your GitHub repo.
2. In Railway: **New Project → Deploy from GitHub repo**, pick this repo. Railway will detect `railway.json`/`Dockerfile` and build the WordPress image automatically.
3. **Add a database**: in the same Railway project, click **+ New → Database → MySQL** (Railway's own MySQL plugin). Railway does not auto-wire this to the WordPress service — you set the four env vars below yourself, referencing the MySQL service's own variables.
4. On the **WordPress service → Variables**, add:
   ```
   WORDPRESS_DB_HOST     = ${{MySQL.MYSQLHOST}}
   WORDPRESS_DB_USER     = ${{MySQL.MYSQLUSER}}
   WORDPRESS_DB_PASSWORD = ${{MySQL.MYSQLPASSWORD}}
   WORDPRESS_DB_NAME     = ${{MySQL.MYSQLDATABASE}}
   ```
   (Railway lets you reference another service's variables with that
   `${{ServiceName.VAR}}` syntax in the Variables tab — pick them from the
   dropdown rather than typing manually so the service name matches exactly.)
5. Under **Settings → Networking**, click **Generate Domain** to get a
   public `*.up.railway.app` URL. The Dockerfile already reads
   `RAILWAY_PUBLIC_DOMAIN` (Railway sets this automatically) to configure
   `WP_HOME`/`WP_SITEURL` and fix HTTPS detection behind Railway's proxy —
   you don't need to hand-edit those.
6. Deploy. Visit the generated domain, run the WordPress installer (site
   title, admin user/password — do this immediately, it's a fresh public
   install), then go to **Appearance → 365 Setup** and click **Create Pages
   & Menu**.

## 3. Content status

All real page copy is now ported (from the screenshots you sent) and
seeded automatically by the **Appearance → 365 Setup** tool:

- **What We Do** — uses a dedicated alternating-row template
  (`page-what-we-do.php`) pulling from its three child pages automatically.
- **Gas Springs / Hydraulic Design / Pneumatic Design** — final copy baked
  in.
- **Who We Are** — dedicated template (`page-who-we-are.php`) with the
  "Market Knowledge" and "Engineered Service" sections.
- **Contact Us** — a real working form (First/Last name, Email, Subject,
  Message) that sends via `wp_mail()` to `info@go365tech.com`, plus the
  address/phone/email card.

  **wp_mail() caveat:** PHP's default mail() often can't deliver reliably
  from a container host like Railway (no local mail server, easy to land
  in spam, sometimes silently dropped). Before relying on the form in
  production, install an SMTP plugin — **WP Mail SMTP** is the standard
  choice — and connect it to a real provider (Gmail, Postmark, SES,
  etc.). Until then, test a submission and check your inbox/spam folder.

## 4. What still needs your input

- **Images**: none of the original site's photos are ported — I only had
  screenshots to read copy from, not the actual image files. Every photo
  slot (`feature-row__media` in the homepage, What We Do, and Who We Are
  templates) currently renders a labeled placeholder block. Upload real
  photos via Media Library and either set them as each page's Featured
  Image (What We Do's service rows already pull the child page's featured
  image automatically if one is set) or send me the files and I'll wire
  them in directly.
- **Logo**: set via **Appearance → Customize → Site Identity → Logo**
  once you upload the actual 365 Technologies logo files.
- **SMTP**, as noted above, before the contact form goes live for real.

## 5. Scaffolding for what's next

`Products`, `Applications`, `Downloads`, and `Blog` are real WordPress
pages/menu items already, using a `page-placeholder.php` template with a
"coming soon" block — so when you're ready to build out a product
catalog like Anfield/GRH, it's adding content and (if needed) a proper
category taxonomy, not restructuring the theme or nav.
