# 測試 ZIP 上傳安裝（2026-11-13）

> 2026-11-15 佈景代號改為 `futheme`，下列 `futheme-core` 檔案已從 repo 移除；同日合併 main 後亦曾重新打包，雜湊已變更，見 [合併報告](2026-11-15-merge-editor.md)。下表為 11-13 當時的檔案。

使用者要求先提供可從 GitHub 下載安裝的版本，M1 尚未完成，因此檔名標 `-test`，僅供測試站。

## 檔案

| 項目 | 值 |
| --- | --- |
| ZIP | `dist/futheme-core-0.1.0-test.zip`（已移除），31 檔，19,157 bytes |
| SHA-256 | `eaf727b5a095b856d70136cd401264f60e21424d3075e22d35dffc877f3cb7de` |
| 檔案清單 | `futheme-core-0.1.0-test.files.txt`（已移除） |
| 產生方式 | `python3 scripts/package_theme.py test`；連續兩次產生相同雜湊 |

## 環境

另建乾淨站（`FUTHEME_LINK_THEMES=0`，不接 repo 佈景資料夾）：WordPress 7.1.4 zh_TW、PHP 8.3.6、SQLite 3.1.3、Chromium 153。

## 結果

| 步驟 | 結果 | 方法 |
| --- | --- | --- |
| 後台上傳安裝 | 通過 | Playwright 登入，「外觀 → 佈景主題 → 上傳」選 ZIP；顯示「佈景主題已完成安裝」 |
| 啟用 | 通過 | 點「啟用」，顯示「新的佈景主題已啟用」；安裝為實體資料夾 `themes/futheme-core` |
| 前台 | 通過 | 首頁、文章、頁面、搜尋無結果 200，不存在網址 404；無 PHP 警告；繁中字串正常；偵測到 10 套配色 |
| 取代更新 | 通過 | 再上傳同一 ZIP，WordPress 顯示版本比較，點「使用已上傳版本取代已安裝版本」，顯示「佈景主題已成功更新」 |
| 回退 | 通過 | 存 graphite 配色 → 切到 Twenty Twenty-Five（首頁 200）→ 切回 FuTheme，graphite 仍在；重設後回到 urban-blue |

## 未測／限制

- 更新只測了同版本取代；不同版本號之間的升級與降級未測。
- 主機 PHP 若低於 8.3，WordPress 會拒絕安裝（`Requires PHP: 8.3`）。
- 其他限制同 [M1 第一輪報告](2026-11-12-m1-core.md)：網站編輯器 UI 未操作、只測 Chromium 與 SQLite。
