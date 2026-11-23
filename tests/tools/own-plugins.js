// 自家外掛融合度實測：都更試算（ur-calc）與 UR AI Assistant 在 FuTheme 下的外觀、配色跟隨、功能與錯誤。
// 用法：node tests/tools/own-plugins.js <fixture 網址路徑> [截圖目錄]
// 測試站需在 localhost:8888，fixture 內容見 tests/fixtures/own-plugins.html。
// 測試站必須沒有 OpenAI API Key，且裝 futheme-test-block-ai.php（擋 AI／LINE 對外請求），不會產生費用。
const playwright = require('playwright');
const engine = playwright[process.env.FUTHEME_BROWSER || 'chromium'];
const [fixture, out] = process.argv.slice(2);
const BASE = 'http://localhost:8888';
const palette = process.env.FUTHEME_PALETTE || 'default';

const parse = (c) => c.match(/[\d.]+/g).map(Number);
const lum = ([r, g, b]) => [r, g, b].map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4; })
  .reduce((s, v, i) => s + v * [0.2126, 0.7152, 0.0722][i], 0);
const ratio = (a, b) => { const [x, y] = [lum(parse(a)), lum(parse(b))].sort((p, q) => q - p); return (x + 0.05) / (y + 0.05); };

(async () => {
  const b = await engine.launch();
  const results = []; const fails = []; const notes = [];
  const check = (name, ok, detail) => { results.push(`${ok ? 'PASS' : 'FAIL'} [${palette}] ${name}${detail ? ' — ' + detail : ''}`); if (!ok) fails.push(name); };
  for (const w of [360, 1280]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    const errs = []; const external = [];
    p.on('pageerror', e => errs.push(e.message));
    p.on('console', m => m.type() === 'error' && !m.text().startsWith('Prefetch request denied') && errs.push(m.text()));
    p.on('request', r => { const h = new URL(r.url()).host; if (/openai|line\.me|anthropic|googleapis\.com\/v1/.test(h)) external.push(h); });
    await p.goto(BASE + fixture, { waitUntil: 'networkidle' });
    const sw = await p.evaluate(() => document.documentElement.scrollWidth);
    check(`${w} no horizontal overflow`, sw <= w, `${sw}`);
    if (w === 1280) {
      const tok = await p.evaluate(() => {
        const t = {}; const probe = document.createElement('i'); document.body.append(probe);
        for (const k of ['primary', 'primary-contrast', 'contrast', 'base', 'surface', 'border']) { probe.style.color = `var(--wp--preset--color--${k})`; t[k] = getComputedStyle(probe).color; }
        probe.hidden = true; return t;
      });
      // ── 都更試算：讀佈景配色變數
      const calc = await p.evaluate(() => {
        const root = document.querySelector('.ur-calc'); const cs = getComputedStyle(root);
        const probe = document.createElement('i'); root.append(probe);
        const resolve = (v) => { probe.style.color = `var(${v})`; return getComputedStyle(probe).color; };
        const r = { primary: resolve('--ur-calc-primary'), onPrimary: resolve('--ur-calc-on-primary'), text: resolve('--ur-calc-text'), border: resolve('--ur-calc-border') };
        probe.hidden = true;
        const accent = document.querySelector('.ur-calc__btn--accent'); const ac = getComputedStyle(accent);
        r.accentBg = ac.backgroundColor; r.accentColor = ac.color;
        const hero = document.querySelector('.ur-calc__hero'); r.heroBg = getComputedStyle(hero).backgroundImage.slice(0, 60);
        return r;
      });
      check('ur-calc primary follows theme', calc.primary === tok.primary, `${calc.primary} vs ${tok.primary}`);
      check('ur-calc text/border follow theme', calc.text === tok.contrast && calc.border === tok.border, `${calc.text} ${calc.border}`);
      check('ur-calc on-primary contrast ≥4.5', ratio(calc.primary, calc.onPrimary) >= 4.5, ratio(calc.primary, calc.onPrimary).toFixed(2));
      notes.push(`[${palette}] ur-calc hero background: ${calc.heroBg}`);
      // 功能：輸入基地 100 坪、法定容積率 225% → 結果應出現 225
      await p.fill('#ur-calc-1-f1', '100'); await p.fill('#ur-calc-1-f3', '225'); await p.press('#ur-calc-1-f3', 'Tab');
      await p.waitForTimeout(400);
      const total = await p.evaluate(() => (document.querySelector('.ur-calc__total') || {}).textContent || '');
      check('ur-calc computes inside theme (100坪 × 225% = 225)', /225/.test(total), total.trim().slice(0, 40));
      const fieldH = await p.$eval('#ur-calc-1-f1', e => e.getBoundingClientRect().height);
      notes.push(`[${palette}] ur-calc input height ${fieldH}px`);
      // ── UR AI Assistant：外觀是否跟隨配色（記錄，不判失敗）
      const ai = await p.evaluate(() => {
        // 送出鈕是漸層底：取漸層第一個顏色當底色計算對比
        const s = document.querySelector('.ur-ai-submit'); const c = getComputedStyle(s);
        const first = (c.backgroundImage.match(/rgba?\([^)]+\)/) || [c.backgroundColor])[0];
        const hero = document.querySelector('.ur-ai-section'); return { submitBg: first, submitColor: c.color, submitH: s.getBoundingClientRect().height, heroBg: getComputedStyle(hero).backgroundImage.slice(0, 60) };
      });
      notes.push(`[${palette}] UR AI submit bg ${ai.submitBg} (theme primary ${tok.primary}) — ${ai.submitBg === tok.primary ? 'follows' : 'fixed colour'}`);
      check('UR AI submit text contrast ≥4.5', ratio(ai.submitBg, ai.submitColor) >= 4.5, ratio(ai.submitBg, ai.submitColor).toFixed(2));
      // 功能：送出問題 → 沒有 API Key 時外掛的 AJAX 刻意回 HTTP 500＋說明；畫面應顯示錯誤訊息，不會連到外部 AI。
      // 這個 500 是外掛自己的設計（Twenty Twenty-Five 下相同），不算佈景錯誤，從 JS 錯誤清單排除。
      await p.fill('.ur-ai-question-input', '什麼是自主更新？');
      await p.click('.ur-ai-submit');
      await p.waitForFunction(() => { const r = document.querySelector('.ur-ai-error, .ur-ai-result'); return r && r.offsetParent && r.textContent.trim().length > 0; }, null, { timeout: 20000 }).catch(() => {});
      const reply = await p.evaluate(() => { const r = [...document.querySelectorAll('.ur-ai-error, .ur-ai-result')].find(e => e.offsetParent && e.textContent.trim()); return r ? r.textContent : ''; });
      check('UR AI shows a message without API key', reply.trim().length > 0, reply.trim().replace(/\s+/g, ' ').slice(0, 60));
      for (let i = errs.length - 1; i >= 0; i--) if (errs[i].includes('status of 500')) errs.splice(i, 1);
      check('no request to external AI services', external.length === 0, external.join(','));
      if (out) await p.screenshot({ path: `${out}/own-${palette}-1280.png`, fullPage: true });
    } else if (out) {
      await p.screenshot({ path: `${out}/own-${palette}-360.png`, fullPage: true });
    }
    check(`${w} no JS errors`, errs.length === 0, errs.join('; '));
    await p.close();
  }
  console.log(results.join('\n'));
  console.log(notes.join('\n'));
  console.log(fails.length ? `FAILED: ${fails.length}` : 'ALL PASS');
  await b.close();
  process.exit(fails.length ? 1 : 0);
})();
