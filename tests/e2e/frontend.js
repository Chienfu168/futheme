// FuTheme M1 前台檢查：版型狀態碼、配色變數、對比、焦點、RWD、主控台錯誤。
// 用法：node tests/e2e/frontend.js <輸出目錄> [palette-slug]
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const BASE = process.env.FUTHEME_BASE || 'http://localhost:8890';
const out = process.argv[2];
const slug = process.argv[3] || 'urban-blue';
const tokens = JSON.parse(fs.readFileSync(path.join(__dirname, '../../design/tokens.json'))).palettes.find(p => p.slug === slug).colors;
const PAGES = { front: '/', page: '/about/', 'page-banner-image': '/services/', 'single-white-image': '/test-post-1/', 'single-no-image': '/test-post-3/', archive: '/category/%E6%B4%BB%E5%8B%95%E6%B6%88%E6%81%AF/', search: '/?s=%E6%B8%AC%E8%A9%A6', 'search-empty': '/?s=zzzz', 404: '/nope-404/', form: '/contact/' };
const VIEWPORTS = { desktop: { width: 1280, height: 900 }, mobile: { width: 390, height: 844 } };

// 依 WCAG 計算實際渲染的文字/背景對比（往上找第一個不透明背景）。
const contrastProbe = () => {
  const parse = c => { const m = c.match(/rgba?\(([^)]+)\)/); if (!m) return null; const v = m[1].split(',').map(Number); return { r: v[0], g: v[1], b: v[2], a: v.length > 3 ? v[3] : 1 }; };
  const lum = ({ r, g, b }) => [r, g, b].map(v => { v /= 255; return v <= 0.04045 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4; }).reduce((s, v, i) => s + v * [0.2126, 0.7152, 0.0722][i], 0);
  const bgOf = el => { if (el.closest('.fu-header--overlay') && document.querySelector('.fu-hero')) return 'cover'; for (let e = el; e; e = e.parentElement) { const s = getComputedStyle(e); const c = parse(s.backgroundColor); if (c && c.a > 0.99) return c; if (e.classList && e.classList.contains('wp-block-cover')) return 'cover'; } return { r: 255, g: 255, b: 255, a: 1 }; };
  const results = [];
  const sel = 'main p, main a, main h1, main h2, main h3, main time, main li, main label, main button, header a, header .wp-block-site-title, footer a, footer p, .wp-block-post-date';
  for (const el of document.querySelectorAll(sel)) {
    if (!el.textContent.trim() || !el.offsetParent) continue;
    const s = getComputedStyle(el); const fg = parse(s.color); const bg = bgOf(el);
    if (bg === 'cover' || !fg) continue; // 橫幅內文字另以截圖與遮罩計算驗證
    const [a, b] = [lum(fg), lum(bg)].sort((x, y) => y - x);
    const ratio = (a + 0.05) / (b + 0.05);
    const large = parseFloat(s.fontSize) >= 24 || (parseFloat(s.fontSize) >= 18.66 && +s.fontWeight >= 700);
    results.push({ text: el.textContent.trim().slice(0, 30), tag: el.tagName, ratio: +ratio.toFixed(2), min: el.disabled ? 0 : (large ? 3 : 4.5) });
  }
  return results;
};

(async () => {
  fs.mkdirSync(out, { recursive: true });
  const browser = await chromium.launch();
  const report = { palette: slug, pages: {}, failures: [] };
  for (const [vp, size] of Object.entries(VIEWPORTS)) {
    const page = await browser.newPage({ viewport: size });
    let errors = [];
    page.on('pageerror', e => errors.push(e.message));
    page.on('console', m => { if (m.type() === 'error') errors.push(m.text()); });
    for (const [name, url] of Object.entries(PAGES)) {
      errors = [];
      const res = await page.goto(BASE + url, { waitUntil: 'load' });
      const status = res.status();
      const info = await page.evaluate(() => {
        const css = getComputedStyle(document.body);
        const hero = document.querySelector('.fu-hero, .fu-banner');
        const header = document.querySelector('.fu-header');
        return {
          primary: css.getPropertyValue('--wp--preset--color--primary').trim().toUpperCase(),
          accent: css.getPropertyValue('--wp--preset--color--accent').trim().toUpperCase(),
          overflowX: document.documentElement.scrollWidth > window.innerWidth + 1,
          skipLink: !!document.querySelector('#wp-skip-link'),
          main: !!document.querySelector('main'),
          heroBg: hero ? getComputedStyle(hero).backgroundColor : null,
          heroHasImage: hero ? !!hero.querySelector('.wp-block-cover__image-background') : null,
          heroOverlayOpacity: hero && hero.querySelector('.wp-block-cover__background') ? getComputedStyle(hero.querySelector('.wp-block-cover__background')).opacity : null,
          headerBg: header ? getComputedStyle(header).backgroundColor : null,
          headerPos: header ? getComputedStyle(header.parentElement.classList.contains('wp-site-blocks') ? header : header.parentElement).position : null,
          h1: document.querySelectorAll('h1').length,
        };
      });
      const contrast = await page.evaluate(contrastProbe);
      const lowContrast = contrast.filter(c => c.ratio < c.min);
      // 鍵盤：Tab 第一下應到跳至內容，再數下應有可見焦點環。
      await page.keyboard.press('Tab');
      const firstFocus = await page.evaluate(() => document.activeElement && document.activeElement.id);
      await page.keyboard.press('Tab'); await page.keyboard.press('Tab');
      const focusStyle = await page.evaluate(() => { const s = getComputedStyle(document.activeElement); return { tag: document.activeElement.tagName, outline: s.outlineStyle + ' ' + s.outlineWidth, color: s.outlineColor }; });
      await page.screenshot({ path: path.join(out, `${slug}-${name}-${vp}.png`), fullPage: true });
      const r = { status, ...info, lowContrast, checkedText: contrast.length, firstFocus, focusStyle };
      report.pages[`${name}/${vp}`] = r;
      const expectStatus = name === '404' ? 404 : 200;
      const fail = m => report.failures.push(`${name}/${vp}: ${m}`);
      if (status !== expectStatus) fail(`HTTP ${status}`);
      if (info.primary !== tokens.primary) fail(`primary ${info.primary} != ${tokens.primary}`);
      if (info.accent !== tokens.accent) fail(`accent ${info.accent} != ${tokens.accent}`);
      if (info.overflowX) fail('水平捲動');
      if (!info.skipLink || !info.main) fail('缺跳至內容或 main');
      if (info.h1 !== 1) fail(`h1 數量 ${info.h1}`);
      if (lowContrast.length) fail('對比不足 ' + JSON.stringify(lowContrast.slice(0, 3)));
      if (firstFocus !== 'wp-skip-link') fail(`第一個 Tab 焦點為 ${firstFocus}`);
      if (!/solid 2px/.test(focusStyle.outline)) fail(`焦點環 ${JSON.stringify(focusStyle)}`);
      // 404 頁本身回 404，瀏覽器會記一筆資源錯誤，屬預期。
      const unexpected = errors.filter(e => !(name === '404' && /status of 404/.test(e)));
      if (unexpected.length) fail('console ' + unexpected.slice(0, 3).join(' | '));
    }
    await page.close();
  }
  await browser.close();
  fs.writeFileSync(path.join(out, `${slug}-report.json`), JSON.stringify(report, null, 2));
  console.log(`${slug}: ${report.failures.length ? 'FAIL\n  ' + report.failures.join('\n  ') : 'PASS'}`);
  process.exitCode = report.failures.length ? 1 : 0;
})();
