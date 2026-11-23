# 專案狀態

更新：2026-11-23

## 已完成

| 項目 | 證據 |
| --- | --- |
| M0 規格與骨架 | README、docs、design 與 scripts |
| 第一個採用網站 | 2026-11-15 本人決定：**fudawang.com** |
| 合併平行的 M1 | 2026-11-15：`main` 上 2026-11-10～14 的平行工作（未經 WordPress 驗證的佈景檔、採用狀態、色彩 slug 契約、更新機制分析）併入。佈景檔以已實測版本為準，補入 `FUTHEME_CORE_VERSION` 與 post-thumbnails；配色產生器保留 `build_styles.py`，移除會整份覆寫 theme.json 的 `generate_styles.py` |
| 使用者確認 M0 規格 | 2026-11-10，見 [需求確認](requirements.md)；版型範圍與橫幅需求同日確認 |
| 舊 WP_Themes 處理 | 2026-11-10 改名為 `Chienfu168/futheme`，舊 PHP 骨架移除，留在 git 歷史（`b58ca3b`） |
| Token 靜態檢查 | 2026-11-02：10 套 × 13 Token、260 組對比配對及本地 Markdown 連結通過；最低 3.482:1 為介面配對（門檻 3:1），文字配對均 ≥4.5:1 |
| M1 測試環境 | 2026-11-12：setup.sh 執行成功，WordPress 7.1.4＋PHP 8.3.6＋SQLite 3.1.3，前台與登入頁 200 |
| M1 佈景本體 | 2026-11-12：style.css、theme.json、functions.php、8 個模板、頁首頁尾、繁中翻譯；啟用、十套配色切換與重設、schema、PHP lint、360/1280px 截圖與鍵盤焦點通過，見 [報告](../tests/reports/2026-11-12-m1-core.md) |
| 測試環境版本查證 | 2026-11-12：WordPress 官方 API 最新正式版仍為 7.1.4；WordPress ZIP 的 SHA1 與官方公布值相符，WP-CLI 2.12.0 的 SHA-256 與官方 `.sha256` 相符；版本與雜湊鎖在 [versions.env](../tests/env/versions.env) |
| 網站編輯器切換配色 | 2026-11-15：瀏覽器實際操作「樣式 → 瀏覽樣式」選石墨灰並儲存，前台 13 色相符，見 [報告](../tests/reports/2026-11-15-merge-editor.md) |
| 佈景改名 | 2026-11-15 本人決定：名稱 `FuTheme`、代號 `futheme`（原 `futheme-core`），加 `Update URI`、`screenshot.png`；改名後全部重測通過，見 [報告](../tests/reports/2026-11-15-merge-editor.md) |
| 0.2.0 視覺版面 | 2026-11-15：首頁大橫幅（hero Pattern）、文章卡片格線、單篇／單頁頁首橫幅、深色頁尾、頁首主色線；十套配色截圖、編輯器區塊合法性、對比計算、深色區塊焦點框、0.1.0→0.2.0 升級均通過，見 [報告](../tests/reports/2026-11-15-visual.md) |
| **1.0.0 正式版** | 2026-11-23 本人指示發布：`dist/futheme-1.0.0.zip`，`update.json` 指向它；線上更新 0.6.1→1.0.0 實測通過，見 [報告](../tests/reports/2026-11-23-100-release.md)。正式站切換由本人操作 |
| 0.6.1 外掛專屬配色變數 | 2026-11-20：新增 `--wp--custom--futheme--*`（13 色）。實測共用的 `--wp--preset--color--*` 在 Twenty Twenty-Three 下會把都更試算按鈕帶成 1.25:1；外掛改讀專屬變數後，FuTheme 下跟配色、其他三個佈景與原版相同，見 [報告](../tests/reports/2026-11-20-own-plugins.md) |
| 自家外掛融合度 | 2026-11-20：都更試算 0.3.7、UR AI Assistant 1.49.0 在十套配色下版面、功能、無錯誤通過；都更試算跟隨配色，UR AI Assistant 固定藍色且錯誤訊息被蓋掉（外掛端，建議另修），見 [報告](../tests/reports/2026-11-20-own-plugins.md) |
| 0.6.0 線上更新 | 2026-11-20：讀 main 分支 `update.json`、SHA-256 驗證；偵測新版、後台一鍵更新、檢查碼不符擋下、版本檔取不到時不影響網站，全部實測；**要合併進 main 後各站才收得到**，見 [報告](../tests/reports/2026-11-20-060-online-update.md) |
| 0.5.1 fudawang 外掛實測 | 2026-11-20：以正式站相同版本裝 19 個公開外掛全部啟用，前台、元件、CF7、版型測試通過；發現 WP Fastest Cache 擋住換配色，佈景改為儲存樣式時自動清快取；見 [報告](../tests/reports/2026-11-20-051-site-plugins.md) |
| 0.5.0 版型與相容 | 2026-11-20：作者改為福大資訊、頁尾署名；頁首／頁尾各三款；六款原創橫幅圖與 Pattern；CF7 與 Yoast 相容實測；CI 加 Firefox、WebKit；見 [報告](../tests/reports/2026-11-20-050-layouts-banners-plugins.md) |
| GitHub CI | 2026-11-20：run #5（commit `3a49124`）靜態檢查與 WordPress 實測兩個 job 全部通過；第一次執行抓到連結檢查誤掃 `vendor/`，已修正 |
| 0.4.0 M1 元件 | 2026-11-20：服務卡片、聯絡 Pattern、單篇留言區；按鈕／外框按鈕／表單的 hover、focus、disabled、error 狀態；修正 slug `border` 類別撞名；十套配色 × 33 項元件檢查、編輯器區塊合法性、WPCS 通過，見 [報告](../tests/reports/2026-11-20-m1-components.md) |
| 0.3.0 預設橫幅圖與頁尾 | 2026-11-16：使用者要求預設橫幅圖（改變 0.2.0「佈景不帶圖片」的做法，本人決定）；頁尾改為品牌欄＋選單＋版權底列；頁首備援選單改顯示文字按鈕。十套配色對比計算、編輯器區塊合法性、360/1280 截圖通過，見 [報告](../tests/reports/2026-11-16-banner-footer.md) |
| 0.2.1 導覽備援保護 | 2026-11-16：未指定選單且頁面超過 6 個時，頁首收成選單按鈕、頁尾不列出全部頁面；36 頁／4 頁／已指定選單三種情境實測，見 [報告](../tests/reports/2026-11-16-nav-fallback.md) |
| 佈景 ZIP | 2026-11-23 現為 `dist/futheme-1.0.0.zip`（正式版），保留 `futheme-0.6.1-test.zip` 供退回；2026-11-20 為 0.6.1 測試版；同日曾為 0.6.0、 0.5.1、0.5.0、同日曾為 0.4.0；2026-11-16 為 0.3.0（同日曾為 0.2.1）；2026-11-15 為 0.2.0；改名後曾為 0.1.0；合併後重打並以取代更新驗證；2026-11-13：應使用者要求提供 `dist/futheme-core-0.1.0-test.zip`（M1 未完成，僅供測試站）；乾淨站後台上傳安裝、啟用、取代更新、回退通過，見 [報告](../tests/reports/2026-11-13-m1-zip-install.md) |

