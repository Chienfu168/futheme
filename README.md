# FuTheme Framework

版本：0.1.0｜建立日期：2026-11-02｜階段：M1 進行中

共用 Block Theme、十套品牌配色與外掛視覺契約。

2026-11-10：`themes/futheme-core/` 有了第一版檔案——theme.json v3、十套 Style Variations、
頁首頁尾與 index 範本。**但這些檔案一次都沒有在 WordPress 裡跑過**，
也還沒碰過任何正式網站。靜態檢查全過只代表檔案寫得對，不代表 WordPress 吃得下。

下一步就一件事：裝進乾淨站台，確認十套配色真的能在「外觀 → 編輯器 → 樣式」切換。
整個規劃建立在這個假設上，而它還沒被驗證（見 [docs/status.md](docs/status.md)）。

## 閱讀順序

0. [需求確認（本人 2026-11-10）](docs/requirements.md)
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

在專案根目錄執行：

```sh
python3 scripts/validate_spec.py              # 規格連結、Token 完整性、色彩對比
python3 scripts/generate_styles.py --check    # theme.json 與 styles/ 跟 tokens.json 一致
```

色盤的唯一主資料是 `design/tokens.json`。`themes/futheme-core/theme.json` 與
`themes/futheme-core/styles/*.json` 都由 `scripts/generate_styles.py` 產生，不要手改——
改了 `--check` 會擋下來。要換顏色就改 tokens.json 再重新產生。

這兩個檢查都不代表 WordPress 執行、效能、無障礙或外掛整合已通過。

首輪目標 WordPress 7.1.4；不宣稱向下相容。實作前再次確認官方修補版與 PHP 要求。UR 原有 Sydney 優化工作不因本專案而自動變更。

儲存庫：[Chienfu168/futheme](https://github.com/Chienfu168/futheme)（私人）。
2026-11-10 由 `WP_Themes` 改名接手——舊的傳統 PHP 骨架沒有網站在用，也做不到「配色後台切換」，
已移除並留在 git 歷史（`b58ca3b`）。共用佈景只有一套，不開第二個 repo。

下一步：把 `themes/futheme-core/` 裝進乾淨站台驗證配色切換。UR 隔離副本及 UR AI Assistant 原始碼尚未取得（M2 依賴）；正式部署未建立。
