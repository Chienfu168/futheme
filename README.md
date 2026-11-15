# FuTheme Framework

版本：0.1.0｜建立日期：2026-11-02｜階段：M1 核心原型（進行中）

共用 Block Theme、十套品牌配色與外掛視覺契約。目前 `themes/futheme` 已可在測試站啟用並切換十套配色，但 M1 尚未完成（Pattern、橫幅、元件、CI、ZIP），**不可用於正式網站**；未連接或修改任何正式網站。

## 測試版下載安裝

**僅供測試站**，M1 尚未完成（服務卡片、聯絡 Pattern、表單元件、CI 未做），不要裝在正式網站。

0.2.0 起有首頁大橫幅、文章卡片、單篇／單頁頁首橫幅與深色頁尾。首頁橫幅的圖片在「外觀 → 編輯器 → 範本 → 首頁」點橫幅換圖；單篇／單頁橫幅用文章的「精選圖片」。沒設圖片時顯示目前配色的主色。

1. 在 GitHub 開啟 [dist/futheme-0.2.0-test.zip](dist/futheme-0.2.0-test.zip)，按右上角下載（Download raw file）。
   不要用 repo 首頁的「Code → Download ZIP」，那是整個專案，WordPress 無法安裝。
2. 可比對 SHA-256：[futheme-0.2.0-test.sha256](dist/futheme-0.2.0-test.sha256)。
3. WordPress 後台「外觀 → 佈景主題 → 安裝佈景主題 → 上傳佈景主題」，選 ZIP，安裝後啟用。
4. 換配色：「外觀 → 編輯器 → 樣式」選一套後儲存。
5. 從 0.1.0 升級：直接上傳新 ZIP，選「使用已上傳版本取代已安裝版本」。
6. 如果之前裝過舊名稱「FuTheme Core」（`futheme-core`），新版會另外裝成「FuTheme」，舊的那個可直接刪除（沒啟用過就沒有影響）。

需求：WordPress 7.1 以上、PHP 8.3 以上（只測過這個組合）。

## 閱讀順序

0. [需求確認（本人 2026-11-10）](docs/requirements.md)
0.5 [採用狀態：哪些網站要用](docs/adoption.md) ← 目前最該先解決的
0.6 [色彩 slug 是對外契約](docs/color-slug-contract.md) ← 改 token 名稱之前必讀
1. [架構與技術可行性](docs/architecture.md)
2. [設計系統及完整配色](docs/design-system.md)
3. [目錄與檔案規格](docs/file-specification.md)
4. [開發工作與里程碑](docs/work-items.md)
5. [效能預算與測試方案](docs/performance-budget.md)
6. [外掛整合契約](docs/plugin-integration.md)
7. [風險及相容性](docs/risks.md)
8. [部署與回復](docs/deployment.md)
9. [執行狀態](docs/status.md)

## 本機驗證

```bash
python3 scripts/validate_spec.py       # Token、對比、文件連結
python3 scripts/build_styles.py --check # theme.json／styles 與 Token 一致
python3 scripts/validate_theme_json.py  # theme.json schema
bash tests/env/setup.sh                 # 建立本機測試站（.wp-test/）
```

色盤唯一主資料是 `design/tokens.json`。theme.json 的色盤與 `styles/*.json` 由 `python3 scripts/build_styles.py` 產生，不要手改，`--check` 會擋下不一致；theme.json 其餘欄位手動維護。靜態檢查不代表效能、無障礙或外掛整合已通過，實測證據見 `tests/reports/`。

首輪目標 WordPress 7.1.4；不宣稱向下相容。實作前再次確認官方修補版與 PHP 要求。UR 原有 Sydney 優化工作不因本專案而自動變更。

儲存庫：[Chienfu168/futheme](https://github.com/Chienfu168/futheme)（私人）。
2026-11-10 由 `WP_Themes` 改名接手——舊的傳統 PHP 骨架沒有網站在用，也做不到「配色後台切換」，
已移除並留在 git 歷史（`b58ca3b`）。共用佈景只有一套，不開第二個 repo。

第一個採用網站：**fudawang.com**（2026-11-15 決定，見 [採用狀態](docs/adoption.md)）。

下一步：M1 的 Pattern、橫幅與元件。UR 隔離副本及 UR AI Assistant 原始碼尚未取得（M2 依賴）；正式部署未建立。
