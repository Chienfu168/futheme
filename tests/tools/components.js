// 元件狀態實測：按鈕、外框按鈕、表單（一般／錯誤／停用）、服務卡片、聯絡區塊、留言表單。
// 讀前台 computed style 比對配色 Token，並計算實際對比；鍵盤 Tab 檢查焦點框。
// 用法：node tests/tools/components.js <fixture 頁網址路徑> <單篇網址路徑> [截圖目錄]
// 測試站需在 localhost:8888，fixture 內容見 tests/fixtures/components.html。
// 瀏覽器由 FUTHEME_BROWSER 指定：chromium（預設）、firefox、webkit
const playwright = require('playwright');
const engine = playwright[process.env.FUTHEME_BROWSER || 'chromium'];
const [fixture, single, out] = process.argv.slice(2);
const BASE = 'http://localhost:8888';

const parse = (c) => { const m = c.match(/[\d.]+/g).map(Number); return m.slice(0, 3).concat(m.length > 3 ? m[3] : 1); };
const lum = ([r, g, b]) => [r, g, b].map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4; })
  .reduce((s, v, i) => s + v * [0.2126, 0.7152, 0.0722][i], 0);
const ratio = (a, b) => { const [x, y] = [lum(parse(a)), lum(parse(b))].sort((p, q) => q - p); return (x + 0.05) / (y + 0.05); };

