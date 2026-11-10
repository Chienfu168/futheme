#!/usr/bin/env bash
# 打包 FuTheme Core 測試 ZIP：先跑檢查，ZIP 根目錄為 futheme-core/，輸出檔案清單與 SHA-256。
# 用法：scripts/package_theme.sh   產物在 dist/（不提交）
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
THEME="$ROOT/themes/futheme-core"
cd "$ROOT"
python3 scripts/validate_spec.py >/dev/null
python3 scripts/build_theme_styles.py --check >/dev/null
find "$THEME" -name '*.php' -print0 | xargs -0 -n1 php -l >/dev/null
for f in "$THEME"/theme.json "$THEME"/styles/*.json "$THEME"/styles/sections/*.json; do python3 -m json.tool "$f" >/dev/null; done
VERSION="$(sed -n 's/^Version: *//p' "$THEME/style.css" | tr -d '\r')"
mkdir -p dist
NAME="futheme-core-$VERSION"
ZIP="$ROOT/dist/$NAME.zip"
rm -f "$ZIP"
# 只打包佈景檔案；排除 README（開發說明）、隱藏檔與 node_modules。
(cd "$ROOT/themes" && find futheme-core -type f ! -name 'README.md' ! -path '*/.*' ! -path '*/node_modules/*' | LC_ALL=C sort > "$ROOT/dist/$NAME.files.txt" && TZ=UTC zip -X -q "$ZIP" -@ < "$ROOT/dist/$NAME.files.txt")
(cd dist && sha256sum "$NAME.zip" > "$NAME.zip.sha256")
echo "version=$VERSION"
echo "files=$(wc -l < "dist/$NAME.files.txt")"
cat "dist/$NAME.zip.sha256"
