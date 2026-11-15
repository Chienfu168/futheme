#!/usr/bin/env python3
"""以 tests/env/versions.env 鎖定的 schema 驗證 theme.json 與 styles/*.json。

需要 python3-jsonschema。schema 下載後快取在 .wp-test/.cache，並核對 SHA-256。
"""
import hashlib
import json
import sys
import urllib.request
from pathlib import Path

import jsonschema

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "themes/futheme"
env = dict(
    line.split("=", 1)
    for line in (ROOT / "tests/env/versions.env").read_text().splitlines()
    if "=" in line and not line.startswith("#")
)
cache = ROOT / ".wp-test/.cache/theme-schema-wp-7.1.json"
if not cache.exists():
    cache.parent.mkdir(parents=True, exist_ok=True)
    with urllib.request.urlopen(env["THEME_SCHEMA_URL"]) as r:
        cache.write_bytes(r.read())
digest = hashlib.sha256(cache.read_bytes()).hexdigest()
if digest != env["THEME_SCHEMA_SHA256"]:
    cache.unlink()
    sys.exit(f"schema SHA-256 不符（{digest}），上游可能已修改，請人工確認後更新 versions.env")

validator = jsonschema.Draft7Validator(json.loads(cache.read_text()))
files = [THEME / "theme.json", *sorted((THEME / "styles").glob("*.json"))]
errors = []
for f in files:
    for e in validator.iter_errors(json.loads(f.read_text())):
        errors.append(f"{f.relative_to(ROOT)}: {'/'.join(map(str, e.absolute_path))}: {e.message[:200]}")
if errors:
    sys.exit("\n".join(errors))
print(f"PASS: {len(files)} 個 JSON 符合 WordPress 7.1 theme.json schema")
