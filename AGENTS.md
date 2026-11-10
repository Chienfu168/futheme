# FuTheme 開發規範

- 先讀 README 與 docs/status.md，依 docs/work-items.md 執行當前里程碑。
- M0 只有規格與骨架；使用者確認規格後才實作 M1，不把空殼打包為可安裝成品。
- 不修改 WordPress 核心、不在正式站測試、不儲存密碼、個資、API key 或正式資料庫。
- Core 只負責呈現；AI、RSS、會員、排程與商業邏輯留在獨立外掛。
- 優先使用原生區塊、theme.json、原生 CSS；不用大型前台框架與外部字型。
- 公開 Token slug 與 fu- 元件類別變更需記錄相容性與版本；禁止硬寫品牌色，備援值除外。
- 修改配色後執行 python3 scripts/validate_spec.py；實作期加入與變更相稱的 WordPress 驗證，不以靜態檢查代替實測。
- 更新狀態須分開記錄已完成、待驗證與阻礙。沒有證據不能標示通過。
- 不新增功能以繞過效能預算；新依賴須說明必要性與資源影響。
