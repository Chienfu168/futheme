# 專案狀態

更新：2026-11-10

| 項目 | 狀態 | 證據／下一步 |
| --- | --- | --- |
| M0 規格與骨架 | 已建立 | README、docs、design 與 scripts |
| Token 靜態檢查 | 通過 | 2026-11-02：10 套 × 13 Token、260 組對比配對及本地 Markdown 連結通過；最低 3.482:1 為介面配對（門檻 3:1），文字配對均 ≥4.5:1 |
| GitHub 遠端 | 已建立 | `Chienfu168/futheme`（私人）。2026-11-10 由 WP_Themes 改名接手 |
| M1 第一刀：theme.json 與十套 Style Variations | **檔案已寫出，未在 WordPress 驗證** | theme.json v3、styles/ 十個 JSON，全部由 `scripts/generate_styles.py` 從 design/tokens.json 產生 |
| M1 第一刀：頁首頁尾 | **檔案已寫出，未在 WordPress 驗證** | parts/header.html、parts/footer.html、templates/index.html |
| 產生檔與色盤一致性 | 通過 | `python3 scripts/generate_styles.py --check` |
| PHP lint | 未跑 | 沙箱沒有 php；本機或 CI 要補 |
| **配色能否在後台切換** | **未驗證——這是 M1 要證明的那一件事** | 需要乾淨的 WordPress 站台，安裝佈景後到「外觀 → 編輯器 → 樣式」實際切換 |
| 區塊標記合法性 | 未驗證 | 範本的 HTML 沒有經過 WordPress 的區塊解析器 |
| 其餘版型（home／front-page／page／single／archive／search／404） | 未開始 | 刻意等配色切換驗證過再做 |
| 橫幅（首頁大橫幅、單篇頁首橫幅） | 未開始 | M1 後段 |
| Patterns、按鈕卡片表單契約 | 未開始 | M1 後段 |
| UR 隔離副本 | 未取得 | M2 需要去識別副本與隔離環境 |
| UR AI Assistant 原始碼 | 未取得 | 不假設既有交接文件含原始碼 |
| 效能／瀏覽器／整合 | 未測 | 不以靜態檢查替代 |
| Release 打包 | 未建立 | M3 處理 |
| 正式網站 | 未操作 | 無登入、切換或部署 |

## 現在最該做的一件事

把 `themes/futheme-core/` 裝進一個乾淨的 WordPress 站台，到「外觀 → 編輯器 → 樣式」
看十套配色是不是真的切得動。

**整個規劃建立在「十套配色靠 Style Variations 在後台切換」這個假設上，而它一次都沒被驗證過。**
如果這條不成立，後面七種版型、橫幅、外掛契約全部要重新想。所以先驗它，
不要先把版型都畫出來——那是把賭注押大，不是把風險降低。

靜態檢查全過不代表這件事成立。JSON 可解析、色盤一致、對比達標，
都只是說「檔案寫得對」，不是「WordPress 吃得下」。
