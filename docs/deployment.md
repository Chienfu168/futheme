# 部署與回復

狀態：未執行；M0 不產生安裝 ZIP、不登入正式站。

## M1/M2 隔離驗證

先建立示範站；UR 副本必須與正式站資料庫、網域、憑證及 API 設定隔離。匯入前處理個資、寄信、排程及付費 API，確認禁止索引與存取限制。保存測試基準與還原方式。

原生操作：上傳 Core ZIP → 啟用 → 外觀／編輯器／樣式 → 選擇配色 → 儲存。檢查十套配色、單色及字型調整、切換、重設。WordPress 介面標籤若依版本不同，文件隨實測更新。

配色更新前備份資料庫及自訂樣式，記錄當前版本、配色及修改；先在副本試用新版。使用原生介面重新套用會改動樣式，不能承諾保留所有個人修改。首版採人工核對，匯入、匯出與差異合併工具留後續。

## M3 發布

通過檢查後使用語意化版本，輸出 ZIP、SHA-256、檔案清單、變更說明、已測環境與已知限制。保留前一版 ZIP。GitHub Release 需先建立遠端及發布流程，本輪不發布。

## 未來正式切換

另行具體確認部署範圍及授權；先備份檔案與資料庫，驗證可還原，盤點測試期間新增資料。部署後清快取，檢查首頁、文章、搜尋、表單、AI、會員及錯誤日誌。

嚴重錯誤時先回復本次佈景／外掛及設定；檔案回退不會還原 DB 樣式。需要還原資料庫時先保護備份後新資料，不用舊測試資料庫覆蓋正式站。記錄原因、影響、回復結果與未解決問題。

## 線上更新（0.6.0 起）

2026-11-20 本人同意實作。repo 已公開，不需要 token。程式在 [`themes/futheme/inc/updater.php`](../themes/futheme/inc/updater.php)。

### 怎麼運作

1. `style.css` 的 `Update URI: https://github.com/Chienfu168/futheme` 讓 WordPress 改問 `update_themes_github.com`，不會拿 wordpress.org 上同名的佈景來比對。
2. 佈景讀 **main 分支**的 [`update.json`](../update.json)（版本、ZIP 網址、SHA-256、需求版本）。成功快取 12 小時、失敗快取 1 小時；在「控制台 → 更新」頁會重新讀取。
3. 版本比 WordPress 上裝的新，後台就顯示「有新版本」，按一下更新，跟官方佈景一樣。
4. 下載後先比對 SHA-256，不符就停止、不安裝，佈景維持舊版。
5. 更新完成後自動清頁面快取（0.5.1 的快取清除）。

### 發布流程

1. 在工作分支改版號、`python3 scripts/package_theme.py test`：產生 ZIP，並同時改寫 `update.json`（網址固定指向 main 的 `dist/`）。
2. `validate_spec.py`（CI 也會跑）檢查 `update.json` 的版本、網址與 ZIP 雜湊一致。
3. 在 [CHANGELOG.md](../CHANGELOG.md) 加上該版本段落（`validate_spec.py` 會檢查）。
4. 開 PR、CI 通過、本人合併進 main → 各站在 12 小時內看到更新；[release.yml](../.github/workflows/release.yml) 同時自動建立 GitHub Release `v<版本>`，附 ZIP、SHA-256 與 CHANGELOG 段落（已存在則跳過）。**工作分支上的版本不會推給任何網站。**
5. 第一次仍要手動上傳一次 0.6.0 以上的版本（之前的版本沒有更新程式）。

### 🚩 仍然存在的難處

使用者在網站編輯器儲存的樣式、範本、範本組件，優先序高於佈景檔案。更新佈景檔案不等於網站外觀跟著變：
曾在編輯器改過頁尾的網站，更新後仍是自己存的那份頁尾，而且不會有提示。
「列出資料庫裡存了哪些覆寫」的檢查工具**還沒做**，是下一個該補的。在那之前，重要的版面更新要在發布說明裡提醒「若曾自訂頁首／頁尾，請在編輯器重設後再看」。

### 其他做法（沒選）

| 做法 | 沒選的原因 |
| --- | --- |
| Git Updater 等外掛 | 每站多一個外掛 |
| 自架更新端點 | 要多維護一台主機；GitHub raw 已足夠 |
| GitHub Releases API | 本 session 無法建立 Release；main 分支的 `update.json` 同樣以合併作為發布關卡 |

### 參考

- [`update_themes_{$hostname}`](https://developer.wordpress.org/reference/hooks/update_themes_hostname/)
- [Updates API 在 5.5 的使用建議](https://make.wordpress.org/core/2020/07/30/recommended-usage-of-the-updates-api-to-support-the-auto-updates-ui-for-plugins-and-themes-in-wordpress-5-5/)
