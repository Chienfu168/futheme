#!/usr/bin/env bash
# 在本機測試站裝上 fudawang 實際使用的公開外掛（版本與正式站相同，2026-11-20 本人提供清單）。
# 用法：bash tests/env/site-plugins.sh [--activate]
# 不含：UR AI Assistant、都更試算（ur-calc）——私人外掛，需另外取得原始碼。
# WPS Hide Login 啟用後 wp-login.php 會變 404（編輯器測試需在它啟用前跑）。
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
# shellcheck source=versions.env
source "$ROOT/tests/env/versions.env"
SITE="${FUTHEME_WP_DIR:-$ROOT/.wp-test}"
WP="php $SITE/.cache/wp-cli-$WPCLI_VERSION.phar --path=$SITE/wordpress"
[[ $EUID -eq 0 ]] && WP="$WP --allow-root"

# 前台會影響輸出的外掛
FRONT=(
  contact-form-7:6.2.5 wordpress-seo:28.9 google-site-kit:1.192.0 wp-fastest-cache:1.5.5
  performance-lab:4.2.0 optimization-detective:1.0.0-beta7 embed-optimizer:1.0.0-beta6
  auto-sizes:1.8.0 dominant-color-images:1.3.0 image-prioritizer:1.0.0-beta4
  nocache-bfcache:1.3.3 webp-uploads:2.7.1 performant-translations:1.2.0
  speculation-rules:1.7.0 view-transitions:1.2.1
)
# 只影響後台／登入的安全外掛
SECURITY=( limit-login-attempts-reloaded:3.4.5 wps-hide-login:1.9.20 wordfence:9.1.1 )

for pv in "${FRONT[@]}" "${SECURITY[@]}"; do
  $WP plugin install "${pv%%:*}" --version="${pv##*:}" --quiet
done
if [[ "${1:-}" == "--activate" ]]; then
  for pv in "${FRONT[@]}" "${SECURITY[@]}"; do $WP plugin activate "${pv%%:*}" --quiet; done
fi
$WP plugin list --fields=name,version,status
