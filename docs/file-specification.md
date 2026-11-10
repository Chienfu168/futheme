# 目錄與檔案規格

M1（2026-11-10）已建立 Core 佈景、生成與打包工具、測試環境與 CI 設定；plugins／Bridge 仍為 M2 規格，尚不存在。

| 位置 | 預定內容及完成條件 |
| --- | --- |
| themes/futheme-core/ | M1：style.css 佈景標頭、theme.json、最小 functions.php；text domain futheme-core，前綴 futheme_ |
| themes/futheme-core/templates/ | index、home、front-page、page、page-no-banner、single、archive、search、404.html；首頁使用可編輯內容及 Pattern，無硬寫業務資料 |
| themes/futheme-core/parts/ | header.html（主色底）、header-overlay.html（首頁透明）、footer.html；Navigation 使用原生區塊 |
| themes/futheme-core/patterns/ | front-hero、front-latest、front-services、front-stats、front-cta，及版型用的 hidden-*；可翻譯文字及合法區塊標記 |
| themes/futheme-core/styles/ | urban-blue、tech-blue、forest-green、business-green、warm-orange、coral-red、creative-purple、graphite、classic-gold、aqua-blue.json；每個含 title 及相同 palette slug，由產生器輸出。sections/ 放四個區段樣式（block style variations） |
| themes/futheme-core/assets/ | 必要的自有 CSS；只有實際需求才新增 JS，不載入遠端字型 |
| plugins/futheme-design-bridge/ | M2：可獨立安裝外掛、元件樣式、明確的按需載入介面與 fallback |
| child-themes/ | M0/M1 只有擴充說明，沒有十套配色子佈景 |
| design/tokens.json | 配色唯一主資料；schemaVersion、paletteVersion、palettes；不直接作為 theme.json 安裝 |
| tests/ | env/（測試站）、e2e/（瀏覽器檢查）、reports/（證據）；不提交 WordPress 本體或敏感資料 |
| scripts/ | validate_spec.py（規格）、build_theme_styles.py（產生配色檔）、package_theme.sh（打包） |
| .github/workflows/ | ci.yml：規格、配色、JSON、PHP lint、WPCS 與 WordPress 實測；M3 才加入 Release 打包 |
| dist/ | package_theme.sh 產物（ZIP、檔案清單、SHA-256），不提交 |

M1 最終 ZIP 根目錄必須為 futheme-core/，排除測試、憑證、node_modules 與來源副本。每次打包輸出版本、檔案清單與 SHA-256。此規格不新增 REST API 或資料庫 schema。
