# 專案狀態

更新：2026-11-16

## 已完成

| 項目 | 證據 |
| --- | --- |
| M0 規格與骨架 | README、docs、design 與 scripts |
| 第一個採用網站 | 2026-11-15 本人決定：**fudawang.com**，見 [採用狀態](adoption.md) |
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
| 0.2.1 導覽備援保護 | 2026-11-16：未指定選單且頁面超過 6 個時，頁首收成選單按鈕、頁尾不列出全部頁面；36 頁／4 頁／已指定選單三種情境實測，見 [報告](../tests/reports/2026-11-16-nav-fallback.md) |
| 測試 ZIP | 2026-11-16 現為 `dist/futheme-0.2.1-test.zip`；2026-11-15 為 0.2.0；改名後曾為 0.1.0；合併後重打並以取代更新驗證；2026-11-13：應使用者要求提供 `dist/futheme-core-0.1.0-test.zip`（M1 未完成，僅供測試站）；乾淨站後台上傳安裝、啟用、取代更新、回退通過，見 [報告](../tests/reports/2026-11-13-m1-zip-install.md) |

## 待驗證／進行中

| 項目 | 狀態 |
| --- | --- |
| M1 未完成項目 | 服務卡片與聯絡 Pattern、按鈕／表單元件契約與 fixture、CI；完成後重新打包驗證 |
| 網站編輯器細項 | 2026-11-15 已在編輯器實際切換並儲存配色（通過）；手動改單色／字型、編輯器重設、編輯器與前台逐項比對未測 |
| 其他瀏覽器與裝置 | 只測 Chromium；Firefox、Safari、手機實機、200% 縮放未測 |
| fudawang 盤點 | 未做：現用佈景、外掛、主機 PHP／資料庫版本都未確認；佈景暫標 `Requires PHP: 8.3`（只測過 8.3），需依主機版本決定是否另測放寬 |
| 效能比較／外掛整合 | 未測，不以靜態檢查替代 |

## 阻礙

| 項目 | 說明 |
| --- | --- |
| 網路限制 | 雲端容器連不到 schemas.wp.org 與 develop.svn.wordpress.org；schema 改從 GitHub 取並鎖雜湊，theme.json 翻譯字串手動維護 |
| 資料庫差異 | 雲端容器沒有 Docker daemon 與 MySQL，M1 先用 SQLite；正式主機用 MySQL/MariaDB，資料庫相關行為須在 M2 隔離副本再驗 |
| UR 採用方向 | 未決：UR 原計畫沿用 Sydney，與 FuTheme 不相容，見 [採用狀態](adoption.md)；第一個採用者改為 fudawang，不再阻擋 M2 |
| UR 隔離副本 | 未取得；UR 未決前不需要 |
| UR AI Assistant 原始碼 | 未取得；不假設既有交接文件含原始碼 |
| GitHub Release | 未建立，M3 處理 |

正式網站：未操作，無登入、切換或部署。