(async () => {
  const b = await engine.launch();
  const fails = []; const results = [];
  const check = (name, ok, detail) => { results.push(`${ok ? 'PASS' : 'FAIL'} ${name}${detail ? ' — ' + detail : ''}`); if (!ok) fails.push(name); };
  for (const w of [360, 1280]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    const errs = []; p.on('pageerror', e => errs.push(e.message)); // WebKit 在 http 測試站拒絕 Speculation Rules 預先擷取，正式站為 https 不會發生，略過
    p.on('console', m => m.type() === 'error' && !m.text().startsWith('Prefetch request denied: URL must be secure') && errs.push(m.text()));
    await p.goto(BASE + fixture);
    // 解析每個 Token 的實際顏色：用一個暫存元素讀 computed color
    const tok = await p.evaluate(() => {
      const t = {}; const probe = document.createElement('i'); document.body.append(probe);
      for (const k of ['primary', 'primary-contrast', 'base', 'surface', 'contrast', 'muted', 'border', 'danger', 'focus']) {
        probe.style.color = `var(--wp--preset--color--${k})`; t[k] = getComputedStyle(probe).color;
      }
      probe.hidden = true; return t;
    });
    const cs = (sel, pseudo) => p.evaluate(([s, ps]) => { const e = document.querySelector(s); if (!e) return null; const c = getComputedStyle(e, ps || null); const r = e.getBoundingClientRect(); return { bg: c.backgroundColor, color: c.color, bc: c.borderTopColor, bw: c.borderTopWidth, td: c.textDecorationLine, h: r.height, ow: c.outlineWidth, os: c.outlineStyle, oc: c.outlineColor, shadow: c.boxShadow, content: c.content, cursor: c.cursor }; }, [sel, pseudo]);
    const sw = await p.evaluate(() => document.documentElement.scrollWidth);
    check(`${w} no horizontal overflow`, sw <= w, `scrollWidth ${sw}`);
    if (w === 1280) {
      // 按鈕
      let s = await cs('.fx-btn-default a');
      check('button colours primary/primary-contrast', s.bg === tok.primary && s.color === tok['primary-contrast'], `${s.bg} / ${s.color}`);
      check('button contrast ≥4.5', ratio(s.bg, s.color) >= 4.5, ratio(s.bg, s.color).toFixed(2));
      check('button height ≥44px', s.h >= 44, `${s.h}px`);
      await p.hover('.fx-btn-default a'); s = await cs('.fx-btn-default a');
      check('button hover keeps colour + underline', s.bg === tok.primary && s.td.includes('underline'), `${s.bg} ${s.td}`);
      s = await cs('.fx-btn-outline a');
      check('outline button transparent + primary text/border', parse(s.bg)[3] === 0 && s.color === tok.primary && s.bc === tok.primary && s.bw === '2px', `${s.bg} ${s.color} ${s.bc} ${s.bw}`);
      check('outline button contrast on base ≥4.5', ratio(tok.base, s.color) >= 4.5, ratio(tok.base, s.color).toFixed(2));
      // 表單
      s = await cs('#fx-name');
      check('input base/contrast/border, ≥44px', s.bg === tok.base && s.color === tok.contrast && s.bc === tok.border && s.h >= 44, `${s.bg} ${s.color} ${s.bc} ${s.h}px`);
      check('input border vs base ≥3:1', ratio(s.bc, tok.base) >= 3, ratio(s.bc, tok.base).toFixed(2));
      for (const sel of ['#fx-topic', '#fx-msg']) { const t = await cs(sel); check(`${sel} styled like input`, t.bc === tok.border && t.h >= 44, `${t.bc} ${t.h}px`); }
      s = await cs('#fx-email');
      check('invalid input danger border', s.bc === tok.danger && s.shadow.includes('inset'), `${s.bc} ${s.shadow}`);
      s = await cs('#fx-email-error');
      check('error text danger, contrast ≥4.5', s.color === tok.danger && ratio(s.color, tok.base) >= 4.5, `${s.color} ${ratio(s.color, tok.base).toFixed(2)}`);
      s = await cs('#fx-email-error', '::before');
      check('error icon present', s.content === '"!"', s.content);
      check('error described by', await p.$eval('#fx-email', e => e.getAttribute('aria-describedby') === 'fx-email-error'));
      for (const sel of ['#fx-disabled', '.fx-submit-disabled']) {
        s = await cs(sel);
        check(`${sel} disabled surface/muted, ≥4.5`, s.bg === tok.surface && s.color === tok.muted && ratio(s.bg, s.color) >= 4.5 && s.cursor === 'not-allowed', `${s.bg} ${s.color} ${ratio(s.bg, s.color).toFixed(2)}`);
      }
      s = await cs('.fx-search .wp-block-search__input');
      check('search input styled', s.bc === tok.border && s.h >= 44, `${s.bc} ${s.h}px`);
      // 服務卡片
      s = await cs('.fu-services .fu-card');
      check('service card surface + border', s.bg === tok.surface && s.bc === tok.border && s.bw === '1px', `${s.bg} ${s.bc} ${s.bw}`);
      const tx = await cs('.fu-services .fu-card p');
      check('service card text is contrast (slug "border" class clash)', tx.color === tok.contrast && ratio(tx.color, tok.surface) >= 4.5, `${tx.color} ${ratio(tx.color, tok.surface).toFixed(2)}`);
      const rows = await p.$$eval('.fu-services .fu-card', es => new Set(es.map(e => Math.round(e.getBoundingClientRect().top))).size);
      check('service cards in one row at 1280', rows === 1, `${rows} rows`);
      // 聯絡區塊
      s = await cs('.fu-contact');
      check('contact primary/primary-contrast', s.bg === tok.primary && s.color === tok['primary-contrast'], `${s.bg} ${s.color}`);
      const h = await cs('.fu-contact h2');
      check('contact heading primary-contrast', h.color === tok['primary-contrast'], h.color);
      s = await cs('.fu-contact .wp-block-button__link');
      check('contact button contrast ≥4.5', ratio(s.bg, s.color) >= 4.5, ratio(s.bg, s.color).toFixed(2));
      await p.focus('.fu-contact .wp-block-button__link'); await p.keyboard.press('Shift+Tab'); await p.keyboard.press('Tab');
      s = await cs('.fu-contact .wp-block-button__link:focus-visible');
      check('contact focus ring inverted', s && s.oc === tok['primary-contrast'] && s.ow === '2px', s ? `${s.oc} ${s.ow}` : 'not focus-visible');
      // 焦點：鍵盤進入表單欄位
      await p.focus('#fx-name'); await p.keyboard.press('Shift+Tab'); await p.keyboard.press('Tab');
      s = await cs('#fx-name:focus-visible');
      check('input focus-visible 2px focus outline', s && s.ow === '2px' && s.os === 'solid' && s.oc === tok.focus, s ? `${s.ow} ${s.os} ${s.oc}` : 'not focus-visible');
      if (out) await p.screenshot({ path: `${out}/components-1280.png`, fullPage: true });
      // 留言表單
      await p.goto(BASE + single);
      const labels = await p.$$eval('.fu-comments .comment-form label', ls => ls.map(l => { const r = l.getBoundingClientRect(); return r.width > 0 && r.height > 0; }));
      check('comment form labels visible', labels.length >= 1 && labels.every(Boolean), `${labels.length} labels`);
      s = await cs('.fu-comments #comment');
      check('comment textarea styled', s && s.bc === tok.border && s.h >= 44, s && `${s.bc} ${s.h}px`);
      s = await cs('.fu-comments .comment-form [type=submit]');
      check('comment submit is themed button', s && s.bg === tok.primary && s.h >= 44, s && `${s.bg} ${s.h}px`);
      check('no avatar images', (await p.$$('.fu-comments img.avatar')).length === 0);
      if (out) await p.screenshot({ path: `${out}/single-comments-1280.png`, fullPage: true });
    } else if (out) {
      await p.screenshot({ path: `${out}/components-360.png`, fullPage: true });
      await p.goto(BASE + single);
      const sw2 = await p.evaluate(() => document.documentElement.scrollWidth);
      check('360 single no overflow', sw2 <= 360, `${sw2}`);
      await p.screenshot({ path: `${out}/single-comments-360.png`, fullPage: true });
    }
    check(`${w} no JS errors`, errs.length === 0, errs.join('; '));
    await p.close();
  }
  console.log(results.join('\n'));
  console.log(fails.length ? `FAILED: ${fails.length}` : 'ALL PASS');
  await b.close();
  process.exit(fails.length ? 1 : 0);
})();
