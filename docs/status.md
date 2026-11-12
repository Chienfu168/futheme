# 專案狀態

更新：2026-11-12

## 已完成

| 項目 | 證據 |
| --- | --- |
| M0 規格與骨架 | README、docs、design 與 scripts |
| 使用者確認 M0 規格 | 2026-11-10，見 [需求確認](requirements.md)；版型範圍與橫幅需求同日確認 |
| 舊 WP_Themes 處理 | 2026-11-10 改名為 `Chienfu168/futheme`，舊 PHP 骨架移除，留在 git 歷史（`b58ca3b`） |
| Token 靜態檢查 | 2026-11-02：10 套 × 13 Token、260 組對比配對及本地 Markdown 連結通過；最低 3.482:1 為介面配對（門檻 3:1），文字配對均 ≥4.5:1 |
| M1 測試環境 | 2026-11-12：setup.sh 執行成功，WordPress 7.1.4＋PHP 8.3.6＋SQLite 3.1.3，前台與登入頁 200 |
| M1 佈景本體 | 2026-11-12：style.css、theme.json、functions.php、8 個模板、頁首頁尾、繁中翻譯；啟用、十套配色切換與重設、schema、PHP lint、360/1280px 截圖與鍵盤焦點通過，見 [報告](../tests/reports/2026-11-12-m1-core.md) |
| 測試環境版本查證 | 2026-11-12：WordPress 官方 API 最新正式版仍為 7.1.4；WordPress ZIP 的 SHA1 與官方公布值相符，WP-CLI 2.12.0 的 SHA-256 與官方 `.sha256` 相符；版本與雜湊鎖在 [versions.env](../tests/env/versions.env) |

## 待驗證／進行中

| 項目 | 狀態 |
| --- | --- |
| M1 未完成項目 | 三種 Pattern、橫幅、按鈕／卡片／表單元件、CI、ZIP 打包 |
| 網站編輯器 UI | 未實際操作；配色切換以寫入 Global Styles 驗證，編輯器畫面與前台一致性未測 |
| 首頁 `<h1>` | 目前沒有，待橫幅 hero 補上 |
| 其他瀏覽器與裝置 | 只測 Chromium；Firefox、Safari、手機實機、200% 縮放未測 |
| 正式主機 PHP 版本 | 未盤點；佈景暫標 `Requires PHP: 8.3`（只測過 8.3） |
| 效能比較／外掛整合 | 未測，不以靜態檢查替代 |

## 阻礙

| 項目 | 說明 |
| --- | --- |
| 網路限制 | 雲端容器連不到 schemas.wp.org 與 develop.svn.wordpress.org；schema 改從 GitHub 取並鎖雜湊，theme.json 翻譯字串手動維護 |
| 資料庫差異 | 雲端容器沒有 Docker daemon 與 MySQL，M1 先用 SQLite；正式主機用 MySQL/MariaDB，資料庫相關行為須在 M2 隔離副本再驗 |
| UR 隔離副本 | 未取得；M2 需要去識別副本與隔離環境 |
| UR AI Assistant 原始碼 | 未取得；不假設既有交接文件含原始碼 |
| GitHub Release | 未建立，M3 處理 |

正式網站：未操作，無登入、切換或部署。
