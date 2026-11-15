# 目錄與檔案規格

本輪只建立文件、Token、驗證工具及說明檔。下列實作檔案為 M1/M2 交付規格，不代表已存在。

| 位置 | 預定內容及完成條件 |
| --- | --- |
| themes/futheme/ | M1：style.css 佈景標頭、theme.json、最小 functions.php；text domain futheme，前綴 futheme_ |
| themes/futheme/templates/ | index、home、front-page、page、single、archive、search、404.html；首頁使用可編輯內容及 Pattern，無硬寫業務資料 |
| themes/futheme/parts/ | header.html、footer.html；Navigation 使用原生區塊 |
| themes/futheme/patterns/ | hero、服務卡片、聯絡區塊；可翻譯文字及合法區塊標記 |
| themes/futheme/styles/ | urban-blue、tech-blue、forest-green、business-green、warm-orange、coral-red、creative-purple、graphite、classic-gold、aqua-blue.json；每個含 title 及相同 palette slug |
| themes/futheme/assets/ | 必要的自有 CSS；只有實際需求才新增 JS，不載入遠端字型 |
| plugins/futheme-design-bridge/ | M2：可獨立安裝外掛、元件樣式、明確的按需載入介面與 fallback |
| child-themes/ | M0/M1 只有擴充說明，沒有十套配色子佈景 |
| design/tokens.json | 配色唯一主資料；schemaVersion、paletteVersion、palettes；不直接作為 theme.json 安裝 |
| tests/ | M1 起的環境、測試案例及報告；不提交敏感副本資料 |
| scripts/ | M0 規格驗證；M1 加入產生及打包工具 |
| .github/workflows/ | M1 加入 JSON、PHP lint、WPCS、配色檢查；M3 才加入 Release 打包 |
| dist/ | 僅放驗證後產物；M0 無 ZIP |

M1 最終 ZIP 根目錄必須為 futheme/，排除測試、憑證、node_modules 與來源副本。每次打包輸出版本、檔案清單與 SHA-256。此規格不新增 REST API 或資料庫 schema。
