#!/usr/bin/env python3
"""產生 FuTheme 內建橫幅圖（原創、灰階、無外部素材）。

用法：python3 scripts/make_banners.py [輸出目錄]（預設 themes/futheme/assets/images）
輸出：banner-<主題>-1920.webp、banner-<主題>-960.webp

圖片一律灰階，亮度壓在 LUM_MIN～LUM_MAX，網頁上疊 80% 主色遮罩後跟著配色變色；
亮度範圍固定，才能保證十套配色下橫幅文字對比（見 tests/reports/2026-11-20-banners.md）。
固定亂數種子，同一份程式得到同一組圖。
"""
import math
import random
import sys
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter

W, H = 1920, 800
LUM_MIN, LUM_MAX = 50, 165
OUT = Path(sys.argv[1]) if len(sys.argv) > 1 else Path(__file__).resolve().parents[1] / "themes/futheme/assets/images"


def sky(top, bottom):
    img = Image.new("L", (W, H))
    d = ImageDraw.Draw(img)
    for y in range(H):
        d.line([(0, y), (W, y)], fill=int(top + (bottom - top) * y / H))
    return img


def glow(img, bands, level=175, blur=60):
    mask = Image.new("L", (W, H), 0)
    g = ImageDraw.Draw(mask)
    for x0, w in bands:
        g.polygon([(x0, 0), (x0 + w, 0), (x0 + w - 500, H), (x0 - 500, H)], fill=26)
    return Image.composite(Image.new("L", (W, H), level), img, mask.filter(ImageFilter.GaussianBlur(blur)))


