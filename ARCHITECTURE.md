# WordPress Theme Architecture

This repository uses a parent theme plus child themes.

## Structure

- `wp-theme-starter/`: shared parent theme for templates, layout, base CSS, theme supports, menus, and assets.
- `ur-promoter/`: child theme for UR Promoter.
- `fudawang/`: child theme for Fudawang.
- `jyc-foundation/`: child theme for JYC Foundation.
- `aplus-age/`: child theme for Aplus Age.

## Why This Structure

Use the parent theme for shared behavior, templates, and admin-editable settings. Use child themes for default brand styling and small structural overrides only when a site truly needs them.

This keeps future site rollout fast:

1. Copy an existing child theme folder.
2. Rename the folder.
3. Update the `Theme Name` and `Text Domain` in `style.css`.
4. Set default brand colors in `assets/css/main.css` and `theme.json`.
5. Activate the child theme in WordPress.
6. Adjust site-specific content and most design settings in Appearance > Customize.

## Parent Theme Responsibilities

The parent theme should own:

- Template hierarchy files such as `front-page.php`, `home.php`, `single.php`, `page.php`, `archive.php`, `search.php`, and `404.php`.
- Shared template parts under `template-parts/`.
- Theme supports and WordPress setup in `functions.php`.
- Shared layout and component styling in `assets/css/main.css`.
- Shared block editor defaults in `theme.json`.
- Admin-editable settings in `inc/customizer.php`.

## Child Theme Responsibilities

Each child theme should own:

- Required WordPress theme header in `style.css`.
- `Template: wp-theme-starter`.
- Brand CSS variables in `assets/css/main.css`.
- Brand palette overrides in `theme.json`.
- Optional template overrides only when a site truly needs different structure.

## Design Tokens

Child themes should prefer overriding these CSS variables before adding custom component CSS:

- `--color-background`
- `--color-surface`
- `--color-text`
- `--color-muted`
- `--color-border`
- `--color-primary`
- `--color-accent`
- `--color-hero-background`
- `--color-hero-text`
- `--radius`

Most of these can also be changed in Appearance > Customize. Child theme values are defaults; Customizer values are the per-site override.

## Admin-Editable Settings

The parent theme exposes these settings in Appearance > Customize > Theme Design:

- Brand colors
- Hero background and text colors
- Content width
- Wide content width
- Border radius
- Section spacing
- Card image ratio
- Featured image ratio
- Front page hero eyebrow
- Front page hero title
- Front page hero summary
- Show or hide latest posts
- Latest posts section title
- Latest posts count
- Footer block count
- Footer text

Use WordPress admin areas for:

- Site title and tagline: Settings > General or Appearance > Customize > Site Identity
- Logo: Appearance > Customize > Site Identity
- Menus: Appearance > Menus
- Footer blocks: Appearance > Widgets, use Footer Block 1 through Footer Block 4
- Homepage assignment: Settings > Reading
- Page body sections: edit the assigned front page with the block editor

## Responsive Layout Rules

Shared CSS should preserve these rules:

- Images use fixed aspect-ratio containers on cards and hero media to avoid layout jumps.
- Images use `object-fit: cover` in card contexts so mixed source sizes remain visually consistent.
- Main content width, wide content width, radius, and section spacing are controlled by CSS variables and can be adjusted from Customizer.
- Footer widget blocks support 1 to 4 columns on desktop, 2 columns on tablet, and 1 column on mobile.
- The homepage header is wider and more open; inner page headers are more compact and include a small masthead band.

## Adding a New Site Theme

Copy `ur-promoter/` as a starting point:

```powershell
Copy-Item -Recurse .\ur-promoter .\new-site-theme
```

Then update:

- `new-site-theme/style.css`
- `new-site-theme/assets/css/main.css`
- `new-site-theme/theme.json`
- `new-site-theme/README.txt`

Keep the `Template: wp-theme-starter` line unchanged unless the parent theme folder is renamed.

## Installation

Upload both the parent theme and the desired child theme to `wp-content/themes/`:

- `wp-theme-starter`
- one child theme folder, such as `ur-promoter`

Activate only the child theme.
