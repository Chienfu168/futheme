# 外掛整合契約

狀態：設計契約，Bridge 尚未實作。

## 公開樣式介面

所有品牌色引用 `--wp--preset--color--{slug}`；完整 slug 見設計系統。元件使用 .fu-button、.fu-card、.fu-form；狀態採 :hover、:focus-visible、:disabled／[aria-disabled="true"] 及 [aria-invalid="true"]。樣式限定 fu- 根類別。

🚩 **相容性變更（0.4.0／0.5.0）**：原本寫「不改寫全站 input 或 button」，實作後改為：佈景以 `:where()`（權重為零）為原生欄位（0.4.0）與原生送出鈕 `input[type=submit|button|reset]`（0.5.0）加上基本外觀，讓沒有自帶樣式的外掛表單（例如 Contact Form 7）跟著配色。外掛只要寫任何一個類別選擇器就會蓋過佈景，不需要 `!important`。

外掛應使用 `var(--wp--preset--color--primary, #1769AA)` 形式，文字同時引用 primary-contrast。其他角色也必須有備援；備援採 urban-blue。無 FuTheme、無 Bridge 時，外掛保有可用的自有樣式、語意 HTML 與互動功能。

M2 Bridge 預定提供 PHP `futheme_bridge_enqueue_components(array $components): void`，接受 button、card、form，去重並忽略未知名稱；於 wp_enqueue_scripts 中呼叫。提供樣式 handle futheme-ui-button、futheme-ui-card、futheme-ui-form，外掛以 function_exists 檢查可用性。此介面為待實作契約，不能現在呼叫。

動態外掛應在可確定會輸出元件時載入；不可只靠搜尋 post_content 判斷，因短代碼、模板及動態區塊會漏判。表單依賴 button 時只載入一次。編輯器預覽須另行驗證，不能把前台 hook 當成編輯器已支援。

## 整合順序

1. 取得 UR AI Assistant 原始碼、授權與實際介面清單，確認一般 DOM、iframe 或 Shadow DOM 邊界。
2. 以虛擬問答呈現按鈕、卡片與表單，驗證十套配色、手動修改及無 FuTheme 的 fallback；禁止觸發真實 API 計費或寄信。
3. M2 完成一種外掛，後續完整版本加入第二種外掛；未選定第二種前不宣稱全外掛相容。

M1 可用測試 fixture 驗證 CSS 契約，fixture 不能替代 UR 外掛整合成果。Modal、Tabs、Pagination 等本輪僅保留擴充方向。

## 實測相容的外掛（fudawang.com 現用清單，2026-11-20）

版本與正式站相同；重現方式：`bash tests/env/site-plugins.sh --activate`。詳見 [報告](../tests/reports/2026-11-20-051-site-plugins.md)。

| 外掛 | 版本 | 影響範圍 | 結果 |
| --- | --- | --- | --- |
| Contact Form 7 | 6.2.5 | 前台表單 | 通過；佈景補上送出鈕與錯誤訊息配色（只在 CF7 樣式載入時） |
| Yoast SEO | 28.9 | SEO 標記 | 通過 |
| WP Fastest Cache | 1.5.5 | 頁面快取、CSS／JS 合併 | 通過；**換配色原本會被快取擋住**，0.5.1 起佈景在儲存樣式／範本時自動清快取 |
| Performance Lab 與 10 個模組 | 見報告 | 圖片、預載、轉場 | 通過；Optimization Detective 會標記佈景的預設橫幅圖，屬正常 |
| Site Kit by Google | 1.192.0 | 前台追蹤碼 | 通過 |
| Wordfence、WPS Hide Login、Limit Login Attempts Reloaded | 9.1.1、1.9.20、3.4.5 | 後台與登入 | 前台通過；Wordfence 在測試環境（SQLite、無防火牆規則檔）有自身警告，與佈景無關 |
| 都更試算（ur-calc） | 0.3.7 | 前台試算區塊 | 通過；**跟隨十套配色**（讀 8 個 Token），試算功能正常；標題區與結果框為外掛固定深色。見 [報告](../tests/reports/2026-11-20-own-plugins.md) |
| UR AI Assistant | 1.49.0 | 前台問答介面 | 版面與功能通過；**不跟配色**（固定藍色）；沒有 API Key 時錯誤訊息被前台蓋成「連線失敗」，屬外掛端問題。見 [報告](../tests/reports/2026-11-20-own-plugins.md) |
