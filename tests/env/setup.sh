#!/usr/bin/env bash
# 建立隔離的 FuTheme 測試站：WordPress 7.1.4 + SQLite + PHP 內建伺服器。
# 只在本機；不連任何正式站、不使用正式資料。WordPress 本體不提交進 repo。
# 用法：tests/env/setup.sh [--reset]   之後 tests/env/serve.sh 啟動。
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
WP_VERSION="7.1.4"
WP_SHA1="b552c39cd95d89895b4e7a55af53a03687aff4f6"
SQLITE_VERSION="3.1.3"
WP_DIR="${FUTHEME_WP_DIR:-$ROOT/.wp-test}"
CACHE="$WP_DIR/.cache"

if [[ "${1:-}" == "--reset" ]]; then rm -rf "$WP_DIR"; fi
mkdir -p "$CACHE"

zip="$CACHE/wordpress-$WP_VERSION.zip"
[[ -f "$zip" ]] || curl -sSfL -o "$zip" "https://downloads.wordpress.org/release/wordpress-$WP_VERSION.zip"
echo "$WP_SHA1  $zip" | sha1sum -c --quiet

szip="$CACHE/sqlite-$SQLITE_VERSION.zip"
[[ -f "$szip" ]] || curl -sSfL -o "$szip" "https://downloads.wordpress.org/plugin/sqlite-database-integration.$SQLITE_VERSION.zip"

if [[ ! -f "$WP_DIR/site/wp-load.php" ]]; then
  tmp="$(mktemp -d)"; unzip -q "$zip" -d "$tmp"; mv "$tmp/wordpress" "$WP_DIR/site"; rm -rf "$tmp"
  unzip -q "$szip" -d "$WP_DIR/site/wp-content/plugins"
  cp "$WP_DIR/site/wp-content/plugins/sqlite-database-integration/db.copy" "$WP_DIR/site/wp-content/db.php"
fi
ln -sfn "$ROOT/themes/futheme-core" "$WP_DIR/site/wp-content/themes/futheme-core"

if [[ ! -f "$WP_DIR/site/wp-config.php" ]]; then
  php -r '
    $c = file_get_contents($argv[1] . "/wp-config-sample.php");
    $salt = "";
    foreach (["AUTH_KEY","SECURE_AUTH_KEY","LOGGED_IN_KEY","NONCE_KEY","AUTH_SALT","SECURE_AUTH_SALT","LOGGED_IN_SALT","NONCE_SALT"] as $k) {
      $c = preg_replace("/define\\( \x27$k\x27,\\s+\x27put your unique phrase here\x27 \\);/", "define( \x27$k\x27, \x27" . bin2hex(random_bytes(32)) . "\x27 );", $c);
    }
    $extra = "define( \x27WP_DEBUG\x27, true );\ndefine( \x27WP_DEBUG_LOG\x27, __DIR__ . \x27/../debug.log\x27 );\ndefine( \x27WP_DEBUG_DISPLAY\x27, false );\ndefine( \x27DISABLE_WP_CRON\x27, true );\ndefine( \x27AUTOMATIC_UPDATER_DISABLED\x27, true );\ndefine( \x27WP_ENVIRONMENT_TYPE\x27, \x27local\x27 );\ndefine( \x27DB_DIR\x27, __DIR__ . \x27/../db\x27 );\n";
    $c = str_replace("/* Add any custom values between this line and the \"stop editing\" line. */", "/* Add any custom values between this line and the \"stop editing\" line. */\n" . $extra, $c);
    $c = preg_replace("/define\\( \x27WP_DEBUG\x27, false \\);/", "", $c);
    file_put_contents($argv[1] . "/wp-config.php", $c);
  ' "$WP_DIR/site"
  mkdir -p "$WP_DIR/db"
fi

php "$ROOT/tests/env/install.php" "$WP_DIR/site"
echo "OK: $WP_DIR/site (WordPress $WP_VERSION, SQLite $SQLITE_VERSION)"
