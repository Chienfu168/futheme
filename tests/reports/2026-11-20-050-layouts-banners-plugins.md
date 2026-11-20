# 0.5.0 頁首頁尾多款、六款橫幅、作者署名、外掛與跨瀏覽器（2026-11-20）

依 2026-11-20 本人確認的方向（[需求](../../docs/requirements.md) 第七節）與建議做法實作。

## 改動

| 區域 | 做法 |
| --- | --- |
| 作者 | `style.css` Author 改為「福大資訊」，Author URI 仍為 fudawang.com |
| 頁尾署名 | 預設頁尾底列右側：「佈景設計：福大資訊」，連到 fudawang.com；站長可在網站編輯器修改或刪除 |
| 頁首三款 | `header`（標準：左標誌右選單）、`header-centered`（置中）、`header-cta`（選單＋行動按鈕）。`parts/header.html` 改為引用 `futheme/header` Pattern，文字才能翻譯 |
| 頁尾三款 | `footer`（簡潔＋署名）、`footer-columns`（網站／連結／聯絡三欄＋署名）、`footer-promo`（署名列另連 ur-promoter.com）。頁尾標題改為 primary-contrast（原本三欄款的標題是深色字在深色底上） |
| 換款方式 | 網站編輯器 → 範本組件 → 頁首或頁尾 → 選取整個區塊 →「取代」，從 FuTheme 的款式中選 |
| 六款橫幅圖 | `scripts/make_banners.py` 以程式繪製原創灰階圖：城市、自然、商務、社區、科技、文化；每款 1920／960 兩種寬度，WebP，3～20KB。亮度一律壓在 50～165 |
| 橫幅 Pattern | `banner-<主題>` 六款，Cover 區塊＋80% 主色遮罩；插入後可直接換成媒體庫圖片 |
| 預設圖 | 未設定圖片的橫幅仍自動用城市圖；可用濾鏡 `futheme_default_banner` 換成其他五款或關閉。原 `banner-1920.webp` 改名為 `banner-city-*`，插入位置改為與 WordPress 儲存的 Cover 相同（圖片在遮罩之前） |
| 外掛表單送出鈕 | 原生 `input[type=submit/button/reset]` 外觀同佈景按鈕（`:where()` 零權重，外掛自訂樣式仍優先） |
| Contact Form 7 | 錯誤提示與結果訊息改用 danger／warning／success Token；**只在 CF7 樣式表載入時**附加，約 0.6KB |
| 跨瀏覽器 | 測試腳本以 `FUTHEME_BROWSER` 選 chromium／firefox／webkit；CI 的 WordPress job 改為三種瀏覽器矩陣 |
| 版本 | 0.4.0 → **0.5.0** |

## 驗證（本機：WordPress 7.1.4、PHP 8.3.6、SQLite、Chromium／Playwright 1.63.0）

| 檢查 | 結果 | 方法 |
| --- | --- | --- |
| 橫幅文字對比 | 通過（計算值） | 圖片亮度 50～165，80% 主色遮罩下 primary-contrast 最低 **4.88:1**（classic-gold，最亮像素） |
| 編輯器區塊合法性 | 通過 | 8 個模板＋頁首頁尾＋元件 fixture（36 區塊）＋[版型 fixture](../fixtures/layouts.html)（12 個新 Pattern，91 區塊）全部 `isValid` |
| 版型前台 | 通過 | 360／1280 無橫向溢出、無 JS 錯誤；六款橫幅圖都實際載入；三款頁尾標題為白字 |
| Contact Form 7 6.2.5 | 通過 10 項 | [plugins.js](../tools/plugins.js)：欄位與送出鈕套用佈景樣式（送出鈕原本是瀏覽器預設灰色 21px 高）、空白送出後欄位 danger 邊框、錯誤提示 danger 字 7.11:1（原本 CF7 自帶紅 4.62:1）、結果訊息顯示 |
| Yoast SEO 28.9 | 通過 | 首頁輸出 schema、首頁／單篇／搜尋無 JS 錯誤、無 PHP 錯誤 |
| 元件狀態、冒煙 | 通過 | 加入通用送出鈕樣式後 components.js 33 項、smoke-browser 仍全數通過 |
| 繁中 | 通過 | 新增 28 條；「佈景設計：福大資訊 · 都更推廣：ur-promoter.com」、「連結」「聯絡方式」正確 |
| WPCS／PHP lint／配色 | 通過 | |
| 後台上傳更新 | 通過 | 乾淨站 0.4.0 →「上傳佈景主題」0.5.0 ZIP：比較畫面顯示版本 0.4.0→0.5.0、作者 FuDaWang→福大資訊；取代後佈景維持啟用、新頁尾署名與 `banner-city` 預設圖出現、`banner-tech-960.webp` 200；無 PHP 錯誤 |

ZIP：`dist/futheme-0.5.0-test.zip`，SHA-256 `c52734c47c15acfe1e424ab96df33cdcc2b6b40aa5b450a79d464cc61cefcc22`，61 檔，226KB（12 張橫幅圖約 100KB）。

截圖：[版型總覽](img/2026-11-20-050-layouts.png)、[六款橫幅原圖](img/2026-11-20-050-banners.png)、[CF7 錯誤狀態](img/2026-11-20-050-cf7-errors.png)。

## 未驗證

- **Firefox、WebKit** 只在 GitHub CI 跑（本機只有 Chromium），結果見下一次 CI。
- 真機 Safari／iOS、Android 未測（WebKit 引擎不等於 iOS Safari）。
- 網站編輯器內「取代」頁首／頁尾的操作流程未自動化實測，只驗證各款區塊合法與前台顯示。
- 其他外掛（WooCommerce、Rank Math、Elementor 等）與 ur-calc 未測；ur-calc 不在本 session 可存取的 repo 範圍。
