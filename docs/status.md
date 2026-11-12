# 專案狀態

更新：2026-11-12

## 已完成

| 項目 | 證據 |
| --- | --- |
| M0 規格與骨架 | README、docs、design 與 scripts |
| 使用者確認 M0 規格 | 2026-11-10，見 [需求確認](requirements.md)；版型範圍與橫幅需求同日確認 |
| 舊 WP_Themes 處理 | 2026-11-10 改名為 `Chienfu168/futheme`，舊 PHP 骨架移除，留在 git 歷史（`b58ca3b`） |
| Token 靜態檢查 | 2026-11-02：10 套 × 13 Token、260 組對比配對及本地 Markdown 連結通過；最低 3.482:1 為介面配對（門檻 3:1），文字配對均 ≥4.5:1 |
| 測試環境版本查證 | 2026-11-12：WordPress 官方 API 最新正式版仍為 7.1.4；WordPress ZIP 的 SHA1 與官方公布值相符，WP-CLI 2.12.0 的 SHA-256 與官方 `.sha256` 相符；版本與雜湊鎖在 [versions.env](../tests/env/versions.env) |

## 待驗證／進行中

| 項目 | 狀態 |
| --- | --- |
| M1 隔離測試環境 | 已寫 [tests/env/setup.sh](../tests/env/setup.sh)（PHP 內建伺服器 + SQLite + WP-CLI），**尚未執行**，沒有安裝成功的證據 |
| M1 其他項目 | 未開始：style.css、theme.json、模板、styles JSON、Patterns、橫幅、元件、CI、ZIP |
| WordPress／PHP 執行驗證 | 未測，尚無可安裝佈景 |
| 效能／瀏覽器／整合 | 未測，不以靜態檢查替代 |

## 阻礙

| 項目 | 說明 |
| --- | --- |
| 測試環境執行權限 | 2026-11-12 雲端工作階段的自動權限擋下「執行下載的 WordPress／WP-CLI」，需使用者同意後才能跑 setup.sh |
| 資料庫差異 | 雲端容器沒有 Docker daemon 與 MySQL，M1 先用 SQLite；正式主機用 MySQL/MariaDB，資料庫相關行為須在 M2 隔離副本再驗 |
| UR 隔離副本 | 未取得；M2 需要去識別副本與隔離環境 |
| UR AI Assistant 原始碼 | 未取得；不假設既有交接文件含原始碼 |
| GitHub Release | 未建立，M3 處理 |

正式網站：未操作，無登入、切換或部署。
