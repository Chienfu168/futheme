const { chromium } = require('playwright');
// 網站編輯器實測：登入 → 外觀 → 編輯器 → 樣式 → 瀏覽樣式 → 選「專業石墨灰」→ 儲存。
// 用法：node tests/tools/editor-switch.js <admin 密碼> <截圖目錄>（測試站 localhost:8888；伺服器請用 PHP_CLI_SERVER_WORKERS=4）
// 密碼只從參數傳入，不寫入檔案。
(async () => {
  const [pass, out] = process.argv.slice(2);
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
  await p.goto('http://localhost:8888/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', pass);
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);
  await p.goto('http://localhost:8888/wp-admin/site-editor.php?p=%2Fstyles', { waitUntil: 'domcontentloaded', timeout: 90000 });
  await p.waitForSelector('.edit-site-layout, .edit-site', { timeout: 90000 }); await p.waitForTimeout(8000);
  await p.screenshot({ path: `${out}/editor-1.png` });
  await p.getByRole('button', { name: '瀏覽樣式' }).click();
  await p.waitForTimeout(3000);
  const opts = await p.$$eval('[role="option"], .edit-site-global-styles-variations_item, [aria-label]', els => [...new Set(els.map(e => e.getAttribute('aria-label')).filter(Boolean))]);
  console.log('options:', JSON.stringify(opts.filter(o => /藍|綠|橘|紅|紫|灰|金|預設|Default/.test(o))));
  await p.screenshot({ path: `${out}/editor-2.png` });
  await p.getByRole('option', { name: '專業石墨灰' }).first().click().catch(async () => { await p.getByLabel('專業石墨灰').first().click(); });
  await p.waitForTimeout(2000);
  await p.screenshot({ path: `${out}/editor-3.png` });
  await p.getByRole('button', { name: /檢視.*變更/ }).first().click();
  await p.waitForTimeout(2000);
  await p.screenshot({ path: `${out}/editor-3b.png` });
  await p.getByRole('button', { name: /^儲存$/ }).last().click();
  await p.waitForTimeout(4000);
  await p.screenshot({ path: `${out}/editor-4.png` });
  console.log('saved state:', await p.locator('button:has-text("已儲存")').count());
  await b.close();
})();
