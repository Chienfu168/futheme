// 第三方外掛相容實測：Contact Form 7 表單外觀與驗證錯誤狀態、Yoast SEO 啟用下前台無錯誤。
// 用法：node tests/tools/plugins.js <CF7 頁網址路徑> [截圖目錄]（測試站 localhost:8888，兩個外掛需已啟用）
// 瀏覽器由 FUTHEME_BROWSER 指定：chromium（預設）、firefox、webkit
const playwright = require('playwright');
const engine = playwright[process.env.FUTHEME_BROWSER || 'chromium'];
const [formPage, out] = process.argv.slice(2);
const BASE = 'http://localhost:8888';

const parse = (c) => c.match(/[\d.]+/g).map(Number);
const lum = ([r, g, b]) => [r, g, b].map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4; })
  .reduce((s, v, i) => s + v * [0.2126, 0.7152, 0.0722][i], 0);
const ratio = (a, b) => { const [x, y] = [lum(parse(a)), lum(parse(b))].sort((p, q) => q - p); return (x + 0.05) / (y + 0.05); };

(async () => {
  const b = await engine.launch();
  const p = await b.newPage({ viewport: { width: 1280, height: 900 } });
  const errs = []; p.on('pageerror', e => errs.push(e.message));
  p.on('console', m => m.type() === 'error' && errs.push(m.text()));
  const results = []; const fails = [];
  const check = (name, ok, detail) => { results.push(`${ok ? 'PASS' : 'FAIL'} ${name}${detail ? ' — ' + detail : ''}`); if (!ok) fails.push(name); };
  const cs = (sel) => p.evaluate((s) => { const e = document.querySelector(s); if (!e) return null; const c = getComputedStyle(e); return { bg: c.backgroundColor, color: c.color, bc: c.borderTopColor, h: e.getBoundingClientRect().height, display: c.display }; }, sel);

  // Yoast：前台各頁無 JS 錯誤、輸出 SEO 標記
  for (const u of ['/', '/hello-world/', '/?s=test']) {
    await p.goto(BASE + u);
    if (u === '/') check('Yoast SEO meta present', (await p.$$('script.yoast-schema-graph')).length === 1);
  }
  check('Yoast: no JS errors on front pages', errs.length === 0, errs.join('; '));

  await p.goto(BASE + formPage);
  const tok = await p.evaluate(() => {
    const t = {}; const probe = document.createElement('i'); document.body.append(probe);
    for (const k of ['primary', 'primary-contrast', 'base', 'contrast', 'border', 'danger']) { probe.style.color = `var(--wp--preset--color--${k})`; t[k] = getComputedStyle(probe).color; }
    probe.hidden = true; return t;
  });
  let s = await cs('.wpcf7 input.wpcf7-text');
  check('CF7 text input themed', s.bg === tok.base && s.bc === tok.border && s.h >= 44, `${s.bg} ${s.bc} ${s.h}px`);
  s = await cs('.wpcf7 textarea');
  check('CF7 textarea themed', s.bc === tok.border, s.bc);
  s = await cs('.wpcf7 .wpcf7-submit');
  check('CF7 submit looks like theme button', s.bg === tok.primary && s.color === tok['primary-contrast'] && s.h >= 44, `${s.bg} ${s.color} ${s.h}px`);
  if (out) await p.screenshot({ path: `${out}/cf7-1280.png`, fullPage: true });

  // 空白送出：CF7 以 JS 驗證，標 aria-invalid 並顯示錯誤提示
  await p.click('.wpcf7 .wpcf7-submit');
  await p.waitForSelector('.wpcf7-not-valid-tip', { timeout: 15000 });
  s = await cs('.wpcf7 [aria-invalid="true"]');
  check('CF7 invalid field shows danger border', s && s.bc === tok.danger, s && s.bc);
  s = await cs('.wpcf7-not-valid-tip');
  check('CF7 error tip is danger text, ≥4.5:1', s.color === tok.danger && ratio(s.color, tok.base) >= 4.5, `${s.color} ${ratio(s.color, tok.base).toFixed(2)}`);
  s = await cs('.wpcf7-response-output');
  check('CF7 response message visible', s && s.display !== 'none', s && s.display);
  if (out) await p.screenshot({ path: `${out}/cf7-errors-1280.png`, fullPage: true });

  const sw = await p.evaluate(() => document.documentElement.scrollWidth);
  check('no horizontal overflow', sw <= 1280, `${sw}`);
  check('no JS errors (all pages)', errs.length === 0, errs.join('; '));
  console.log(results.join('\n'));
  console.log(fails.length ? `FAILED: ${fails.length}` : 'ALL PASS');
  await b.close();
  process.exit(fails.length ? 1 : 0);
})();
