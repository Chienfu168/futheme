#!/usr/bin/env python3
"""Read-only M0 checks. Run from any working directory; no dependencies."""
import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
REQUIRED = {"primary", "primary-contrast", "secondary", "accent", "base", "surface", "contrast", "muted", "border", "focus", "success", "warning", "danger"}
EXPECTED = {"urban-blue", "tech-blue", "forest-green", "business-green", "warm-orange", "coral-red", "creative-purple", "graphite", "classic-gold", "aqua-blue"}

def luminance(color):
    channels = [int(color[i:i+2], 16) / 255 for i in (1, 3, 5)]
    linear = [c / 12.92 if c <= 0.04045 else ((c + 0.055) / 1.055) ** 2.4 for c in channels]
    return sum(c * w for c, w in zip(linear, (0.2126, 0.7152, 0.0722)))

def lab(color):
    channels = [int(color[i:i+2], 16) / 255 for i in (1, 3, 5)]
    r, g, b = (c / 12.92 if c <= 0.04045 else ((c + 0.055) / 1.055) ** 2.4 for c in channels)
    xyz = ((0.4124 * r + 0.3576 * g + 0.1805 * b) / 0.95047, 0.2126 * r + 0.7152 * g + 0.0722 * b, (0.0193 * r + 0.1192 * g + 0.9505 * b) / 1.08883)
    fx, fy, fz = (t ** (1 / 3) if t > 0.008856 else 7.787 * t + 16 / 116 for t in xyz)
    return (116 * fy - 16, 500 * (fx - fy), 200 * (fy - fz))

def delta_e(a, b):
    """CIE76 color difference; accent must not read as a status color."""
    return sum((x - y) ** 2 for x, y in zip(lab(a), lab(b))) ** 0.5

MIN_ACCENT_STATUS_DELTA_E = 20.0

def ratio(a, b):
    a, b = sorted((luminance(a), luminance(b)))
    return (b + 0.05) / (a + 0.05)

def main():
    errors = []
    data = json.loads((ROOT / "design/tokens.json").read_text())
    palettes = data["palettes"]
    if len(palettes) != 10 or {p["slug"] for p in palettes} != EXPECTED:
        errors.append("Palette slugs/count mismatch")
    if data.get("schemaVersion") != 1 or data.get("defaultPalette") not in EXPECTED:
        errors.append("Invalid metadata")
    checks = 0
    minimum = 100.0
    distinct = 0
    min_delta = 1000.0
    for palette in palettes:
        colors = palette["colors"]
        if set(colors) != REQUIRED or any(not re.fullmatch(r"#[0-9A-F]{6}", c) for c in colors.values()):
            errors.append(f"Invalid tokens: {palette['slug']}")
            continue
        pairs = [(fg, bg, 4.5) for bg in ("base", "surface") for fg in ("contrast", "muted", "primary", "secondary", "accent", "success", "warning", "danger")]
        pairs += [("primary-contrast", bg, 4.5) for bg in ("primary", "secondary", "accent", "success", "warning", "danger")]
        pairs += [(fg, bg, 3.0) for fg in ("border", "focus") for bg in ("base", "surface")]
        for fg, bg, target in pairs:
            value = ratio(colors[fg], colors[bg])
            checks += 1
            minimum = min(minimum, value)
            if value < target:
                errors.append(f"{palette['slug']} {fg}/{bg}: {value:.3f} < {target}")
        for status in ("success", "warning", "danger"):
            value = delta_e(colors["accent"], colors[status])
            distinct += 1
            min_delta = min(min_delta, value)
            if value < MIN_ACCENT_STATUS_DELTA_E:
                errors.append(f"{palette['slug']} accent/{status}: deltaE {value:.1f} < {MIN_ACCENT_STATUS_DELTA_E}")
    for document in ROOT.rglob("*.md"):
        for link in re.findall(r"\[[^\]]*\]\(([^)]+)\)", document.read_text()):
            if "://" in link or link.startswith("#"):
                continue
            if not (document.parent / link.split("#")[0]).exists():
                errors.append(f"Broken link: {document.relative_to(ROOT)} -> {link}")
    if errors:
        raise SystemExit("\n".join(errors))
    print(f"PASS: 10 palettes, 13 tokens each, {checks} contrast pairs, {distinct} accent/status distinctness pairs, local Markdown links. Lowest ratio: {minimum:.3f}:1 (includes 3:1 UI checks); lowest accent/status deltaE: {min_delta:.1f}.")
    print("WordPress runtime, browser accessibility, plugin integration and performance: NOT TESTED.")

if __name__ == "__main__":
    main()
