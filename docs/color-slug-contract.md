# 色彩 slug 是對外契約

建立：2026-11-14

## 規則

`design/tokens.json` 裡那 13 個語意色的 **slug 不可以改名**：

```
primary  primary-contrast  secondary  accent
base  surface  contrast  muted  border  focus
success  warning  danger
```

改顏色的值可以，隨時可以。**改 slug 不行**，除非走下面的程序。

## 為什麼

這些 slug 會被 WordPress 變成 CSS 變數（`--wp--preset--color--primary` 之類），
而站上的外掛會直接讀那些變數來跟著配色走。第一個這樣做的是都更試算外掛
（`Chienfu168/ur-calc`），它的樣式寫成：

```css
--ur-calc-primary: var(--wp--preset--color--primary, #1769AA);
```

這個寫法的好處是外掛不用認識佈景：在 Sydney 這種沒有 theme.json 的傳統佈景上，
變數不存在就用後面的預設值；換成 FuTheme 就自動跟著當下選的配色走，十套都不用設定。

🚩 **代價是改 slug 會讓外掛安靜地退回預設色。** 不會報錯、不會壞版、
不會有任何訊息——只是某一天有人發現試算區塊的顏色跟網站不一樣了，
然後沒有人知道為什麼。安靜地壞掉比直接壞掉難發現得多。

## 已知問題：`border` 與 WordPress 類別撞名（2026-11-20）

`border` 產生的文字色類別 `has-border-color` 剛好和 WordPress「這個區塊有邊框色」的通用類別同名，
所有設了邊框色的區塊文字都會變成 border 色。佈景以 CSS 修正（見 [設計系統](design-system.md)），**不改 slug**：
改名會讓讀 `--wp--preset--color--border` 的外掛退回預設色，代價比 CSS 修正大。

## 要改 slug 的話

1. 先查有哪些外掛在讀這些變數（目前已知：ur-calc）
2. 同一批改掉它們的 fallback 寫法
3. 在這裡記下改了什麼、什麼時候改的

## 已知的消費者

| 誰 | 用到哪幾個 | 備註 |
| --- | --- | --- |
| ur-calc（都更試算外掛） | primary、surface、contrast、border | 有 fallback，缺變數時用自己的預設值 |

新增消費者請加到這張表。這張表就是「改 slug 前要通知誰」的名單。
