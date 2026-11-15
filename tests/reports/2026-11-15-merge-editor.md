# 合併 main 與網站編輯器實測（2026-11-15）

## 合併

`main` 上 2026-11-10～14 的平行工作併入 `claude/nifty-goldberg-tx85e0`：

| 來自 main | 處理 |
| --- | --- |
| docs/adoption.md、color-slug-contract.md、deployment.md 更新機制 | 全部保留；adoption 記下第一個採用者 fudawang；移除連到 repo 外的連結 |
| 佈景檔（theme.json、styles、頁首頁尾、index） | 以已實測版本為準（main 版未經 WordPress 驗證） |
| functions.php 的 `FUTHEME_CORE_VERSION`、post-thumbnails、strict_types | 補入 |
| style.css 的 Author、Tags | 補入；`Requires PHP` 維持 8.3（只測過 8.3） |
| scripts/generate_styles.py | 移除：會整份重寫 theme.json，與手動維護的版面設定衝突；保留 build_styles.py |

色彩 slug：兩邊同一組 13 個，ur-calc 讀的 primary、surface、contrast、border 都在。

## 驗證（合併後重跑）

| 檢查 | 結果 |
| --- | --- |
| validate_spec.py、build_styles.py --check、validate_theme_json.py、php -l | 全過 |
| `FUTHEME_CORE_VERSION`、post-thumbnails 支援 | 0.1.0、true |
| 十套配色＋重設（寫入 Global Styles） | 11/11 通過 |
| 瀏覽器冒煙測試（360／1280px） | 無橫向溢出，Tab 焦點正常 |
| **網站編輯器 UI 切換** | **通過**：見下 |
| 重打測試 ZIP 並在乾淨站「取代現有版本」更新 | 通過；新 SHA-256 `7ff1f88f2026ac5b4dc9b4befb465583b289d1b9c8460c0d75d32991e8bed86f` |

## 網站編輯器 UI

以 [editor-switch.js](../tools/editor-switch.js) 用 Chromium 153 實際操作：登入 → 外觀 → 編輯器 → 樣式 → 瀏覽樣式。

- 列出「預設」與十套配色，名稱為 tokens 的中文標題。
- 點「專業石墨灰」後右側預覽立即變色。
- 「檢視 1 個變更…」→「儲存」，按鈕變「已儲存」。
- 前台 13 個色彩變數與 graphite tokens 全部相符。

這補上了 `main` 狀態檔列為「M1 要證明的那一件事」的驗證：十套配色可在後台切換。

## 未測

- 在編輯器內手動改單一顏色與字型、編輯器「重設」按鈕、編輯器畫面與前台的逐項視覺比對。
- Firefox、Safari、手機實機；MySQL。

## 補：佈景縮圖（2026-11-15）

使用者在後台看到 FuTheme 沒有縮圖：佈景缺 `screenshot.png`。以測試站首頁 1200×900 截圖補上（如實呈現目前尚無橫幅的樣子，M1 完成後要換）。
重打 ZIP：32 檔，SHA-256 `694487f5d8a1385615ab68245a2895d04e47c0131506a817bd014fffb5948d2f`；乾淨站 `wp theme install --force` 後 `get_screenshot()` 回傳網址，HTTP 200 image/png。
