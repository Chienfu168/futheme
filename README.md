# FuTheme Framework

版本：0.1.0｜建立日期：2026-11-02｜階段：M1 核心原型（進行中）

共用 Block Theme、十套品牌配色與外掛視覺契約。目前 `themes/futheme-core` 已可在測試站啟用並切換十套配色，但 M1 尚未完成（Pattern、橫幅、元件、CI、ZIP），**不可用於正式網站**；未連接或修改任何正式網站。

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

```bash
python3 scripts/validate_spec.py       # Token、對比、文件連結
python3 scripts/build_styles.py --check # theme.json／styles 與 Token 一致
python3 scripts/validate_theme_json.py  # theme.json schema
bash tests/env/setup.sh                 # 建立本機測試站（.wp-test/）
```

改 `design/tokens.json` 後執行 `python3 scripts/build_styles.py` 重新產生配色檔。靜態檢查不代表效能、無障礙或外掛整合已通過，實測證據見 `tests/reports/`。

首輪目標 WordPress 7.1.4；不宣稱向下相容。實作前再次確認官方修補版與 PHP 要求。UR 原有 Sydney 優化工作不因本專案而自動變更。

儲存庫：[Chienfu168/futheme](https://github.com/Chienfu168/futheme)（私人）。
2026-11-10 由 `WP_Themes` 改名接手——舊的傳統 PHP 骨架沒有網站在用，也做不到「配色後台切換」，
已移除並留在 git 歷史（`b58ca3b`）。共用佈景只有一套，不開第二個 repo。

下一步：M1 的 Pattern、橫幅與元件。UR 隔離副本及 UR AI Assistant 原始碼尚未取得（M2 依賴）；正式部署未建立。
