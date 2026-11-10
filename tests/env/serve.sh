#!/usr/bin/env bash
# 啟動測試站 http://localhost:8890 （PHP 內建伺服器，僅供本機測試）
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
WP_DIR="${FUTHEME_WP_DIR:-$ROOT/.wp-test}"
cd "$WP_DIR/site"
exec php -S localhost:8890 "$ROOT/tests/env/router.php"
