# 外掛整合契約

狀態：設計契約，Bridge 尚未實作。

## 公開樣式介面

所有品牌色引用 `--wp--preset--color--{slug}`；完整 slug 見設計系統。元件使用 .fu-button、.fu-card、.fu-form；狀態採 :hover、:focus-visible、:disabled／[aria-disabled="true"] 及 [aria-invalid="true"]。樣式限定 fu- 根類別，不改寫全站 input 或 button。

外掛應使用 `var(--wp--preset--color--primary, #1769AA)` 形式，文字同時引用 primary-contrast。其他角色也必須有備援；備援採 urban-blue。無 FuTheme、無 Bridge 時，外掛保有可用的自有樣式、語意 HTML 與互動功能。

M2 Bridge 預定提供 PHP `futheme_bridge_enqueue_components(array $components): void`，接受 button、card、form，去重並忽略未知名稱；於 wp_enqueue_scripts 中呼叫。提供樣式 handle futheme-ui-button、futheme-ui-card、futheme-ui-form，外掛以 function_exists 檢查可用性。此介面為待實作契約，不能現在呼叫。

動態外掛應在可確定會輸出元件時載入；不可只靠搜尋 post_content 判斷，因短代碼、模板及動態區塊會漏判。表單依賴 button 時只載入一次。編輯器預覽須另行驗證，不能把前台 hook 當成編輯器已支援。

## 整合順序

1. 取得 UR AI Assistant 原始碼、授權與實際介面清單，確認一般 DOM、iframe 或 Shadow DOM 邊界。
2. 以虛擬問答呈現按鈕、卡片與表單，驗證十套配色、手動修改及無 FuTheme 的 fallback；禁止觸發真實 API 計費或寄信。
3. M2 完成一種外掛，後續完整版本加入第二種外掛；未選定第二種前不宣稱全外掛相容。

M1 可用測試 fixture 驗證 CSS 契約，fixture 不能替代 UR 外掛整合成果。Modal、Tabs、Pagination 等本輪僅保留擴充方向。
