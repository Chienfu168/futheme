# 專案狀態

更新：2026-11-10

| 項目 | 狀態 | 證據／下一步 |
| --- | --- | --- |
| M0 規格與骨架 | 已建立 | README、docs、design 與 scripts |
| Token 靜態檢查 | 通過 | 2026-11-10（配色 0.3.0）：10 套 × 13 Token、260 組對比配對、85 組色彩區別（accent/狀態色 ΔE ≥20、primary/accent ≥30、配色間 primary ≥20）及本地 Markdown 連結通過；最低對比 3.482:1 為介面配對（門檻 3:1），文字配對均 ≥4.5:1；最低 ΔE 20.6（business-green／aqua-blue）。0.1.0、0.2.0 舊值經同工具確認會被新檢查擋下 |
| 配色資料分析 | 已完成（靜態） | 2026-11-10：三套 accent 與 warning 過近已修正；焦點環間隔、連結底線、橫幅疊層門檻寫入設計系統。待驗證：實際 DOM、編輯器與橫幅圖片的對比。0.3.0 已拉開 tech-blue、forest-green 主色與 graphite 內部三色。待驗證：新色在實際頁面與後台樣式預覽中的觀感，需使用者看過 |
| 頁首／首頁風格規格 | 已確認（規格） | 2026-11-10：頁首方案 B（主色底、各站準備反白 Logo）；首頁與內頁用色方式不同（透明頁首、色塊區段）。待驗證：WordPress 實作、真實 Logo 與橫幅照片 |
| M1 核心原型 | 原型完成，部分待驗證 | 2026-11-10：0.1.0-m1 可安裝；證據見 [M1 測試報告](../tests/reports/m1-2026-11-10/README.md) |
| UR 隔離副本 | 未取得 | M2 需要去識別副本與隔離環境 |
| UR AI Assistant 原始碼 | 未取得 | 不假設既有交接文件含原始碼 |
| WordPress／PHP 執行驗證 | 通過（限定環境） | WordPress 7.1.4、PHP 8.3.6、SQLite；乾淨站以上傳流程安裝、啟用、更新、退回；PHP 錯誤 0 |
| 前台／編輯器實測 | 通過（Chromium） | 10 套配色 × 10 種頁面 × 桌機／手機 200 組；9 版型＋3 template part 區塊皆有效 |
| 效能 | 部分 | 佈景 CSS gzip 2.2KB、JS 0、外部字型 0（預算內）。TTFB／LCP／CLS／比較未測（M2） |
| 其他瀏覽器、MySQL、螢幕報讀器、外掛整合 | 未測 | 見下方「待驗證」 |
| CI | 已設定，未執行 | `.github/workflows/ci.yml` 尚未在 GitHub 上跑過 |
| GitHub Release | 未建立 | M3 處理 |
| 正式網站 | 未操作 | 無登入、切換或部署 |

## M1（2026-11-10）

**已完成（有證據）**
- futheme-core 0.1.0-m1：9 個版型、3 個 template part、5 個首頁 pattern、10 套配色、4 種區段樣式、共用 CSS。
- 配色檔由 tokens.json 產生，`--check` 確認一致。
- 以後台上傳流程在乾淨站安裝、啟用、更新、退回。
- 前台 200 組自動檢查、編輯器 12 項區塊有效性檢查、PHP 錯誤 0。
- Logo（有／無）、橫幅（白圖、深圖、無圖）、手動改主色與重設。

**待驗證**
- 在網站編輯器 UI 實際點選配色並儲存（目前以腳本寫入相同資料）。
- MySQL／MariaDB、Firefox、Safari、Edge、iOS Safari、Android Chrome、zh_TW 介面。
- 螢幕報讀器；Logo 替代文字與網站標題重複報讀。
- WPCS 與官方 theme.json schema 驗證；CI 首次在 GitHub 執行。
- 圖片效能、橫幅焦點裁切、TTFB／LCP／CLS 與同條件比較（M2）。
- 真實 Logo 與橫幅照片下的觀感（需使用者提供或確認）。

**阻礙**
- 本工作階段的網路不開放 GitHub 下載，WPCS 無法在本機執行。
- UR 隔離副本與 UR AI Assistant 原始碼仍未取得（M2 依賴）。
