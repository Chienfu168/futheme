# M1 測試報告（2026-11-10）

## 環境

| 項目 | 版本 |
| --- | --- |
| 佈景 | FuTheme Core 0.1.0-m1，commit `86a81b2`，ZIP SHA-256 `3bd5c002476ca7d1048f8a10e19c1b7f709b74b7cc187bd848e417f30459d4ef` |
| WordPress | 7.1.4（downloads.wordpress.org，SHA-1 `b552c39c…aff4f6` 已核對） |
| PHP | 8.3.6 CLI，內建伺服器 |
| 資料庫 | SQLite（sqlite-database-integration 3.1.3）——**不是 MySQL** |
| 瀏覽器 | Chromium 153.0.8010.12（Playwright 1.63.0） |
| 網站語言 | en_US（未測 zh_TW） |
| 測試站 | 隔離的本機站，示範內容由 `tests/env/seed.php` 產生，無真實資料 |

重現：`tests/env/setup.sh`、`tests/env/serve.sh`，再依下列指令執行。

## 結果

| 檢查 | 結果 | 證據 |
| --- | --- | --- |
| 以後台上傳流程（`Theme_Upgrader`）在乾淨站安裝 ZIP、啟用 | 通過 | [install.txt](install.txt) |
| 更新（以新版 ZIP 取代）與退回（以舊版取代） | 通過：0.1.0-m1 → 0.1.0-m1-update-test → 0.1.0-m1 | 見下方「更新與退回」 |
| WordPress 判定為 Block Theme、讀到 10 套 Style Variations | 通過 | [install.txt](install.txt) |
| 前台：10 套配色 × 10 種頁面 × 桌機 1280／手機 390，共 200 組 | 全部通過 | [frontend.txt](frontend.txt)、[raw/](raw/) |
| 編輯器：9 個版型＋3 個 template part 無無效區塊、無警告 | 全部通過 | [editor.txt](editor.txt) |
| PHP 錯誤（WP_DEBUG_LOG） | 0 行 | [php-errors.txt](php-errors.txt) |

每組前台檢查內容：HTTP 狀態（404 頁應為 404）、`--wp--preset--color--primary／accent` 等於該套 Token、無水平捲動、有 `main` 與跳至內容連結、恰好一個 h1、頁面上所有可見文字對其實際背景的 WCAG 對比（一般 4.5、大字 3，停用元件除外；橫幅內文字另以遮罩計算）、第一次 Tab 到跳至內容、焦點環為 2px 實線、無主控台錯誤。

頁面種類：首頁、一般頁面（無精選圖）、頁面（深色精選圖）、文章（白色精選圖，最壞情況）、文章（無精選圖）、分類彙整、搜尋（有結果）、搜尋（無結果）、404、表單示範頁。

## 更新與退回

更新測試包只把 style.css 版本改為 `0.1.0-m1-update-test`，其他檔案相同。

```
1) 安裝: installed (none) -> 0.1.0-m1 ; active=futheme-core ; block_theme=yes ; variations=10
2) 更新: installed 0.1.0-m1 -> 0.1.0-m1-update-test ; active=futheme-core ; block_theme=yes ; variations=10
3) 退回: installed 0.1.0-m1-update-test -> 0.1.0-m1 ; active=futheme-core ; block_theme=yes ; variations=10
```

## 其他手動情境（本機站）

- Logo：上傳白色透明 PNG 後，桌機高 48px、手機 36px，寬度等比，無水平捲動；未上傳時只顯示網站標題（[logo-header.jpg](logo-header.jpg)）。
- 手動改主色（模擬樣式面板改色）後前台立即反映；重設後回到檔案預設 `#1769AA`。
- 首頁／頁首橫幅未設定圖片：改為主色實心底，遮罩不顯示。

## 截圖

- [十套首頁（桌機）](front-desktop-10-palettes.jpg)
- [十套文章頁，白色橫幅最壞情況（桌機）](single-white-banner-desktop-10-palettes.jpg)
- [十套表單與狀態（手機）](form-mobile-10-palettes.jpg)

## 效能（只量佈景自有資源）

| 項目 | 結果 | 預算 |
| --- | --- | --- |
| 佈景自有 CSS | 6,493 B，gzip 2,204 B | ≤20KB gzip |
| 佈景自有 JS | 0 | ≤10KB gzip |
| 外部字型 | 0 | 0 |
| WordPress 產生的 Global Styles（另列） | 18,008 B，gzip 2,911 B | 另列 |
| 首頁全部 inline CSS（核心區塊＋Global Styles＋其他） | 59,836 B，gzip 8,139 B | 另列 |

TTFB、LCP、CLS、查詢數與同條件比較**未測**，屬 M2。

## 限制與未測

- 資料庫為 SQLite，正式主機多為 MySQL／MariaDB，未在 MySQL 上測。
- 配色切換以腳本寫入使用者 Global Styles（與編輯器儲存的資料相同），**沒有用滑鼠在編輯器 UI 點選並儲存**。
- 只在 Chromium 測；Firefox、Safari、Edge、iOS Safari、Android Chrome 未測。
- 螢幕報讀器未測；Logo 的替代文字與網站標題相同，報讀時可能念兩次，待評估。
- WPCS 未在本機執行（沙盒無法下載 phpcs）；CI 設定已寫好，尚未在 GitHub 上執行。
- theme.json 未對官方 JSON schema 驗證（只驗 JSON 格式與 WordPress 實際載入結果）。
- 編輯器主控台有核心向 s.w.org（表情符號）與 gravatar 的請求被測試網路擋下，判定為環境限制，不計入失敗。
- 圖片效能（尺寸、格式、延遲載入）與橫幅焦點裁切未量測。