def city(r):
    """城市建築：都更、不動產、建設（與 0.3.0 的預設圖相同畫法）。"""
    img = glow(sky(150, 100), [(300, 260), (900, 180), (1450, 320)])
    d = ImageDraw.Draw(img)

    def skyline(hmin, hmax, shade, win):
        x = -20
        while x < W:
            w = r.randint(70, 170)
            h = r.randint(hmin, hmax)
            d.rectangle([x, H - h, x + w, H], fill=shade)
            if r.random() < .3:
                d.rectangle([x + w // 4, H - h - 40, x + 3 * w // 4, H - h], fill=shade)
            if win:
                for wy in range(H - h + 24, H - 10, 34):
                    for wx in range(x + 14, x + w - 18, 26):
                        if r.random() < .55:
                            d.rectangle([wx, wy, wx + 10, wy + 16], fill=shade + win)
            x += w + r.randint(4, 24)
    skyline(260, 520, 112, 0)
    skyline(140, 380, 84, 18)
    skyline(60, 220, 58, 14)
    return img


def nature(r):
    """自然綠意：教育、環保、農業。層疊山丘、樹林與太陽。"""
    img = sky(155, 120)
    d = ImageDraw.Draw(img)
    d.ellipse([1380, 120, 1580, 320], fill=165)
    for base, amp, shade, freq in [(430, 90, 118, 0.0021), (520, 70, 98, 0.0033), (610, 60, 80, 0.0047), (700, 40, 62, 0.006)]:
        phase = r.random() * 6
        pts = [(x, base - amp * math.sin(x * freq + phase) - amp * 0.4 * math.sin(x * freq * 2.7 + phase)) for x in range(0, W + 20, 20)]
        d.polygon(pts + [(W, H), (0, H)], fill=shade)
    for _ in range(70):  # 前景樹
        x = r.randint(0, W)
        y = r.randint(660, 780)
        s = r.randint(30, 70)
        d.polygon([(x, y - s * 2), (x - s // 2, y), (x + s // 2, y)], fill=r.choice([52, 56, 60]))
    return img


def business(r):
    """商務幾何：企業、顧問、金融。斜向面板與上升的柱狀線條。"""
    img = sky(140, 110)
    d = ImageDraw.Draw(img)
    for i in range(9):
        x = -300 + i * 280 + r.randint(-40, 40)
        shade = r.choice([96, 108, 120, 132])
        d.polygon([(x, H), (x + 360, 0), (x + 520, 0), (x + 160, H)], fill=shade)
    for i in range(14):  # 上升柱
        x = 140 + i * 120
        h = 120 + i * 28 + r.randint(-30, 30)
        d.rectangle([x, H - h, x + 64, H], fill=70)
    pts = [(140 + i * 120 + 32, H - (150 + i * 28) - 60) for i in range(14)]
    d.line(pts, fill=160, width=6)
    for p in pts:
        d.ellipse([p[0] - 9, p[1] - 9, p[0] + 9, p[1] + 9], fill=160)
    return img


def community(r):
    """社區人文：公益、社福、協會。斜屋頂的街屋、樹與小窗。"""
    img = glow(sky(150, 115), [(500, 300), (1300, 260)], level=168)
    d = ImageDraw.Draw(img)
    for row, (base, shade, scale) in enumerate([(560, 110, 0.8), (680, 86, 1.0), (800, 62, 1.2)]):
        x = -40 + row * 30
        while x < W:
            w = int(r.randint(120, 190) * scale)
            h = int(r.randint(90, 150) * scale)
            d.rectangle([x, base - h, x + w, H], fill=shade)
            d.polygon([(x - 10, base - h), (x + w // 2, base - h - int(70 * scale)), (x + w + 10, base - h)], fill=shade)
            for wx in range(x + 18, x + w - 30, int(46 * scale)):
                d.rectangle([wx, base - h + 30, wx + int(22 * scale), base - h + 30 + int(26 * scale)], fill=shade + 22)
            x += w + r.randint(30, 70)
            if r.random() < .5:  # 樹
                cx = x - r.randint(10, 25)
                d.ellipse([cx - 34 * scale, base - 120 * scale, cx + 34 * scale, base - 40 * scale], fill=shade - 6)
                d.rectangle([cx - 5, base - 45 * scale, cx + 5, base], fill=shade - 6)
    return img


def tech(r):
    """科技線條：資訊、新創。網格、節點與連線。"""
    img = sky(105, 75)
    d = ImageDraw.Draw(img)
    for x in range(0, W, 80):
        d.line([(x, 0), (x, H)], fill=88, width=1)
    for y in range(0, H, 80):
        d.line([(0, y), (W, y)], fill=88, width=1)
    nodes = [(r.randint(0, W), r.randint(0, H)) for _ in range(90)]
    for i, a in enumerate(nodes):
        for b in nodes[i + 1:]:
            dist = math.dist(a, b)
            if dist < 210:
                d.line([a, b], fill=int(150 - dist / 4), width=2)
    for x, y in nodes:
        s = r.choice([5, 7, 10])
        d.ellipse([x - s, y - s, x + s, y + s], fill=160)
    for _ in range(26):  # 電路走線
        x, y = r.randrange(0, W, 80), r.randrange(0, H, 80)
        pts = [(x, y)]
        for _ in range(4):
            x += r.choice([-160, 0, 160])
            y += r.choice([-80, 0, 80])
            pts.append((x, y))
        d.line(pts, fill=128, width=4)
    return img


def culture(r):
    """文化典雅：藝文、宗教、傳統產業。連續拱廊與格紋窗花。"""
    img = sky(150, 120)
    d = ImageDraw.Draw(img)
    for x in range(0, W, 48):  # 背景窗花格紋
        for y in range(0, 380, 48):
            d.rectangle([x + 4, y + 4, x + 44, y + 44], outline=136, width=2)
            d.line([(x + 4, y + 24), (x + 44, y + 24)], fill=136, width=1)
            d.line([(x + 24, y + 4), (x + 24, y + 44)], fill=136, width=1)
    d.rectangle([0, 330, W, 380], fill=96)  # 屋簷
    d.polygon([(0, 330), (W, 330), (W - 60, 290), (60, 290)], fill=104)
    span = 240
    for i in range(-1, W // span + 2):  # 拱廊
        x = i * span
        d.rectangle([x, 380, x + span, H], fill=78)
        d.rectangle([x + 40, 520, x + span - 40, H], fill=120)
        d.ellipse([x + 40, 440, x + span - 40, 600], fill=120)
        d.rectangle([x, 380, x + 26, H], fill=62)
    d.rectangle([0, H - 40, W, H], fill=56)
    return img


THEMES = {"city": city, "nature": nature, "business": business, "community": community, "tech": tech, "culture": culture}


def main():
    OUT.mkdir(parents=True, exist_ok=True)
    for name, draw in THEMES.items():
        img = draw(random.Random(f"futheme-{name}")).filter(ImageFilter.GaussianBlur(1.2))
        # 把亮度線性壓進 LUM_MIN～LUM_MAX，保證主色遮罩下的文字對比
        lo, hi = img.getextrema()
        img = img.point(lambda v: round(LUM_MIN + (v - lo) * (LUM_MAX - LUM_MIN) / max(hi - lo, 1)))
        for w in (1920, 960):
            im = img if w == W else img.resize((w, H * w // W), Image.LANCZOS)
            im.convert("RGB").save(OUT / f"banner-{name}-{w}.webp", "WEBP", quality=72, method=6)
        print(f"{name}: luminance {img.getextrema()}")


if __name__ == "__main__":
    main()