## 待驗證／進行中

| 項目 | 狀態 |
| --- | --- |
| M1 未完成項目 | 編輯器手動改色／字型與重設未測；0.4.0 ZIP 安裝／更新／回退通過（WP-CLI），後台上傳取代 0.3.0 也通過 |
| 網站編輯器細項 | 2026-11-15 已在編輯器實際切換並儲存配色（通過）；手動改單色／字型、編輯器重設、編輯器與前台逐項比對未測 |
| 其他瀏覽器與裝置 | 本機只測 Chromium；Firefox、WebKit 已加入 CI（0.5.0 首次）；手機實機、200% 縮放未測 |
| fudawang 盤點 | 2026-11-21 本人提供網站狀態：WordPress 7.1.5、PHP 8.3、MySQL 5.7、現用 Sydney 2.74、20 個外掛（已全部實測）、留言關閉、永久連結為預設格式。**FuTheme 0.6.1 可啟用**；測試環境已升 7.1.5 並重跑全部測試通過。未做：在正式站內容上試用（建議用 Health Check 疑難排解模式） |
| 效能比較／外掛整合 | 效能比較未測；外掛：fudawang 的 19 個公開外掛已實測，都更試算與 UR AI Assistant 2026-11-20 已測 |

## 阻礙

| 項目 | 說明 |
| --- | --- |
| 網路限制 | 雲端容器連不到 schemas.wp.org 與 develop.svn.wordpress.org；schema 改從 GitHub 取並鎖雜湊，theme.json 翻譯字串手動維護 |
| 資料庫差異 | 雲端容器沒有 Docker daemon 與 MySQL，M1 先用 SQLite；正式主機用 MySQL/MariaDB，資料庫相關行為須在 M2 隔離副本再驗 |
| UR 採用方向 | 未決；第一個採用者為 fudawang，不阻擋 M2 |
| UR 隔離副本 | 未取得；UR 未決前不需要 |
| UR AI Assistant 原始碼 | 2026-11-20 取得（`Chienfu168/FU-AI-UR`，公開），已做融合度測試 |
| GitHub Release | 未建立，M3 處理 |

正式網站：未操作，無登入、切換或部署。
