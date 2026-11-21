# 目錄狀態

`ci.yml`（2026-11-20 加入）：

- **Static checks**：PHP lint、WPCS（`phpcs.xml.dist`，版本鎖在 `composer.lock`）、配色對比、styles 與 tokens 一致、theme.json schema。
- **WordPress runtime**：用 `tests/env/setup.sh` 建立一次性 WordPress 7.1.4＋SQLite，啟用佈景後跑前台冒煙、元件狀態與編輯器區塊合法性；有任何 PHP 錯誤記錄即失敗；截圖保留 7 天。

不發布、不部署、不連正式站。Release 打包留待 M3。
