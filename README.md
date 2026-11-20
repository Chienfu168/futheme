# FuTheme

佈景版本：**0.5.0**（2026-11-20）｜作者：福大資訊｜階段：M1 核心原型大致完成，**仍為測試版**

多站共用的 Block Theme：十套品牌配色在網站編輯器切換，只負責呈現、不含業務邏輯。
已有首頁與頁首橫幅、文章卡片、服務卡片、聯絡區塊、留言區，以及按鈕與表單的完整狀態；每次推送都由 GitHub CI 自動檢查。
**目前只建議裝在測試站**：還沒在正式網站的副本上驗證，也還沒和官方佈景比較效能。未連接或修改任何正式網站。

## 測試版下載安裝

0.2.0 起有首頁大橫幅、文章卡片、單篇／單頁頁首橫幅與深色頁尾。首頁橫幅的圖片在「外觀 → 編輯器 → 範本 → 首頁」點橫幅換圖；單篇／單頁橫幅用文章的「精選圖片」。0.3.0 起沒設圖片時顯示預設橫幅圖（灰階城市剪影，疊上目前配色的主色）。

0.5.0 新增：
- **頁首／頁尾各三款**：網站編輯器 → 範本組件 → 頁首（或頁尾）→ 選取整個區塊 →「取代」，從 FuTheme 款式挑選。頁尾預設有「佈景設計：福大資訊」署名，可自行修改或刪除；另有一款同時連到 ur-promoter.com。
- **六款橫幅圖**：城市、自然、商務、社區、科技、文化。編輯器「+」→ 區塊版面配置 → FuTheme →「橫幅：…」插入，圖片會跟著配色變色，也可以換成自己的照片。
- 與 Contact Form 7、Yoast SEO 實測相容；表單錯誤提示跟著配色。

0.4.0 新增「服務卡片」與「聯絡區塊」兩個區塊樣式（編輯器 →「+」新增區塊 → 區塊版面配置 → FuTheme），文章頁下方有留言區。

**選單**：頁首與頁尾請在「外觀 → 編輯器 → 導覽」建立選單並指定。沒指定時 WordPress 會自動列出所有頁面；0.2.1 起頁面超過 6 個時，頁首自動收成選單按鈕、頁尾不列出。

1. 在 GitHub 開啟 [dist/futheme-0.5.0-test.zip](dist/futheme-0.5.0-test.zip)，按右上角下載（Download raw file）。
   不要用 repo 首頁的「Code → Download ZIP」，那是整個專案，WordPress 無法安裝。
2. 可比對 SHA-256：[futheme-0.5.0-test.sha256](dist/futheme-0.5.0-test.sha256)。
3. WordPress 後台「外觀 → 佈景主題 → 安裝佈景主題 → 上傳佈景主題」，選 ZIP，安裝後啟用。
4. 換配色：「外觀 → 編輯器 → 樣式」選一套後儲存。
5. 從舊版升級：直接上傳新 ZIP，選「使用已上傳版本取代已安裝版本」。
6. 如果之前裝過舊名稱「FuTheme Core」（`futheme-core`），新版會另外裝成「FuTheme」，舊的那個可直接刪除（沒啟用過就沒有影響）。

需求：WordPress 7.1 以上、PHP 8.3 以上（只測過這個組合）。

## 閱讀順序

0. [需求確認（本人 2026-11-10）](docs/requirements.md)
0.6 [色彩 slug 是對外契約](docs/color-slug-contract.md) ← 改 token 名稱之前必讀
1. [架構與技術可行性](docs/architecture.md)
2. [設計系統及完整配色](docs/design-system.md)
3. [目錄與檔案規格](docs/file-specification.md)
4. [開發工作與里程碑](docs/work-items.md)
5. [效能預算與測試方案](docs/performance-budget.md)
6. [外掛整合契約](docs/plugin-integration.md)
7. [風險及相容性](docs/risks.md)
8. [部署與回復](docs/deployment.md)
9. [執行狀態](docs/status.md)

## 本機驗證

```bash
python3 scripts/validate_spec.py       # Token、對比、文件連結
python3 scripts/build_styles.py --check # theme.json／styles 與 Token 一致
python3 scripts/validate_theme_json.py  # theme.json schema
bash tests/env/setup.sh                 # 建立本機測試站（.wp-test/）
```

色盤唯一主資料是 `design/tokens.json`。theme.json 的色盤與 `styles/*.json` 由 `python3 scripts/build_styles.py` 產生，不要手改，`--check` 會擋下不一致；theme.json 其餘欄位手動維護。靜態檢查不代表效能、無障礙或外掛整合已通過，實測證據見 `tests/reports/`。

首輪目標 WordPress 7.1.4；不宣稱向下相容。實作前再次確認官方修補版與 PHP 要求。UR 原有 Sydney 優化工作不因本專案而自動變更。

儲存庫：[Chienfu168/futheme](https://github.com/Chienfu168/futheme)（私人）。
2026-11-10 由 `WP_Themes` 改名接手——舊的傳統 PHP 骨架沒有網站在用，也做不到「配色後台切換」，
已移除並留在 git 歷史（`b58ca3b`）。共用佈景只有一套，不開第二個 repo。

第一個採用網站：**fudawang.com**（2026-11-15 決定）。

下一步：M1 的 Pattern、橫幅與元件。UR 隔離副本及 UR AI Assistant 原始碼尚未取得（M2 依賴）；正式部署未建立。
