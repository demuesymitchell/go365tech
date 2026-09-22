const express = require("express");
const path = require("path");
const nodemailer = require("nodemailer");

const app = express();
const PORT = process.env.PORT || 3000;

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views"));
app.use(express.static(path.join(__dirname, "public")));
app.use(express.urlencoded({ extended: true }));

const site = {
  name: "365 Technologies",
  address1: "8531 S Fwy Dr",
  address2: "Macedonia, OH 44056",
  phone: "(330) 468-3300",
  phoneHref: "+13304683300",
  email: "info@go365tech.com",
  mapEmbed:
    "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.272246814872!2d-81.51559257373954!3d41.32469299127964!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88311fd765855a89%3A0x4112c3dc73d1afc!2s8531+S+Fwy+Dr%2C+Macedonia%2C+OH+44056!5e0!3m2!1sen!2sus!4v1485887472207",
  social: {
    facebook: "https://facebook.com/",
    instagram: "https://instagram.com/",
    twitter: "https://twitter.com/",
    linkedin: "https://linkedin.com/",
  },
};

/**
 * Catalog data — a visual browsing structure, deliberately with no price
 * or cart fields. Categories map to 365's three real disciplines; the
 * items inside each are generic, industry-standard product *types* (not
 * fabricated model numbers or specs) meant to demonstrate the catalog
 * layout. Swap in real SKUs/photos/specs when the actual catalog is
 * ready — the structure won't need to change.
 */
