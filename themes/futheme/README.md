# FuTheme

Block Theme 本體（M1 進行中，未完成，不可用於正式網站）。規格見 ../../docs/file-specification.md。

- `theme.json` 的 `settings.color.palette` 與 `styles/*.json` 由 `scripts/build_styles.py` 從 `design/tokens.json` 產生，**不要手改**；其他欄位手動維護。
- 翻譯：`languages/zh_TW.po` 改完用 `wp i18n make-mo languages` 產生 `.mo`。theme.json 的字串需手動加進 `.po`。
