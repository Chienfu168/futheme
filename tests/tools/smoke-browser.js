// 瀏覽器由 FUTHEME_BROWSER 指定：chromium（預設）、firefox、webkit
const playwright = require('playwright');
const engine = playwright[process.env.FUTHEME_BROWSER || 'chromium'];
// 瀏覽器冒煙測試：360/1280px 截圖、橫向溢出、前兩個 Tab 焦點樣式。
// 用法：node tests/tools/smoke-browser.js <輸出目錄>（測試站需在 localhost:8888）
(async () => {
  const b = await engine.launch();
  const out = process.argv[2];
  const errs = [];
  for (const w of [360, 1280]) {
    const p = await b.newPage({ viewport: { width: w, height: 800 } });
    // 404 測試頁與不存在的分頁本身回 404 是預期的：只略過「主文件」的 404（以導覽回應判斷，WebKit 的 console 事件早於網址更新），
    // 子資源（CSS、圖片）404 仍算錯誤。另略過 WebKit 在 http 測試站拒絕 Speculation Rules 預先擷取的訊息（正式站為 https，不會發生）。
    const nav404 = new Set();
    p.on('response', r => { if (r.request().isNavigationRequest() && r.status() === 404) nav404.add(r.url()); });
    p.on('console', m => {
      if (m.type() !== 'error') return;
      const t = m.text();
      if (t.includes('status of 404') && nav404.has(m.location().url)) return;
      if (t.startsWith('Prefetch request denied: URL must be secure')) return;
      errs.push(`${w} ${t} ${m.location().url}`);
    });
    p.on('pageerror', e => errs.push(`${w} ${e.message}`));
    for (const [n, u] of [['home', '/'], ['single', '/?p=1'], ['search', '/?s=zzzz'], ['404', '/nope/'], ['archive', '/category/uncategorized/page/2/']]) {
      await p.goto('http://localhost:8888' + u);
      const sw = await p.evaluate(() => document.documentElement.scrollWidth);
      if (sw > w) errs.push(`${w} ${n} horizontal overflow ${sw}`);
      await p.screenshot({ path: `${out}/${n}-${w}.png`, fullPage: true });
    }
    // keyboard: first Tab should reach skip link
    await p.goto('http://localhost:8888/');
    await p.keyboard.press('Tab');
    const f = await p.evaluate(() => { const a = document.activeElement; const s = getComputedStyle(a); return [a.className, a.textContent.trim(), s.outlineStyle, s.outlineWidth, s.outlineColor]; });
    console.log(w, 'first tab:', JSON.stringify(f));
    await p.keyboard.press('Tab');
    const f2 = await p.evaluate(() => { const a = document.activeElement; const s = getComputedStyle(a); return [a.tagName, a.textContent.trim().slice(0,20), s.outlineStyle, s.outlineWidth, s.outlineColor, s.outlineOffset, s.boxShadow]; });
    console.log(w, 'second tab:', JSON.stringify(f2));
    await p.screenshot({ path: `${out}/focus-${w}.png` });
  }
  console.log('errors:', errs.length ? errs : 'none');
  await b.close();
})();
