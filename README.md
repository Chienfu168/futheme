# FuTheme Framework

版本：0.1.0-m1｜建立日期：2026-11-02｜階段：M1 核心原型（2026-11-10）

共用 Block Theme、十套品牌配色與外掛視覺契約。M1 已有可安裝的佈景原型 `themes/futheme-core`（測試用，未部署），**外掛（Bridge）尚未實作，也未連接或修改正式網站**。

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

- 規格與配色：`python3 scripts/validate_spec.py`、`python3 scripts/build_theme_styles.py --check`
- 打包：`scripts/package_theme.sh`（輸出到 dist/）
- WordPress 實測：見 [tests/README.md](tests/README.md)，最新結果見 [M1 測試報告](tests/reports/m1-2026-11-10/README.md)

靜態檢查不代表 WordPress 執行、效能、無障礙或外掛整合已通過。

首輪目標 WordPress 7.1.4；不宣稱向下相容。實作前再次確認官方修補版與 PHP 要求。UR 原有 Sydney 優化工作不因本專案而自動變更。

儲存庫：[Chienfu168/futheme](https://github.com/Chienfu168/futheme)（私人）。
2026-11-10 由 `WP_Themes` 改名接手——舊的傳統 PHP 骨架沒有網站在用，也做不到「配色後台切換」，
已移除並留在 git 歷史（`b58ca3b`）。共用佈景只有一套，不開第二個 repo。

M1 原型已完成，待驗證項目見 [執行狀態](docs/status.md)。下一步是 M2，需要 UR 隔離副本及 UR AI Assistant 原始碼（尚未取得）；正式部署未建立。
