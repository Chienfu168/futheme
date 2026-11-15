#!/usr/bin/env python3
"""從 design/tokens.json 產生 theme.json 預設色盤與 styles/*.json。

用法：
  python3 scripts/build_styles.py          寫入檔案
  python3 scripts/build_styles.py --check  只比對；與 tokens 不一致時失敗（CI 用）

只改 theme.json 的 settings.color.palette，其餘欄位手動維護。
輸出固定排序與縮排，同一份 tokens 必得同一份檔案。
"""
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "themes/futheme"
SCHEMA = "https://schemas.wp.org/wp/7.1/theme.json"
# 色盤順序與編輯器顯示名稱；slug 為公開介面，變更需記錄相容性
ROLES = [
    ("primary", "Primary"),
    ("primary-contrast", "Primary Contrast"),
    ("secondary", "Secondary"),
    ("accent", "Accent"),
    ("base", "Base"),
    ("surface", "Surface"),
    ("contrast", "Contrast"),
    ("muted", "Muted"),
    ("border", "Border"),
    ("focus", "Focus"),
    ("success", "Success"),
    ("warning", "Warning"),
    ("danger", "Danger"),
]


def palette(colors):
    return [{"color": colors[slug], "name": name, "slug": slug} for slug, name in ROLES]


def dump(data):
    return json.dumps(data, ensure_ascii=False, indent="\t") + "\n"


def outputs():
    tokens = json.loads((ROOT / "design/tokens.json").read_text())
    by_slug = {p["slug"]: p for p in tokens["palettes"]}
    files = {}

    theme = json.loads((THEME / "theme.json").read_text())
    theme["settings"]["color"]["palette"] = palette(by_slug[tokens["defaultPalette"]]["colors"])
    files[THEME / "theme.json"] = dump(theme)

    for p in tokens["palettes"]:
        files[THEME / "styles" / f"{p['slug']}.json"] = dump({
            "$schema": SCHEMA,
            "version": 3,
            "title": p["title"],
            "slug": p["slug"],
            "settings": {"color": {"palette": palette(p["colors"])}},
        })
    return files


def main():
    check = "--check" in sys.argv
    files = outputs()
    expected = {f.name for f in files if f.parent.name == "styles"}
    extra = sorted(f.name for f in (THEME / "styles").glob("*.json") if f.name not in expected) if (THEME / "styles").exists() else []
    stale = [f for f, text in files.items() if not f.exists() or f.read_text() != text]
    if check:
        problems = [f"過期：{f.relative_to(ROOT)}" for f in stale] + [f"多餘：styles/{n}" for n in extra]
        if problems:
            raise SystemExit("\n".join(problems) + "\n請執行 python3 scripts/build_styles.py")
        print(f"PASS: theme.json 與 {len(expected)} 個 styles 與 tokens 一致")
        return
    if extra:
        raise SystemExit("styles/ 有 tokens 裡沒有的檔案，請先確認再手動刪除：" + ", ".join(extra))
    for f in stale:
        f.parent.mkdir(parents=True, exist_ok=True)
        f.write_text(files[f])
    print(f"寫入 {len(stale)} 個檔案（共 {len(files)} 個）")


if __name__ == "__main__":
    main()
