# 設計系統與完整配色

狀態：預設 Token 已定義；實際元件及編輯器尚未驗證。機器資料見 [tokens.json](../design/tokens.json)，配色版本 0.1.0。

## 語意與配對

公開 CSS 變數一律為 `--wp--preset--color--{slug}`。所有十三種色彩列於下表；primary-contrast 同時作 secondary、accent、success、warning、danger 實心底色的文字色。base/surface 上的文字使用 contrast 或 muted；連結使用 primary 並保留底線。狀態訊息必須同時有文字或圖示，不能只靠顏色。

| 配色／slug | primary | primary-contrast | secondary | accent | base | surface | contrast | muted | border | focus | success | warning | danger |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| 經典都更藍 / urban-blue | #1769AA | #FFFFFF | #174A70 | #8A4B08 | #FFFFFF | #F0F6FC | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 科技深藍 / tech-blue | #153E75 | #FFFFFF | #25476A | #6D28D9 | #FFFFFF | #EFF4FA | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 教育森林綠 / forest-green | #256B45 | #FFFFFF | #365A3C | #875500 | #FFFFFF | #F0F7F1 | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 商務墨綠 / business-green | #155E52 | #FFFFFF | #34534B | #855A11 | #FFFFFF | #EFF7F5 | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 活力暖橘 / warm-orange | #A9440B | #FFFFFF | #70452E | #1D5C7A | #FFFFFF | #FFF4EB | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 公益珊瑚紅 / coral-red | #B63E43 | #FFFFFF | #7A4046 | #25635D | #FFFFFF | #FFF1F2 | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 創意紫 / creative-purple | #713FA0 | #FFFFFF | #554169 | #176579 | #FFFFFF | #F7F1FC | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 專業石墨灰 / graphite | #374151 | #FFFFFF | #4B5563 | #175F87 | #FFFFFF | #F3F4F6 | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 文化典雅金 / classic-gold | #806013 | #FFFFFF | #66532E | #714052 | #FFFFFF | #FAF6EA | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |
| 清新湖水藍 / aqua-blue | #086D80 | #FFFFFF | #285B66 | #7049A0 | #FFFFFF | #EDF8FA | #17212B | #52606D | #778391 | #17212B | #21633B | #805900 | #A52834 |

## 共用尺寸與狀態

- 系統字型：system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif；不下載字型。正文 1rem、行高 1.65，標題行高 1.25。
- 內容寬 48rem、寬版 75rem；間距 0.25、0.5、0.75、1、1.5、2、3、4rem；圓角 0.375rem，邊框 1px。
- 按鈕：primary 底／primary-contrast 字。hover 保持色彩並加文字底線；不依賴透明度或未檢查的深淺運算。
- 卡片：surface 底／contrast 字，border 邊框；避免整張卡片嵌套多層連結。
- 表單：base 底／contrast 字，border 邊框，標籤永久可見；錯誤使用 danger 文字、圖示與 aria-invalid。
- focus-visible：2px focus 實線，2px base 間隔；適用明亮 base/surface；深色區域需測試雙色焦點環，不能直接沿用單色。
- disabled：surface 底／muted 字，保留 border；原生 disabled 或正確 aria-disabled 及事件攔截，不靠 opacity 降低可讀性。
- success/warning/danger 的純文字可置於 base/surface；實心狀態底色則使用 primary-contrast 字。
- 點擊目標至少 24×24 CSS px，主要按鈕及輸入框目標 44px 高；小螢幕表格以可鍵盤操作的捲動容器處理。

## 檢查邊界

靜態工具檢查 contrast、muted、primary、secondary、accent、success、warning、danger 對 base/surface ≥4.5:1；所有實心色與白字 ≥4.5:1；border/focus 對 base/surface ≥3:1。一般文字採 4.5:1，即使大字可放寬也不降低此預設。

這些值只覆蓋指定預設配對；不涵蓋圖片、透明疊色、第三方樣式或任意使用者修改。M1 必須額外驗證實際 DOM、鍵盤與視覺狀態。首版沒有自動產生對比色功能。
