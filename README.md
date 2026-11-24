# FuTheme

佈景版本：**1.0.0**（2026-11-23，正式版）｜作者：[福大資訊](https://www.fudawang.com)｜推廣：[ur-promoter.com](https://www.ur-promoter.com)

簡潔、輕量、適合多產業的 WordPress 區塊佈景。十套配色在網站編輯器一鍵切換，頁首、頁尾、橫幅各有多款可換；
只負責呈現、不含業務邏輯，沒有前台 JavaScript、不載入外部字型。

## 下載安裝

> **直接下載可安裝的 ZIP**：<https://github.com/Chienfu168/futheme/raw/main/dist/futheme-1.0.0.zip>
> 也可以到 [Releases](https://github.com/Chienfu168/futheme/releases) 下載各版本，更新內容見 [CHANGELOG](CHANGELOG.md)。
> 不要用綠色「Code → Download ZIP」：那是整個專案，WordPress 會顯示「缺少 style.css 樣式表」而安裝失敗。

1. 後台「外觀 → 佈景主題 → 安裝佈景主題 → 上傳佈景主題」，選上面下載的 ZIP。
2. 先按「**即時預覽**」看過首頁、文章與常用頁面，再按「啟用」。
3. 「外觀 → 編輯器 → 導覽」建立或匯入選單（可直接匯入舊佈景的傳統選單）。
4. 「外觀 → 編輯器 → 樣式」選一套配色後儲存。
5. 有用頁面快取外掛的話，清除一次快取（FuTheme 之後儲存樣式時會自動清）。

**更新**：裝了 0.6.0 以上的網站，有新版時後台會顯示「有新版本」，按一下就能更新；下載後會先比對 SHA-256，不符就不安裝。
**退回**：「外觀 → 佈景主題」啟用原本的佈景即可，文章、頁面、外掛設定都不受影響。

## 功能

| 類別 | 內容 |
| --- | --- |
| 配色 | 十套（經典都更藍、科技深藍、教育森林綠、商務墨綠、活力暖橘、公益珊瑚紅、創意紫、專業石墨灰、文化典雅金、清新湖水藍），文字對比皆符合 WCAG AA |
| 頁首 | 標準、置中、含行動按鈕：網站編輯器 → 範本組件 → 頁首 → 選取整個區塊 →「取代」 |
| 頁尾 | 簡潔、三欄、含推廣連結；預設有「佈景設計：福大資訊」署名，可修改或刪除 |
| 橫幅 | 六款內建圖（城市、自然、商務、社區、科技、文化），圖片會跟著配色變色；未設定圖片時自動用城市圖；可換成自己的照片 |
| 區塊樣式 | 首頁大橫幅、服務卡片、聯絡區塊（編輯器「+」→ 區塊版面配置 → FuTheme） |
| 表單與按鈕 | hover、鍵盤焦點、停用、錯誤狀態；Contact Form 7 的錯誤提示跟著配色 |
| 外掛相容 | 實測 fudawang 現用的 20 個外掛；外掛可讀 `--wp--custom--futheme--*` 跟著配色（見 [色彩契約](docs/color-slug-contract.md)） |
| 快取 | 儲存樣式、範本或佈景更新後，自動清除 WP Fastest Cache、LiteSpeed Cache、WP Rocket、W3 Total Cache、WP Super Cache 的頁面快取 |
| 選單保護 | 沒指定選單又頁面很多時，頁首自動收成「選單」按鈕，頁尾不列出全部頁面 |

## 已知限制

- 在網站編輯器存過的頁首、頁尾、範本會優先於佈景檔案：之後的佈景更新不會改到那些部分（要在編輯器「重設」才會套用新版）。
- 真機 iOS／Android 與效能比較尚未正式量測（自動測試涵蓋 Chromium、Firefox、WebKit 引擎）。

需求：WordPress 7.1 以上、PHP 8.3 以上（實測 WordPress 7.1.5＋PHP 8.3）。

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

測試環境鎖定 WordPress 7.1.5（與 fudawang 正式站相同）；不宣稱向下相容。

儲存庫：[Chienfu168/futheme](https://github.com/Chienfu168/futheme)（公開）。
2026-11-10 由 `WP_Themes` 改名接手——舊的傳統 PHP 骨架沒有網站在用，也做不到「配色後台切換」，
已移除並留在 git 歷史（`b58ca3b`）。共用佈景只有一套，不開第二個 repo。

第一個採用網站：**fudawang.com**（2026-11-15 決定）。

1.0.0 之後：在 fudawang 正式站啟用並觀察；資料庫覆寫偵測工具、效能比較、真機測試。
