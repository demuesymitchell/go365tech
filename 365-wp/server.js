const express = require("express");
const path = require("path");
const nodemailer = require("nodemailer");

const app = express();
const PORT = process.env.PORT || 3000;

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views"));
app.use(express.static(path.join(__dirname, "public")));
app.use(express.urlencoded({ extended: true }));

// Shared site data available to every template.
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

function render(view, extra = {}) {
  return (req, res) => res.render(view, { site, active: extra.active || "", title: extra.title });
}

app.get("/", render("home", { active: "home", title: "365 Technologies" }));

app.get(
  "/what-we-do",
  render("what-we-do", { active: "what-we-do", title: "What We Do — 365 Technologies" })
);
app.get(
  "/gas-springs",
  render("gas-springs", { active: "what-we-do", title: "Gas Springs — 365 Technologies" })
);
app.get(
  "/hydraulic-design",
  render("hydraulic-design", { active: "what-we-do", title: "Hydraulic Design — 365 Technologies" })
);
app.get(
  "/pneumatic-design",
  render("pneumatic-design", { active: "what-we-do", title: "Pneumatic Design — 365 Technologies" })
);

app.get(
  "/who-we-are",
  render("who-we-are", { active: "who-we-are", title: "Who We Are — 365 Technologies" })
);

app.get(
  "/applications",
  render("applications", { active: "applications", title: "Applications — 365 Technologies" })
);

app.get("/contact-us", (req, res) => {
  res.render("contact-us", {
    site,
    active: "contact-us",
    title: "Contact Us — 365 Technologies",
    submitted: false,
    errors: [],
    values: {},
  });
});

app.post("/contact-us", async (req, res) => {
  const { first_name = "", last_name = "", email = "", subject = "", message = "" } = req.body;
  const errors = [];

  if (!first_name.trim() || !last_name.trim()) errors.push("Please enter your first and last name.");
  if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push("Please enter a valid email address.");
  if (!message.trim()) errors.push("Please enter a message.");

  if (errors.length) {
    return res.render("contact-us", {
      site,
      active: "contact-us",
      title: "Contact Us — 365 Technologies",
      submitted: false,
      errors,
      values: { first_name, last_name, email, subject, message },
    });
  }

  // Email delivery only fires if SMTP env vars are set (see README). Without
  // them the submission is just logged server-side so the form still works
  // end-to-end on staging without requiring real credentials yet.
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

    res.render("contact-us", {
      site,
      active: "contact-us",
      title: "Contact Us — 365 Technologies",
      submitted: true,
      errors: [],
      values: {},
    });
  } catch (err) {
    console.error("Contact form send failed:", err);
    res.render("contact-us", {
      site,
      active: "contact-us",
      title: "Contact Us — 365 Technologies",
      submitted: false,
      errors: [`Sorry — something went wrong sending your message. Please email us directly at ${site.email}.`],
      values: { first_name, last_name, email, subject, message },
    });
  }
});

// Simple 404.
app.use((req, res) => {
  res.status(404).render("404", { site, active: "", title: "Page not found — 365 Technologies" });
});

app.listen(PORT, () => {
  console.log(`365 Technologies site running on port ${PORT}`);
});
