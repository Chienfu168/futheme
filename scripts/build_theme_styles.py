#!/usr/bin/env python3
"""從 design/tokens.json 產生佈景配色檔。

- themes/futheme-core/theme.json 的 settings.color.palette（預設配色）
- themes/futheme-core/styles/{slug}.json（十套 Style Variations）

只改這些欄位，theme.json 其餘內容手寫維護。輸出可重現（固定排序與縮排）。
用法：python3 scripts/build_theme_styles.py [--check]
--check：只比對，不寫入；若產物與 tokens 不一致則失敗（CI 用）。
"""
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "themes/futheme-core"
NAMES = {
    "primary": "主色 Primary",
    "primary-contrast": "主色上的文字 Primary contrast",
    "secondary": "輔色 Secondary",
    "accent": "強調色 Accent",
    "base": "底色 Base",
    "surface": "淺底 Surface",
    "contrast": "內文 Contrast",
    "muted": "次要文字 Muted",
    "border": "邊框 Border",
    "focus": "焦點 Focus",
    "success": "成功 Success",
    "warning": "警告 Warning",
    "danger": "危險 Danger",
}
SCHEMA = "https://schemas.wp.org/wp/7.1/theme.json"


def palette(colors):
    return [{"slug": slug, "color": colors[slug], "name": NAMES[slug]} for slug in NAMES]


def dump(data):
    return json.dumps(data, ensure_ascii=False, indent="\t") + "\n"


def outputs():
    tokens = json.loads((ROOT / "design/tokens.json").read_text())
    by_slug = {p["slug"]: p for p in tokens["palettes"]}
    files = {}
    theme_path = THEME / "theme.json"
    theme = json.loads(theme_path.read_text())
    theme["settings"]["color"]["palette"] = palette(by_slug[tokens["defaultPalette"]]["colors"])
    theme["settings"]["custom"]["futheme"]["paletteVersion"] = tokens["paletteVersion"]
    files[theme_path] = dump(theme)
    for p in tokens["palettes"]:
        files[THEME / "styles" / f"{p['slug']}.json"] = dump({
            "$schema": SCHEMA,
            "version": 3,
            "title": p["title"],
            "settings": {"color": {"palette": palette(p["colors"])}},
        })
    return files


def main():
    check = "--check" in sys.argv
    stale = []
    for path, content in outputs().items():
        current = path.read_text() if path.exists() else None
        if current != content:
            stale.append(path.relative_to(ROOT))
            if not check:
                path.write_text(content)
    if check and stale:
        raise SystemExit("過期，請執行 python3 scripts/build_theme_styles.py：\n" + "\n".join(map(str, stale)))
    print(("CHECK OK" if check else "已寫入") + f"：theme.json palette + {len(outputs()) - 1} 個 styles；變更 {len(stale)} 檔")


if __name__ == "__main__":
    main()
