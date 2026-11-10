# 測試

| 位置 | 用途 |
| --- | --- |
| `env/setup.sh` | 建立隔離測試站：WordPress 7.1.4（核對 SHA-1）＋ SQLite，放在 `.wp-test/`（不提交） |
| `env/serve.sh` | 以 PHP 內建伺服器啟動，預設 `http://localhost:8890`（`FUTHEME_PORT` 可改） |
| `env/seed.php` | 填入示範內容（文章、頁面、測試橫幅圖、表單示範、選單），無真實資料 |
| `env/set-variation.php` | 切換配色（寫入使用者 Global Styles，等同後台儲存）或 `reset` |
| `env/scenario.php` | `logo-on`／`logo-off`／`custom-primary <hex>` |
| `env/zip-install.php` | 以 `Theme_Upgrader`（後台上傳流程）安裝、更新或退回 ZIP |
| `e2e/frontend.js` | 前台檢查：版型、配色變數、對比、焦點、RWD、主控台錯誤 |
| `e2e/editor.js` | 網站編輯器檢查：版型與 template part 區塊是否有效 |
| `reports/` | 各次測試報告與證據 |

乾淨站範例：`FUTHEME_WP_DIR=$PWD/.wp-test-clean FUTHEME_LINK_THEME=0 FUTHEME_PORT=8891 tests/env/setup.sh`，
再 `php tests/env/zip-install.php .wp-test-clean/site dist/futheme-core-*.zip`。

不提交 WordPress 本體、資料庫、管理員密碼（`.wp-test/admin-password` 每次隨機產生）或任何正式資料。
