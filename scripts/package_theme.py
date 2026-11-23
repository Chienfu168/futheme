#!/usr/bin/env python3
"""打包 futheme 為可上傳的 ZIP（根目錄 futheme/）。

用法：python3 scripts/package_theme.py [後綴，如 test]
輸出：dist/futheme-<版本>[-後綴].zip、同名 .sha256 與 .files.txt，以及 repo 根目錄的 update.json（線上更新用）
固定檔案順序與時間戳，同一份原始檔得到同一個雜湊。
"""
import hashlib
import json
import re
import sys
import zipfile
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "themes/futheme"
EXCLUDE = {".DS_Store", "Thumbs.db"}

version = re.search(r"^Version:\s*(\S+)", (THEME / "style.css").read_text(), re.M).group(1)
suffix = f"-{sys.argv[1]}" if len(sys.argv) > 1 else ""
dist = ROOT / "dist"
dist.mkdir(exist_ok=True)
out = dist / f"futheme-{version}{suffix}.zip"

files = sorted(p for p in THEME.rglob("*") if p.is_file() and p.name not in EXCLUDE)
with zipfile.ZipFile(out, "w", zipfile.ZIP_DEFLATED) as z:
    for p in files:
        info = zipfile.ZipInfo(f"futheme/{p.relative_to(THEME).as_posix()}", (2026, 1, 1, 0, 0, 0))
        info.external_attr = 0o644 << 16
        info.compress_type = zipfile.ZIP_DEFLATED
        z.writestr(info, p.read_bytes())

digest = hashlib.sha256(out.read_bytes()).hexdigest()
out.with_suffix(".sha256").write_text(f"{digest}  {out.name}\n")
out.with_suffix(".files.txt").write_text("".join(f"futheme/{p.relative_to(THEME).as_posix()}\n" for p in files))
print(f"{out.relative_to(ROOT)}  {len(files)} 檔  {out.stat().st_size} bytes\nSHA-256 {digest}")

# 線上更新的版本檔：佈景只讀 main 分支上的這個檔，合併進 main 才會推給網站
header = (THEME / "style.css").read_text()
def field(name):
    m = re.search(rf"^{name}:\s*(.+)$", header, re.M)
    return m.group(1).strip() if m else ""
manifest = {
    "version": version,
    "package": f"https://raw.githubusercontent.com/Chienfu168/futheme/main/dist/{out.name}",
    "sha256": digest,
    "url": "https://github.com/Chienfu168/futheme",
    "requires": field("Requires at least"),
    "requires_php": field("Requires PHP"),
    "tested": field("Tested up to"),
}
(ROOT / "update.json").write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + "\n")
print("update.json 已更新")
