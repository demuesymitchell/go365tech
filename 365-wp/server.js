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
    tagline: "Custom gas spring design, sometimes called \"gas shocks.\"",
    description:
      "Sometimes referred to as \"gas shocks,\" gas springs are a core part of the 365 Technologies proficiency. We're gas spring designers capable of producing the perfect custom component or product solution based on specifications and creative gas spring design.",
    items: [
      {
        slug: "compression-gas-springs",
        name: "Compression Gas Springs",
        blurb: "Extend under load — the most common gas spring configuration, used to lift, support, or counterbalance.",
        specs: [
          ["Type", "Compression (extending)"],
          ["Mounting", "Ball stud, eyelet, or custom to application"],
          ["Stroke length", "Custom to application"],
          ["Force range", "Custom to application"],
        ],
      },
      {
        slug: "tension-gas-springs",
        name: "Tension Gas Springs",
        blurb: "Retract under load — used where the spring needs to pull rather than push.",
        specs: [
          ["Type", "Tension (retracting)"],
          ["Mounting", "Ball stud, eyelet, or custom to application"],
          ["Stroke length", "Custom to application"],
          ["Force range", "Custom to application"],
        ],
      },
      {
        slug: "locking-gas-springs",
        name: "Locking Gas Springs",
        blurb: "Hold position at any point in the stroke via a manual or push-button release mechanism.",
        specs: [
          ["Type", "Locking / position-hold"],
          ["Release", "Manual lever or push-button"],
          ["Stroke length", "Custom to application"],
          ["Force range", "Custom to application"],
        ],
      },
      {
        slug: "custom-mounting-hardware",
        name: "Custom Mounting Hardware",
        blurb: "Ball studs, eyelets, and brackets engineered around your specific installation.",
        specs: [
          ["Type", "Mounting hardware"],
          ["Material options", "Steel, stainless, custom"],
          ["Compatibility", "Engineered to your assembly"],
        ],
      },
    ],
  },
  {
    slug: "hydraulic-design",
    name: "Hydraulic Design",
    tagline: "Full-capability hydraulic design, build, and service.",
    description:
      "365 Technologies offers a full capability suite of hydraulic design services. We build and fabricate hydraulics, offer educational instruction and continuing hydraulic education certifications. We also service hydraulic motors, hydraulic pumps, pistons, gears, vanes, modular pumps and hi-lo 2 stage gear pumps.",
    items: [
      {
        slug: "hydraulic-motors",
        name: "Hydraulic Motors",
        blurb: "Converts hydraulic fluid power into rotational mechanical output.",
        specs: [
          ["Type", "Hydraulic motor"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
        ],
      },
      {
        slug: "hydraulic-pumps",
        name: "Hydraulic Pumps",
        blurb: "Drives system flow — sized and specified around your circuit's demands.",
        specs: [
          ["Type", "Hydraulic pump"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
        ],
      },
      {
        slug: "modular-gear-pumps",
        name: "Modular & Hi-Lo 2-Stage Gear Pumps",
        blurb: "Gear pump assemblies including hi-lo 2-stage configurations for dual-flow circuits.",
        specs: [
          ["Type", "Gear pump — modular / hi-lo 2-stage"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
        ],
      },
      {
        slug: "pistons-gears-vanes",
        name: "Pistons, Gears & Vanes",
        blurb: "Core internal components serviced and specified as part of a full hydraulic system.",
        specs: [
          ["Type", "Piston / gear / vane components"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
        ],
      },
    ],
  },
  {
    slug: "pneumatic-design",
    name: "Pneumatic Design",
    tagline: "From air prep hardware through valve-and-cylinder pairing.",
    description:
      "365 Technologies provides pneumatic system design support starting at the connection to a machine's air preparation hardware and continues to correctly pairing valves with cylinders to ensure safe machine operation. We also offer on-site troubleshooting and service across agriculture, automotive, aerial and lift trucks, patient handling, medical, and industrial markets.",
    items: [
      {
        slug: "air-preparation-hardware",
        name: "Air Preparation Hardware",
        blurb: "Filters, regulators, and lubricators at the machine's air supply connection.",
        specs: [
          ["Type", "Air prep (FRL) hardware"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
        ],
      },
      {
        slug: "pneumatic-valves",
        name: "Pneumatic Valves",
        blurb: "Directional control valves specified and paired to match cylinder requirements.",
        specs: [
          ["Type", "Pneumatic directional control valve"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
        ],
      },
      {
        slug: "pneumatic-cylinders",
        name: "Pneumatic Cylinders",
        blurb: "Actuation components sized and paired with valves for safe machine operation.",
        specs: [
          ["Type", "Pneumatic cylinder"],
          ["Configuration", "Custom to application"],
          ["Service", "On-site troubleshooting available"],
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

function base(extra = {}) {
  return { site, catalog, active: extra.active || "", title: extra.title };
}

app.get("/", (req, res) => {
  res.render("home", base({ active: "home", title: "365 Technologies" }));
});

app.get("/catalog", (req, res) => {
  res.render("catalog", base({ active: "catalog", title: "Catalog — 365 Technologies" }));
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
