# 產生 FuTheme 預設橫幅：灰階抽象城市剪影，由各配色的主色遮罩上色。原創，無外部素材。
import random, sys
from PIL import Image, ImageDraw, ImageFilter
random.seed(20261116)
W, H = 1920, 800
img = Image.new("L", (W, H))
d = ImageDraw.Draw(img)
for y in range(H):  # 天空漸層：上亮下暗
    d.line([(0, y), (W, y)], fill=int(150 - 50 * y / H))
# 斜向光帶
glow = Image.new("L", (W, H), 0); g = ImageDraw.Draw(glow)
for x0, w in [(300, 260), (900, 180), (1450, 320)]:
    g.polygon([(x0, 0), (x0 + w, 0), (x0 + w - 500, H), (x0 - 500, H)], fill=26)
glow = glow.filter(ImageFilter.GaussianBlur(60))
img = Image.composite(Image.new("L", (W, H), 175), img, glow)
d = ImageDraw.Draw(img)
def skyline(base, hmin, hmax, shade, win):
    x = -20
    while x < W:
        w = random.randint(70, 170); h = random.randint(hmin, hmax)
        d.rectangle([x, base - h, x + w, H], fill=shade)
        if random.random() < .3:  # 屋頂退縮
            d.rectangle([x + w // 4, base - h - 40, x + 3 * w // 4, base - h], fill=shade)
        if win:
            for wy in range(base - h + 24, H - 10, 34):
                for wx in range(x + 14, x + w - 18, 26):
                    if random.random() < .55:
                        d.rectangle([wx, wy, wx + 10, wy + 16], fill=shade + win)
        x += w + random.randint(4, 24)
skyline(H, 260, 520, 112, 0)    # 遠景
skyline(H, 140, 380, 84, 18)    # 中景
skyline(H, 60, 220, 58, 14)     # 近景
img = img.filter(ImageFilter.GaussianBlur(1.2))
out = sys.argv[1]
for w in (1920, 960):
    im = img if w == W else img.resize((w, H * w // W), Image.LANCZOS)
    im.convert("RGB").save(f"{out}/banner-{w}.webp", "WEBP", quality=72, method=6)
print("luminance min/max:", img.getextrema())
