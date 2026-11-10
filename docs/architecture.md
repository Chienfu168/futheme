# 架構與技術可行性

狀態：已決定的設計，尚未實作。查證日期：2026-11-02。

## 版本與原生能力

官方版本紀錄當日列最新正式版為 WordPress 7.1.4（2026-10-28）。以此作首輪目標與最低驗證版本，不使用 Beta 或 Gutenberg 外掛作必要相依。實作開始時鎖定實際修補版、PHP、資料庫與測試映像版本，記錄於測試報告；PHP 以官方當時支援要求與主機能力共同確認，不從 UR 舊快照推定。

- [官方版本紀錄](https://wordpress.org/download/releases/)
- [Block Theme 結構](https://developer.wordpress.org/themes/core-concepts/theme-structure/)
- [Global Settings and Styles](https://developer.wordpress.org/themes/core-concepts/global-settings-and-styles/)
- [Style Variations](https://developer.wordpress.org/themes/global-settings-and-styles/style-variations/)
- [Child Themes](https://developer.wordpress.org/themes/advanced-topics/child-themes/)

Block Theme 以 HTML 區塊模板和 theme.json 管理呈現；Style Variations 放於 styles/，共用同一母佈景。子佈景可覆寫模板及設定，只在品牌結構不同時建立。技術判斷高度可行，仍須 WordPress 執行驗證。

## 元件責任

| 元件 | 責任 | 不包含 |
| --- | --- | --- |
| Core | 模板、原生區塊樣式、排版、RWD、十套樣式 | AI、RSS、會員、資料匯入 |
| UI Standard | Token、CSS 類別與互動狀態契約 | 強制載入全套元件 |
| Design Bridge | 選用元件樣式、跨佈景 fallback | 自動改寫第三方 DOM |
| Site Child | 特殊模板與品牌結構 | 單純更換色碼 |

## 資料流與優先序

預設設計資料 → 建置時產生 theme.json／styles JSON → WordPress 原生 Global Styles → CSS 變數 → 原生區塊及外掛。使用者在網站編輯器儲存的樣式具有較高優先序；資料庫模板修改也可能遮蔽新版檔案。不得以更新檔案等同網站已更新外觀。

配色主資料為 design/tokens.json，版本由該清單追蹤；生成 WordPress 設定時只輸出 schema 支援欄位。首版採 theme.json version 3，依目標 WordPress 版本 schema 驗證；尚無生成器。Core 的預設配色為 urban-blue，十套 variation 仍包含 urban-blue，供明確選回。

前台不執行配色計算或配色切換 JS；無自訂設定頁、無配色資料表、無每次請求掃描。手動變更 primary 不會保證自動調整其他色，需由管理者檢查對比。