const catalog = [
  {
    slug: "gas-springs",
    name: "Gas Springs",
    tagline: "Placeholder tagline — final copy coming soon.",
    description:
      "Placeholder category description. Final copy for Gas Springs will go here once available.",
    items: [
      {
        slug: "compression-gas-springs",
        name: "Compression Gas Springs",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Mounting", "Custom to application"],
          ["Stroke length", "Custom to application"],
          ["Force range", "Custom to application"],
        ],
      },
      {
        slug: "tension-gas-springs",
        name: "Tension Gas Springs",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Mounting", "Custom to application"],
          ["Stroke length", "Custom to application"],
          ["Force range", "Custom to application"],
        ],
      },
      {
        slug: "locking-gas-springs",
        name: "Locking Gas Springs",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Release", "Custom to application"],
          ["Stroke length", "Custom to application"],
          ["Force range", "Custom to application"],
        ],
      },
      {
        slug: "custom-mounting-hardware",
        name: "Custom Mounting Hardware",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Material options", "Custom to application"],
          ["Compatibility", "Custom to application"],
        ],
      },
    ],
  },
  {
    slug: "hydraulic-design",
    name: "Hydraulic Design",
    tagline: "Placeholder tagline — final copy coming soon.",
    description:
      "Placeholder category description. Final copy for Hydraulic Design will go here once available.",
    items: [
      {
        slug: "hydraulic-motors",
        name: "Hydraulic Motors",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
      {
        slug: "hydraulic-pumps",
        name: "Hydraulic Pumps",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
      {
        slug: "modular-gear-pumps",
        name: "Modular & Hi-Lo 2-Stage Gear Pumps",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
      {
        slug: "pistons-gears-vanes",
        name: "Pistons, Gears & Vanes",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
    ],
  },
  {
    slug: "pneumatic-design",
    name: "Pneumatic Design",
    tagline: "Placeholder tagline — final copy coming soon.",
    description:
      "Placeholder category description. Final copy for Pneumatic Design will go here once available.",
    items: [
      {
        slug: "air-preparation-hardware",
        name: "Air Preparation Hardware",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
      {
        slug: "pneumatic-valves",
        name: "Pneumatic Valves",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
      {
        slug: "pneumatic-cylinders",
        name: "Pneumatic Cylinders",
        blurb: "Placeholder product description — final copy coming soon.",
        specs: [
          ["Type", "Custom to application"],
          ["Configuration", "Custom to application"],
          ["Service", "Custom to application"],
        ],
      },
    ],
  },
];

function findCategory(slug) {
  return catalog.find((c) => c.slug === slug);
}
function findItem(categorySlug, itemSlug) {
  const cat = findCategory(categorySlug);
  if (!cat) return { cat: null, item: null };
  return { cat, item: cat.items.find((i) => i.slug === itemSlug) };
}

// Flat, searchable index of every item across every category.
function allItems() {
  return catalog.flatMap((c) =>
    c.items.map((i) => Object.assign({}, i, { categorySlug: c.slug, categoryName: c.name }))
  );
}

function searchCatalog(query) {
  const q = (query || "").trim().toLowerCase();
  if (!q) return [];
  return allItems().filter((i) => {
    const haystack = `${i.name} ${i.blurb} ${i.categoryName}`.toLowerCase();
    return haystack.includes(q);
  });
}

function base(extra = {}) {
  return { site, catalog, active: extra.active || "", title: extra.title, query: extra.query || "" };
}

app.get("/", (req, res) => {
  res.render("home", base({ active: "home", title: "365 Technologies" }));
});

app.get("/catalog", (req, res) => {
  res.render("catalog", base({ active: "catalog", title: "Catalog — 365 Technologies" }));
});

app.get("/search", (req, res) => {
  const q = req.query.q || "";
  const results = searchCatalog(q);
  res.render(
    "search",
    Object.assign(base({ active: "catalog", title: `Search — 365 Technologies`, query: q }), { results })
  );
});

app.get("/catalog/:category", (req, res, next) => {
  const cat = findCategory(req.params.category);
  if (!cat) return next();
  res.render(
    "catalog-category",
    Object.assign(base({ active: "catalog", title: `${cat.name} — 365 Technologies` }), { cat })
  );
});

app.get("/catalog/:category/:item", (req, res, next) => {
  const { cat, item } = findItem(req.params.category, req.params.item);
  if (!cat || !item) return next();
  const related = cat.items.filter((i) => i.slug !== item.slug).slice(0, 3);
  res.render(
    "catalog-product",
    Object.assign(base({ active: "catalog", title: `${item.name} — 365 Technologies` }), {
      cat,
      item,
      related,
    })
  );
});

app.get("/who-we-are", (req, res) => {
  res.render("who-we-are", base({ active: "who-we-are", title: "Who We Are — 365 Technologies" }));
});

app.get("/contact-us", (req, res) => {
  res.render(
    "contact-us",
    Object.assign(base({ active: "contact-us", title: "Contact Us — 365 Technologies" }), {
      submitted: false,
      errors: [],
      values: {},
    })
  );
});

app.post("/contact-us", async (req, res) => {
  const { first_name = "", last_name = "", email = "", subject = "", message = "" } = req.body;
  const errors = [];

  if (!first_name.trim() || !last_name.trim()) errors.push("Please enter your first and last name.");
  if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push("Please enter a valid email address.");
  if (!message.trim()) errors.push("Please enter a message.");

  if (errors.length) {
    return res.render(
      "contact-us",
      Object.assign(base({ active: "contact-us", title: "Contact Us — 365 Technologies" }), {
        submitted: false,
        errors,
        values: { first_name, last_name, email, subject, message },
      })
    );
  }

  try {
    if (process.env.SMTP_HOST) {
      const transporter = nodemailer.createTransport({
        host: process.env.SMTP_HOST,
        port: Number(process.env.SMTP_PORT || 587),
        secure: process.env.SMTP_SECURE === "true",
        auth: process.env.SMTP_USER
          ? { user: process.env.SMTP_USER, pass: process.env.SMTP_PASS }
          : undefined,
      });

      await transporter.sendMail({
        from: process.env.SMTP_FROM || `"365 Technologies Website" <${site.email}>`,
        to: site.email,
        replyTo: email,
        subject: `[365 Technologies Contact] ${subject || "New inquiry"}`,
        text: `Name: ${first_name} ${last_name}\nEmail: ${email}\nSubject: ${subject}\n\nMessage:\n${message}`,
      });
    } else {
      console.log("[contact form submission — SMTP not configured, logging only]", {
        first_name,
        last_name,
        email,
        subject,
        message,
      });
    }

    res.render(
      "contact-us",
      Object.assign(base({ active: "contact-us", title: "Contact Us — 365 Technologies" }), {
        submitted: true,
        errors: [],
        values: {},
      })
    );
  } catch (err) {
    console.error("Contact form send failed:", err);
    res.render(
      "contact-us",
      Object.assign(base({ active: "contact-us", title: "Contact Us — 365 Technologies" }), {
        submitted: false,
        errors: [`Sorry — something went wrong sending your message. Please email us directly at ${site.email}.`],
        values: { first_name, last_name, email, subject, message },
      })
    );
  }
});

app.use((req, res) => {
  res.status(404).render("404", base({ active: "", title: "Page not found — 365 Technologies" }));
});

app.listen(PORT, () => {
  console.log(`365 Technologies site running on port ${PORT}`);
});
