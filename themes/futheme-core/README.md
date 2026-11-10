# FuTheme Core

M1 原型（0.1.0-m1）。Block Theme：十套配色（`styles/*.json`）、四種首頁區段樣式（`styles/sections/`）、
9 個版型、3 個 template part、首頁 patterns。只負責呈現，不含 AI、RSS、會員或排程邏輯。

- 配色檔由 `scripts/build_theme_styles.py` 從 `design/tokens.json` 產生，**不要手改** `styles/{slug}.json` 與 theme.json 的 palette。
- 打包：`scripts/package_theme.sh`（此 README 不進 ZIP）。
- 各站自己設定：Logo（建議白色反白版）、首頁橫幅圖、文章／頁面精選圖（作為頁首橫幅）、主選單與頁尾選單。

公開類別（變更須記錄相容性與版本）：`fu-header`、`fu-header--overlay`、`fu-hero`、`fu-banner`、`fu-card`、
`fu-field-error`、`fu-status-success／warning／danger`、`is-style-fu-section-surface／primary／secondary／accent`。
