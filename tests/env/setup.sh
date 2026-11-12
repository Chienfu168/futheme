#!/usr/bin/env bash
# 建立一次性本機 WordPress 測試站（PHP 內建伺服器 + SQLite）。
# 用法：tests/env/setup.sh [--reset]
# 站台目錄預設 <repo>/.wp-test（已 gitignore），可用 FUTHEME_WP_DIR 改。
# 不連正式站、不寄信、不跑排程、禁止索引；管理員密碼只印在終端，不寫入檔案。
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
# shellcheck source=versions.env
source "$ROOT/tests/env/versions.env"
SITE="${FUTHEME_WP_DIR:-$ROOT/.wp-test}"
CACHE="$SITE/.cache"
WEB="$SITE/wordpress"
PORT="${FUTHEME_WP_PORT:-8888}"
URL="http://localhost:$PORT"

php -r "exit(version_compare(PHP_VERSION, '$PHP_MIN', '>=') ? 0 : 1);" \
  || { echo "需要 PHP >= $PHP_MIN，目前 $(php -r 'echo PHP_VERSION;')" >&2; exit 1; }
php -m | grep -qi '^pdo_sqlite$' || { echo "PHP 缺 pdo_sqlite" >&2; exit 1; }

if [[ "${1:-}" == "--reset" ]]; then rm -rf "$WEB"; fi
mkdir -p "$CACHE"

fetch() { # url file sha256
  local url="$1" out="$CACHE/$2" sum="$3"
  [[ -f "$out" ]] || curl -sSfL -o "$out" "$url"
  echo "$sum  $out" | sha256sum -c --quiet - || { rm -f "$out"; echo "SHA-256 不符：$2" >&2; exit 1; }
}
fetch "https://downloads.wordpress.org/release/wordpress-$WP_VERSION.zip" "wordpress-$WP_VERSION.zip" "$WP_SHA256"
fetch "https://downloads.wordpress.org/plugin/sqlite-database-integration.$SQLITE_PLUGIN_VERSION.zip" "sqlite-$SQLITE_PLUGIN_VERSION.zip" "$SQLITE_PLUGIN_SHA256"
fetch "https://github.com/wp-cli/wp-cli/releases/download/v$WPCLI_VERSION/wp-cli-$WPCLI_VERSION.phar" "wp-cli-$WPCLI_VERSION.phar" "$WPCLI_SHA256"
WP="php $CACHE/wp-cli-$WPCLI_VERSION.phar --path=$WEB"

if [[ -f "$WEB/wp-config.php" ]]; then
  echo "已存在：$WEB（要重建請加 --reset）"
else
  mkdir -p "$SITE"
  unzip -q "$CACHE/wordpress-$WP_VERSION.zip" -d "$SITE"
  unzip -q "$CACHE/sqlite-$SQLITE_PLUGIN_VERSION.zip" -d "$WEB/wp-content/plugins"
  sed "s#{SQLITE_IMPLEMENTATION_FOLDER_PATH}#$WEB/wp-content/plugins/sqlite-database-integration#; s#{SQLITE_PLUGIN}#sqlite-database-integration/load.php#" \
    "$WEB/wp-content/plugins/sqlite-database-integration/db.copy" > "$WEB/wp-content/db.php"

  $WP config create --dbname=futheme --dbuser=unused --dbpass=unused --skip-check --quiet --extra-php <<'PHP'
define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'DISABLE_WP_CRON', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
PHP
  pass="$(php -r 'echo bin2hex(random_bytes(12));')"
  $WP core install --url="$URL" --title="FuTheme 測試站" --admin_user=admin \
    --admin_password="$pass" --admin_email=admin@example.invalid --skip-email --quiet
  $WP option update blog_public 0 --quiet
  $WP option update permalink_structure '/%postname%/' --quiet

  # 不寄出任何信件
  mkdir -p "$WEB/wp-content/mu-plugins"
  cat > "$WEB/wp-content/mu-plugins/futheme-test-guard.php" <<'PHP'
<?php
// 測試站專用：攔截所有寄信。
add_filter( 'pre_wp_mail', '__return_false' );
PHP
  echo "管理員：admin／$pass（只顯示這一次；忘記時用 wp user update admin --user_pass=...）"
fi

# 佈景以 symlink 接上，改檔即生效
for theme in "$ROOT"/themes/*/; do
  name="$(basename "$theme")"
  [[ -f "$theme/style.css" ]] && ln -sfn "$theme" "$WEB/wp-content/themes/$name"
done

cat <<EOF
環境：PHP $(php -r 'echo PHP_VERSION;')、WordPress $($WP core version)、SQLite 外掛 $SQLITE_PLUGIN_VERSION、WP-CLI $WPCLI_VERSION
啟動：php -S localhost:$PORT -t "$WEB"
WP-CLI：$WP <指令>
EOF
