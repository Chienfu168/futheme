// 深色區塊焦點框截圖：橫幅按鈕與頁尾連結。用法：node tests/tools/focus-dark.js <輸出目錄>
const { chromium } = require('playwright');
(async () => {
  const out = process.argv[2];
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1280, height: 900 }, deviceScaleFactor: 2 });
  await p.goto('http://localhost:8888/');
  const btn = p.locator('.fu-banner .wp-block-button__link'); await btn.focus(); await p.keyboard.press('Shift+Tab'); await p.keyboard.press('Tab');
  console.log('hero focused:', await p.evaluate(() => document.activeElement.textContent.trim()));
  const bb = await btn.boundingBox(); await p.screenshot({ path: `${out}/focus-hero.png`, clip: { x: bb.x - 30, y: bb.y - 20, width: bb.width + 60, height: bb.height + 40 } });
  const link = p.locator('.fu-site-footer a').last(); await link.focus(); await p.keyboard.press('Shift+Tab'); await p.keyboard.press('Tab');
  const lb = await link.boundingBox(); await p.screenshot({ path: `${out}/focus-footer.png`, clip: { x: lb.x - 30, y: lb.y - 20, width: lb.width + 60, height: lb.height + 40 } });
  console.log('footer focused:', await p.evaluate(() => document.activeElement.textContent.trim()));
  await b.close();
})();
