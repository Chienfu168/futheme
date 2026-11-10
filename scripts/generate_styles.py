#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""從 design/tokens.json 產生 theme.json 的色盤與 styles/ 底下的十套 Style Variations。

為什麼要產生而不是手寫
──────────────────────
十套配色 × 十三個語意色 = 130 個欄位。手寫的話，打錯一個 slug 不會有人發現，
網站上就是某個地方顏色不對。色盤的唯一主資料是 design/tokens.json，
其他地方都是它的產物——這樣「兩邊不一致」這種錯誤在結構上就不可能發生。

用法
────
  python3 scripts/generate_styles.py          # 產生檔案
  python3 scripts/generate_styles.py --check  # 只檢查檔案跟 tokens 一致（CI 用，不寫檔）

--check 的退出碼：0 一致，1 不一致。
"""

import json
import os
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOKENS = os.path.join(ROOT, "design", "tokens.json")
THEME = os.path.join(ROOT, "themes", "futheme-core")
STYLES_DIR = os.path.join(THEME, "styles")
THEME_JSON = os.path.join(THEME, "theme.json")

# 語意色的顯示名稱。後台「樣式 → 顏色」會列出這些名字，所以要看得懂。
LABELS = {
    "primary": "主色",
    "primary-contrast": "主色上的文字",
    "secondary": "輔色",
    "accent": "強調色",
    "base": "底色",
    "surface": "區塊底色",
    "contrast": "文字",
    "muted": "次要文字",
    "border": "框線",
    "focus": "鍵盤焦點",
    "success": "成功",
    "warning": "警告",
    "danger": "錯誤",
}


def load_tokens():
    with open(TOKENS, encoding="utf-8") as f:
        return json.load(f)


def palette_entries(colors):
    """照 LABELS 的順序輸出，順序固定產出才可重現。"""
    out = []
    for slug in LABELS:
        if slug not in colors:
            raise SystemExit("tokens.json 少了語意色：%s" % slug)
        out.append({"slug": slug, "color": colors[slug], "name": LABELS[slug]})
    extra = set(colors) - set(LABELS)
    if extra:
        raise SystemExit("tokens.json 有未知的語意色：%s" % ", ".join(sorted(extra)))
    return out


def variation(palette):
    """一套 Style Variation。只覆寫色盤，其餘繼承 theme.json。"""
    return {
        "$schema": "https://schemas.wp.org/trunk/theme.json",
        "version": 3,
        "title": palette["title"],
        "settings": {"color": {"palette": palette_entries(palette["colors"])}},
    }


def base_theme_json(tokens, default_palette):
    """theme.json 本體。色盤放預設那一套；版型與排版設定寫在這裡，變體不重複。"""
    return {
        "$schema": "https://schemas.wp.org/trunk/theme.json",
        "version": 3,
        "settings": {
            "appearanceTools": True,
            "useRootPaddingAwareAlignments": True,
            "color": {
                "custom": True,
                "customDuotone": False,
                "customGradient": False,
                "defaultGradients": False,
                "defaultPalette": False,
                "palette": palette_entries(default_palette["colors"]),
            },
            "layout": {"contentSize": "48rem", "wideSize": "72rem"},
            "spacing": {
                "units": ["px", "rem", "%", "vw", "vh"],
                "spacingScale": {"steps": 7},
            },
            "typography": {
                "fluid": True,
                "fontFamilies": [
                    {
                        "slug": "system",
                        "name": "系統字型",
                        # 不載入遠端字型：效能預算與隱私都走這條（docs/performance-budget.md）。
                        "fontFamily": "-apple-system, BlinkMacSystemFont, \"Segoe UI\", "
                        "\"Noto Sans TC\", \"PingFang TC\", \"Microsoft JhengHei\", "
                        "\"Hiragino Sans GB\", sans-serif",
                    }
                ],
                "fontSizes": [
                    {"slug": "small", "name": "小", "size": "0.875rem",
                     "fluid": {"min": "0.8125rem", "max": "0.9375rem"}},
                    {"slug": "medium", "name": "中", "size": "1rem",
                     "fluid": {"min": "1rem", "max": "1.125rem"}},
                    {"slug": "large", "name": "大", "size": "1.5rem",
                     "fluid": {"min": "1.25rem", "max": "1.75rem"}},
                    {"slug": "x-large", "name": "特大", "size": "2.25rem",
                     "fluid": {"min": "1.75rem", "max": "2.75rem"}},
                ],
            },
        },
        "styles": {
            "color": {"background": "var(--wp--preset--color--base)",
                      "text": "var(--wp--preset--color--contrast)"},
            "typography": {
                "fontFamily": "var(--wp--preset--font-family--system)",
                "fontSize": "var(--wp--preset--font-size--medium)",
                "lineHeight": "1.7",
            },
            "spacing": {"padding": {"left": "var(--wp--preset--spacing--40)",
                                    "right": "var(--wp--preset--spacing--40)"}},
            "elements": {
                "link": {
                    "color": {"text": "var(--wp--preset--color--primary)"},
                    ":hover": {"typography": {"textDecoration": "underline"}},
                    # 鍵盤焦點一定要看得見。對比在 validate_spec.py 裡檢查。
                    ":focus": {"outline": {"width": "2px", "style": "solid",
                                           "color": "var(--wp--preset--color--focus)",
                                           "offset": "2px"}},
                },
                "heading": {"typography": {"lineHeight": "1.3", "fontWeight": "700"}},
                "button": {
                    "color": {"background": "var(--wp--preset--color--primary)",
                              "text": "var(--wp--preset--color--primary-contrast)"},
                    "border": {"radius": "0.25rem"},
                    "spacing": {"padding": {"top": "0.75rem", "bottom": "0.75rem",
                                            "left": "1.25rem", "right": "1.25rem"}},
                    ":hover": {"color": {"background": "var(--wp--preset--color--secondary)"}},
                    ":focus": {"outline": {"width": "2px", "style": "solid",
                                           "color": "var(--wp--preset--color--focus)",
                                           "offset": "2px"}},
                },
            },
        },
        "templateParts": [
            {"name": "header", "title": "頁首", "area": "header"},
            {"name": "footer", "title": "頁尾", "area": "footer"},
        ],
    }


def dump(obj):
    return json.dumps(obj, ensure_ascii=False, indent=2, sort_keys=False) + "\n"


def main():
    check = "--check" in sys.argv
    tokens = load_tokens()
    palettes = tokens["palettes"]
    default_slug = tokens["defaultPalette"]

    by_slug = {p["slug"]: p for p in palettes}
    if default_slug not in by_slug:
        raise SystemExit("defaultPalette %s 不在 palettes 裡" % default_slug)

    wanted = {"theme.json": (THEME_JSON, base_theme_json(tokens, by_slug[default_slug]))}
    for p in palettes:
        wanted["styles/%s.json" % p["slug"]] = (
            os.path.join(STYLES_DIR, p["slug"] + ".json"),
            variation(p),
        )

    if check:
        bad = []
        for label, (path, obj) in sorted(wanted.items()):
            if not os.path.exists(path):
                bad.append("%s 不存在" % label)
                continue
            with open(path, encoding="utf-8") as f:
                if f.read() != dump(obj):
                    bad.append("%s 跟 design/tokens.json 不一致" % label)
        # styles/ 底下不該有多出來的檔案
        if os.path.isdir(STYLES_DIR):
            expected = {p["slug"] + ".json" for p in palettes}
            for name in sorted(os.listdir(STYLES_DIR)):
                if name.endswith(".json") and name not in expected:
                    bad.append("styles/%s 不在 tokens.json 裡，是多出來的" % name)
        if bad:
            print("FAIL: 產生的檔案跟色盤主資料不一致")
            for b in bad:
                print("  - " + b)
            print("\n跑 python3 scripts/generate_styles.py 重新產生。")
            return 1
        print("PASS: theme.json 與 %d 套 Style Variations 都跟 design/tokens.json 一致。"
              % len(palettes))
        return 0

    os.makedirs(STYLES_DIR, exist_ok=True)
    for label, (path, obj) in sorted(wanted.items()):
        with open(path, "w", encoding="utf-8") as f:
            f.write(dump(obj))
        print("  ✓ %s" % label)
    print("產生了 theme.json 與 %d 套 Style Variations（預設 %s）。"
          % (len(palettes), default_slug))
    return 0


if __name__ == "__main__":
    sys.exit(main())
