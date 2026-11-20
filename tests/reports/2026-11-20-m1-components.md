# 0.4.0 M1 元件、Patterns 與 CI（2026-11-20）

## 改動

| 區域 | 做法 |
| --- | --- |
| 服務卡片 Pattern | `patterns/services.php`（`.fu-services`）：標題＋說明＋三張 `.fu-card`；寬版格線最多三欄、最小欄寬 16rem，手機一欄 |
| 聯絡 Pattern | `patterns/contact.php`（`.fu-contact`）：primary 底，電話／電子郵件／地址三欄說明文字＋「聯絡我們」按鈕。**不含表單、不儲存資料**；聯絡資料由站長自行填入 |
| 單篇留言區 | `patterns/hidden-comments.php`（`.fu-comments`），插在 single.html：留言列表、分頁、原生留言表單；不顯示大頭貼，不向 Gravatar 發請求 |
| 表單元件 | 輸入框／下拉／多行：base 底、contrast 字、1px border、高 ≥44px；核取方塊 accent-color primary |
| 錯誤狀態 | `[aria-invalid=true]` danger 邊框（inset 陰影加粗，不移動版面）；`.fu-field-error` danger 文字＋「!」圖示，用 `aria-describedby` 連到欄位 |
| disabled | 按鈕與欄位：surface 底、muted 字、保留邊框、`not-allowed`；不用 opacity |
| 外框按鈕 | `is-style-outline`：透明底、primary 字與 2px 邊框 |
| 頁面寬度 | page／single 的內容區改為 `alignfull`，頁面內的全寬區塊（服務、聯絡）才能真正滿版；一般段落仍是 48rem |
| 修正 | slug `border` 的 `has-border-color` 類別撞名，有邊框的區塊文字原本被染成 border 色（3.86:1），改為繼承；文章卡片「無圖片」色塊只限文章列表 |
| WPCS | `phpcs.xml.dist`：WordPress 標準，只排除兩條「註解須以半形句點結尾」（中文註解以「。」結尾）；`composer.json`／`composer.lock` 鎖 WPCS 3.4.1，只在開發用，不進 ZIP |
| CI | `.github/workflows/ci.yml`：靜態檢查＋WordPress 實測，見 [workflows/README](../../.github/workflows/README.md) |
| 翻譯 | 新增 24 條繁中；`make-pot` 用 `--skip-theme-json`（容器連不到 develop.svn.wordpress.org，theme.json 字串照舊手動維護） |
| 版本 | 0.3.0 → **0.4.0** |

## 驗證（WordPress 7.1.4、PHP 8.3.6、SQLite、Chromium／Playwright 1.63.0）

| 檢查 | 結果 | 方法 |
| --- | --- | --- |
| 元件狀態 | 十套配色 × 33 項全通過 | [components.js](../tools/components.js) 讀 computed style 比對 Token 並計算對比：按鈕、hover、外框按鈕、欄位一般／錯誤／停用、焦點框、搜尋、服務卡片、聯絡區塊、留言表單；fixture 見 [components.html](../fixtures/components.html) |
| 最低對比 | 通過 | 預設配色：按鈕 5.77、停用 5.93、錯誤文字 7.11、欄位邊框 3.86（門檻 3:1）、卡片文字 14.97 |
| 編輯器區塊合法性 | 通過 | [editor-validity.js](../tools/editor-validity.js)：8 個模板＋頁首頁尾（single 含留言區 31 個區塊）＋ fixture 頁 36 個區塊全部 `isValid` |
| 前台冒煙 | 通過 | [smoke-browser.js](../tools/smoke-browser.js)：360／1280 無橫向溢出、無 JS 錯誤（修正：預期的 404 主文件不再誤報） |
| 繁中 | 通過 | 站台語言 zh_TW：Pattern 文字、「留言」、頁首「選單」按鈕顯示中文 |
| WPCS／PHP lint | 通過 | `vendor/bin/phpcs`、`php -l` |
| 配色／schema | 通過 | `validate_spec.py`、`build_styles.py --check`、`validate_theme_json.py` |
| PHP 錯誤記錄 | 無 | `debug.log` 不存在 |
| GitHub CI | 通過 | run #5（`3a49124`）：Static checks 與 WordPress runtime 全部步驟成功；run #1 抓到連結檢查誤掃 `vendor/`，已修正 |
| ZIP 安裝／更新／回退 | 通過 | 乾淨站（不 symlink 佈景）：裝 0.3.0 ZIP 並啟用 → 以 0.4.0 ZIP 取代（版本 0.4.0、前台 200、新頁尾與預設橫幅出現、9 個 futheme Pattern 註冊）→ 以 0.3.0 ZIP 回退（版本 0.3.0、前台 200）；無 PHP 錯誤。用 WP-CLI `theme install --force`，非後台上傳畫面 |

ZIP：`dist/futheme-0.4.0-test.zip`，SHA-256 `1b23518b575195466f1b7703d05060a9535419bbc26e2bd215385613b2678491`，39 檔，不含 vendor／composer。

截圖：[元件 1280](img/2026-11-20-components-1280.png)、[元件 360](img/2026-11-20-components-360.png)、[留言區](img/2026-11-20-comments-1280.png)。

## 未驗證

- 編輯器內手動改單色／字型、編輯器「重設」、資料庫覆寫行為未測。
- 只測 Chromium；Firefox、Safari、手機實機、200% 縮放未測。
- 留言送出後的錯誤頁（例如必填欄位空白）由 WordPress 核心產生，佈景沒有樣式，未測。
