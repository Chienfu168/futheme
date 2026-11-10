// 在網站編輯器逐一開啟版型與 template part，檢查區塊是否有效（無「內容無效」）與主控台錯誤。
// 用法：node tests/e2e/editor.js <輸出目錄>
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const BASE = process.env.FUTHEME_BASE || 'http://localhost:8890';
const out = process.argv[2];
const pw = fs.readFileSync(path.join(__dirname, '../../.wp-test/admin-password'), 'utf8').trim();
const ITEMS = ['wp_template/futheme-core//front-page', 'wp_template/futheme-core//index', 'wp_template/futheme-core//home', 'wp_template/futheme-core//page', 'wp_template/futheme-core//page-no-banner', 'wp_template/futheme-core//single', 'wp_template/futheme-core//archive', 'wp_template/futheme-core//search', 'wp_template/futheme-core//404', 'wp_template_part/futheme-core//header', 'wp_template_part/futheme-core//header-overlay', 'wp_template_part/futheme-core//footer'];
(async () => {
  fs.mkdirSync(out, { recursive: true });
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
  await page.goto(BASE + '/wp-login.php');
  await page.fill('#user_login', 'admin');
  await page.fill('#user_pass', pw);
  await page.click('#wp-submit');
  await page.waitForURL(/wp-admin/);
  const failures = [];
  for (const item of ITEMS) {
    const logs = [];
    // 測試環境網路不開放 s.w.org（表情符號）與 gravatar（頭像），核心向外載入失敗屬環境限制，另計不判失敗。
    let external = 0;
    const onConsole = m => { const t = m.text(); if (/ERR_TUNNEL_CONNECTION_FAILED/.test(t)) { external++; return; } if (m.type() === 'error' || /Block validation|invalid/i.test(t)) logs.push(t.slice(0, 200)); };
    page.on('console', onConsole);
    const [type, id] = item.split('/futheme-core//');
    await page.goto(`${BASE}/wp-admin/site-editor.php?p=/${type}/futheme-core//${id}&canvas=edit`);
    await page.waitForFunction(() => window.wp && wp.data && wp.data.select('core/block-editor').getBlocks().length > 0, null, { timeout: 60000 });
    await page.waitForTimeout(2500);
    const result = await page.evaluate(() => {
      const be = wp.data.select('core/block-editor');
      const invalid = []; let count = 0;
      const walk = ids => ids.forEach(cid => { const b = be.getBlock(cid); if (!b) return; count++; if (b.isValid === false) invalid.push(b.name); walk(be.getBlockOrder(cid)); });
      walk(be.getBlockOrder());
      return { count, invalid, warnings: document.querySelectorAll('.block-editor-warning').length };
    });
    page.off('console', onConsole);
    const name = id;
    await page.screenshot({ path: path.join(out, `editor-${type}-${name}.png`) });
    const ok = !result.invalid.length && !result.warnings && !logs.length;
    console.log(`${ok ? 'PASS' : 'FAIL'} ${type}/${name}: ${result.count} 個區塊${result.invalid.length ? '，無效：' + result.invalid.join(',') : ''}${result.warnings ? '，警告 ' + result.warnings : ''}${external ? `（外部資源被測試網路擋下 ${external} 筆，不計）` : ''}${logs.length ? '\n   ' + logs.join('\n   ') : ''}`);
    if (!ok) failures.push(name);
  }
  await browser.close();
  process.exitCode = failures.length ? 1 : 0;
})();
