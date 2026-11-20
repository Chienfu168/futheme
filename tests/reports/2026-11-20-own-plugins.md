# 自家外掛融合度測試：都更試算、UR AI Assistant（2026-11-20）

本人要求以自己設計的外掛做融合度測試。版本與 fudawang 正式站相同。未修改兩個外掛的程式碼。

| 外掛 | 版本 | 來源 |
| --- | --- | --- |
| 都更試算 | 0.3.7 | `Chienfu168/ur-calc` `plugin/`（commit `1e7b4cc`） |
| UR AI Assistant | 1.49.0 | `Chienfu168/FU-AI-UR` `ur-ai-assistant/`（commit `121d04a`） |

測試站沒有 OpenAI API Key，另裝 `futheme-test-block-ai.php` 擋下所有 OpenAI／LINE 對外請求，**不會產生 API 費用或推播**。
同時啟用 CF7、Yoast、WP Fastest Cache、Site Kit 與 Performance Lab 系列（安全外掛三個關閉，以便登入編輯器；前一份報告已驗證它們不影響前台）。

測試頁：[own-plugins.html](../fixtures/own-plugins.html)（`[ur_calc]`、`[ur_ai_assistant]`、`[ur_ai_faq_kb_page]`）；腳本：[own-plugins.js](../tools/own-plugins.js)。

## 結果（十套配色 × 每套 11 項，全部通過）

| 項目 | 結果 |
| --- | --- |
| 版面 | 360／1280 無橫向溢出、無 JS 錯誤、無 PHP 錯誤 |
| 都更試算：跟隨配色 | **是**。primary、文字、邊框都等於佈景 Token，十套配色逐一比對相符（`--ur-calc-primary` 讀 `--wp--preset--color--primary`）；primary 上的文字對比最低 ≥4.5:1 |
| 都更試算：功能 | 基地 100 坪 × 法定容積率 225% → 顯示「225.00 坪」；輸入框高 44px（佈景表單樣式與外掛樣式相容） |
| 都更試算：固定色 | 頂部標題區與「即時試算結果」大數字框是外掛自訂的深藍綠／深藍，不跟配色 |
| UR AI Assistant：跟隨配色 | **否**。標題區藍色漸層與「送出提問」鈕 `rgb(37, 99, 235)` 在十套配色下都相同；CSS 沒有讀任何 `--wp--preset--*` 變數。白字對比在藍底上通過 |
| UR AI Assistant：功能 | 送出問題 → 沒有 API Key 時顯示「連線失敗，請稍後再試。」；無對外 AI 請求 |
| 佈景 vs 官方佈景 | 同一操作在 Twenty Twenty-Five 下結果相同（同樣顯示「連線失敗」），確認與 FuTheme 無關 |

截圖：[四套配色對照](img/2026-11-20-own-plugins-palettes.png)（左起經典都更藍、創意紫、活力暖橘、教育森林綠）、[手機 360](img/2026-11-20-own-plugins-360.png)。

## 發現的外掛端問題（建議在各自的 repo 修，佈景不需改）

1. **UR AI Assistant 錯誤訊息被蓋掉**：沒有 API Key 且 FAQ 未命中時，伺服器回 HTTP 500 並附清楚原因（「尚未設定 OpenAI API Key，且 FAQ 未命中，無法產生 AI 回答。」），
   但前台腳本把所有非 2xx 都當成網路失敗，畫面只顯示「連線失敗，請稍後再試。」。建議：這類可預期的狀況回 200＋`success:false`，或前台在非 2xx 時讀 `data.message`。
2. **UR AI Assistant 不跟配色**：建議照 [色彩 slug 契約](../../docs/color-slug-contract.md) 改成 `var(--wp--preset--color--primary, #2563eb)` 這種寫法，沒裝 FuTheme 的網站仍是原本的藍色。
3. **都更試算的標題區與結果框**是固定深色。若希望整體跟著配色，可改讀 `--wp--preset--color--secondary`（各配色都已驗證白字對比 ≥7:1）；保留固定色也可以，屬設計選擇。

## 未驗證

- 都更試算以區塊（`ur-calc/calculator`）插入的情境（本次用短代碼，兩者輸出相同的 `.ur-calc` 結構）。
- UR AI Assistant 有 API Key 時的真實回答畫面、LINE Bot、市場行情抓取、後台分析頁（會連外部服務，本次刻意不測）。
- 只測 Chromium；這兩個外掛不在 CI（ur-calc 是私人 repo）。

## 後續：修正已在測試站驗證，交給外掛專案套用（2026-11-20）

本人要求「能修就修，不能修就給建議」。本 session 對兩個外掛 repo 只有讀取權限，所以在測試站的外掛副本上修好、驗證，
做成修正檔交給各外掛專案套用；**未推送到兩個外掛 repo**。

| 外掛 | 修正 | 驗證 |
| --- | --- | --- |
| UR AI Assistant | ① AJAX 失敗時優先顯示伺服器的 `data.message`（三處）；② `public.css` 五個品牌藍改讀 `--wp--preset--color--primary／secondary／surface`，備援為原色 | 十套配色跟隨、白字最低 5.59:1；畫面顯示「尚未設定 OpenAI API Key…」；Twenty Twenty-Five 下仍是原本的藍 |
| 都更試算 | 標頭帶與結果框改用 secondary；深底上的亮色、次要文字由 primary 推出 | 十套配色跟隨；亮色／深底最低 4.86:1、白字／深底最低 7.39:1；`check.sh` 全過；試算 225.00 坪 |

修正後十套 × 11 項全數通過。UR AI Assistant 其餘 6 個樣式表（計算機、市場行情等）仍是固定色，留給外掛專案。
