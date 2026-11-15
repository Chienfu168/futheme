// 用法：node tests/tools/editor-validity.js <admin 密碼>（測試站 localhost:8888，伺服器用 PHP_CLI_SERVER_WORKERS=4）
// 在網站編輯器逐一打開模板與頁首頁尾，檢查是否有無效區塊
const { chromium } = require('playwright');
(async () => {
  const [pass] = process.argv.slice(2);
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
  await p.goto('http://localhost:8888/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', pass);
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);
  const items = ['wp_template/futheme//front-page', 'wp_template/futheme//single', 'wp_template/futheme//page', 'wp_template/futheme//archive', 'wp_template/futheme//search', 'wp_template/futheme//home', 'wp_template/futheme//index', 'wp_template/futheme//404', 'wp_template_part/futheme//header', 'wp_template_part/futheme//footer'];
  for (const it of items) {
    await p.goto('http://localhost:8888/wp-admin/site-editor.php?p=' + encodeURIComponent('/' + it) + '&canvas=edit', { waitUntil: 'domcontentloaded' });
    await p.waitForFunction(() => window.wp && wp.data && wp.data.select('core/block-editor').getBlocks().length > 0, null, { timeout: 90000 });
    await p.waitForTimeout(2500);
    const r = await p.evaluate(() => {
      const bad = []; let n = 0;
      const walk = (bs) => bs.forEach(x => { n++; if (!x.isValid) bad.push(x.name); walk(x.innerBlocks); });
      walk(wp.data.select('core/block-editor').getBlocks());
      return { n, bad };
    });
    console.log(it.split('//')[1].padEnd(12), 'blocks', r.n, r.bad.length ? 'INVALID ' + r.bad : 'all valid');
  }
  await b.close();
})();
