Drop the homepage hero background video here as:

    hero-background.mp4

The homepage hero (`views/home.ejs`) already references
`/video/hero-background.mp4` as a background video (autoplay, muted,
looped, no controls) behind the search bar. Nothing else needs to
change — once the file exists at this path, it'll play automatically.

Notes:
- Keep it reasonably small (a few MB, not tens) since it autoplays for
  every visitor — an already-compressed, short (10–20s), looping clip
  works best.
- MP4 (H.264) is the safest format for broad browser support.
- If no file is present, the hero still looks intentional — it falls
  back to a solid navy gradient behind the dark overlay, so the page
  never shows a broken video icon.
