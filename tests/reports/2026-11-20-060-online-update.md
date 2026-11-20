# 0.6.0 線上更新（2026-11-20）

本人同意實作線上更新。做法、發布流程與限制見 [部署文件](../../docs/deployment.md#線上更新060-起)。

## 改動

| 項目 | 內容 |
| --- | --- |
| `inc/updater.php` | `update_themes_github.com` 讀 main 分支 `update.json`；`upgrader_pre_download` 比對 SHA-256；版本檔快取 12 小時（失敗 1 小時）；更新完成或進「更新」頁時重讀 |
| 下載網址限制 | 只接受 https；`WP_ENVIRONMENT_TYPE=local` 的測試站另允許 `http://localhost` |
| `update.json` | 由 `package_theme.py` 自動產生；`validate_spec.py` 檢查版本、網址、雜湊與 dist 一致 |
| 翻譯 | 新增「更新檔的 SHA-256 檢查碼不符」繁中訊息 |
| 版本 | 0.5.1 → **0.6.0** |

## 驗證（乾淨測試站，WordPress 7.1.4、PHP 8.3.6、不 symlink 佈景）

假的新版 0.6.1（只改版號）放在測試站同主機，以測試用 mu-plugin 把版本檔網址指過去。

| 情境 | 結果 |
| --- | --- |
| 0.5.1 →（後台上傳）0.6.0 | 通過：比較畫面、更新成功 |
| 偵測新版 | 通過：`wp theme list` 顯示 0.6.1 available；佈景頁顯示「有新版本」；「控制台 → 更新」列出「已安裝 0.6.0，更新為 0.6.1」 |
| **SHA-256 不符** | **擋下**：顯示「The FuTheme update file did not match its checksum and was not installed.」，版本維持 0.6.0，前台 200 |
| 後台「更新」頁按更新 | 通過：「FuTheme updated successfully」，版本 0.6.1、維持啟用、前台 200、無 PHP 錯誤 |
| 版本檔取不到（404） | 通過：不顯示更新、網站正常；失敗結果快取 1 小時，不會每次進後台都連線 |
| 第三方佈景同樣用 github.com 的 Update URI | 程式只處理目錄名稱為 `futheme` 的佈景 |

截圖：[更新頁](img/2026-11-20-060-update-core.png)。

測試中瀏覽器記錄到 1 則 JavaScript 錯誤（只顯示為 `Object`），發生在 WordPress 更新進度頁；事後在佈景、更新、控制台頁重跑都無法重現。FuTheme 不含任何 JavaScript，判斷與佈景無關，但未能確認來源。

測試時下載網址用 8891 埠失敗（「A valid URL was not provided」）：WordPress 的安全下載只允許 80／443／8080 埠或與網站相同的主機與埠，屬測試環境限制，改用測試站本身的埠後才測到檢查碼。正式網址是 `https://raw.githubusercontent.com`（443 埠），不受影響。

ZIP：`dist/futheme-0.6.0-test.zip`，SHA-256 見 [update.json](../../update.json)。

## 未驗證

- 真正從 GitHub 下載：`update.json` 要合併進 main 之後才存在；合併前網址回 404（就是上表「版本檔取不到」的情況）。
- WordPress「自動更新」開關（佈景頁的「啟用自動更新」）未測；預設關閉。
- 資料庫覆寫偵測工具未做：曾在編輯器改過的範本，更新後不會變。
