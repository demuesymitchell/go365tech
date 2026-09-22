hero-background.mp4 — in place, wired up in views/home.ejs.

This is your original "wave-placeholder.mp4", re-encoded for the web:
- Downscaled from 4K to 1920x1080 (the hero is dimmed under an overlay,
  so 4K was wasted detail for a background element)
- Re-encoded H.264, CRF 26 — visually the same, ~92% smaller
- Audio track stripped (the video plays muted anyway; there wasn't one
  to begin with, but this keeps future re-exports lean too)
- `faststart` flag set so the browser can begin playing before the
  whole file downloads

Original: 68 MB (4K, ~27 Mbps)
Now: 5.7 MB (1080p, ~2.3 Mbps)

hero-poster.jpg is the video's first frame, used as the `poster`
attribute on the `<video>` tag — it shows instantly while the video
file is still loading, instead of a blank flash.

To replace this with a different video later: drop the new file in as
`hero-background.mp4` (same filename — re-encoding it the same way,
roughly 1080p / CRF 24-28 / no audio / faststart, is recommended so it
stays light enough to autoplay smoothly) and regenerate hero-poster.jpg
from its first frame, or just reuse any frame you like.
