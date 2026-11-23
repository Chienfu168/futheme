// 外掛 × 佈景相容矩陣：在目前啟用的佈景下，量都更試算與 UR AI Assistant 的關鍵配色與實際對比。
// 用來確認外掛在「還沒換成 FuTheme」的網站上也正常（不會被別的佈景的同名配色變數帶歪）。
// 用法：node tests/tools/plugin-theme-matrix.js <fixture 網址路徑> <標籤>
// 輸出一行 JSON：每組文字／底色的對比，低於 4.5 的列在 low。
const playwright = require('playwright');
const engine = playwright[process.env.FUTHEME_BROWSER || 'chromium'];
const [fixture, label] = process.argv.slice(2);

(async () => {
  const b = await engine.launch();
  const p = await b.newPage({ viewport: { width: 1280, height: 900 } });
  const errs = [];
  p.on('pageerror', e => errs.push(e.message));
  await p.goto('http://localhost:8888' + fixture, { waitUntil: 'networkidle' });
  const r = await p.evaluate(() => {
    // 把任何 CSS 顏色（含 color(srgb …)、color-mix 結果）轉成 RGB：畫到 canvas 上讀像素
    const ctx = document.createElement('canvas').getContext('2d');
    const rgb = (c) => { ctx.clearRect(0, 0, 1, 1); ctx.fillStyle = '#000'; ctx.fillStyle = c; ctx.fillRect(0, 0, 1, 1); return [...ctx.getImageData(0, 0, 1, 1).data].slice(0, 3); };
    const lum = (c) => c.map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4; }).reduce((s, v, i) => s + v * [0.2126, 0.7152, 0.0722][i], 0);
    const ratio = (a, b) => { const [x, y] = [lum(rgb(a)), lum(rgb(b))].sort((m, n) => n - m); return +((x + 0.05) / (y + 0.05)).toFixed(2); };
    const firstColor = (cs) => (cs.backgroundImage.match(/(rgba?|color)\([^)]*\)/) || [cs.backgroundColor])[0];
    const pair = (sel, bgFrom) => { const e = document.querySelector(sel); if (!e) return null; const cs = getComputedStyle(e); const bg = bgFrom ? getComputedStyle(document.querySelector(bgFrom)) : cs; const bgc = firstColor(bg); return { fg: cs.color, bg: bgc, ratio: ratio(cs.color, bgc) }; };
    return {
      theme: document.body.className.match(/wp-theme-[a-z0-9-]+/g),
      'calc 下載按鈕(primary)': pair('.ur-calc__btn--primary'),
      'calc 新增按鈕(soft)': pair('.ur-calc__btn--soft'),
      'calc 教學示例(accent)': pair('.ur-calc__btn--accent'),
      'calc 標題(hero)': pair('.ur-calc__hero h2, .ur-calc__hero h3, .ur-calc__title', '.ur-calc__hero'),
      'calc 結果框': pair('.ur-calc__total-label', '.ur-calc__total-box'),
      'AI 送出鈕': pair('.ur-ai-submit'),
    };
  });
  const low = Object.entries(r).filter(([k, v]) => v && v.ratio !== undefined && v.ratio < 4.5).map(([k, v]) => `${k} ${v.ratio}`);
  console.log(JSON.stringify({ label, theme: r.theme, low, jsErrors: errs.length,
    detail: Object.fromEntries(Object.entries(r).filter(([k]) => k !== 'theme').map(([k, v]) => [k, v && `${v.ratio} (${v.fg} on ${v.bg})`])) }));
  await b.close();
})();
