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
    # 只檢查本專案自己的文件；第三方（vendor、node_modules）與本機測試站不檢查
    skip = {"vendor", "node_modules", ".wp-test", ".git"}
    for document in ROOT.rglob("*.md"):
        if skip.intersection(document.relative_to(ROOT).parts):
            continue
        for link in re.findall(r"\[[^\]]*\]\(([^)]+)\)", document.read_text()):
            if "://" in link or link.startswith("#"):
                continue
            if not (document.parent / link.split("#")[0]).exists():
                errors.append(f"Broken link: {document.relative_to(ROOT)} -> {link}")
    # README 標示的佈景版本必須和 style.css 一致（曾經停在 0.1.0 沒人發現）
    theme_version = re.search(r"^Version:\s*(\S+)", (ROOT / "themes/futheme/style.css").read_text(), re.M).group(1)
    readme_version = re.search(r"佈景版本：\*\*(\S+?)\*\*", (ROOT / "README.md").read_text())
    if not readme_version or readme_version.group(1) != theme_version:
        errors.append(f"README 佈景版本 {readme_version and readme_version.group(1)} ≠ style.css {theme_version}")
    # README 的直接下載連結必須指向目前版本的 ZIP
    if f"dist/futheme-{theme_version}-test.zip>" not in (ROOT / "README.md").read_text():
        errors.append(f"README 直接下載連結不是 futheme-{theme_version}-test.zip")
    if errors:
        raise SystemExit("\n".join(errors))
    print(f"PASS: 10 palettes, 13 tokens each, {checks} contrast pairs, local Markdown links. Lowest ratio: {minimum:.3f}:1 (includes 3:1 UI checks).")
    print("WordPress runtime, browser accessibility, plugin integration and performance: NOT TESTED.")

if __name__ == "__main__":
    main()
