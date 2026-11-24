# 目錄狀態

`ci.yml`（2026-11-20 加入）：

- **Static checks**：PHP lint、WPCS（`phpcs.xml.dist`，版本鎖在 `composer.lock`）、配色對比、styles 與 tokens 一致、theme.json schema。
- **WordPress runtime**：用 `tests/env/setup.sh` 建立一次性 WordPress 7.1.4＋SQLite，啟用佈景後跑前台冒煙、元件狀態與編輯器區塊合法性；有任何 PHP 錯誤記錄即失敗；截圖保留 7 天。

`release.yml`（2026-11-24 加入）：main 分支的佈景版本還沒有對應 Release 時，自動建立 `v<版本>`，附上 `update.json` 所列的 ZIP 與 `.sha256`，說明取自 `CHANGELOG.md`；已存在就跳過。`-test` 版本標成 pre-release。

兩個 workflow 都不部署、不連正式站；網站更新由各站後台的線上更新自行取得。
