# southtabor.org — Project Context

## Overview
WordPress site for the South Tabor Neighborhood Association (STNA), hosted on DreamHost
Shared Unlimited. The repo is **public** on GitHub
(`South-Tabor-Neighborhood-Association/southtabor.org`), so nothing secret goes in it.

The site is being redesigned as a block theme (**South Tabor**). Production still runs the
legacy **Exciter** theme; the redesign is on staging for review. Next steps are tracked in
[TODO.md](TODO.md).

## Working Agreements
- Don't commit or push without asking.
- Show the error (for example `docker compose logs wordpress`) before changing anything
  to fix a fatal.
- Report what looks broken before fixing it, and leave content decisions to the user.
- Ask before running anything against production.

## Repository Layout
```
southtabor.org/              ← workspace root
├── docker-compose.yml       ← local dev stack
├── CLAUDE.md                ← this file
├── TODO.md                  ← next steps
├── southtabor.org/          ← WordPress document root (mounted into Docker)
│   ├── wp-config.php        ← local dev DB credentials + local URL overrides
│   ├── db_backup.sql        ← production DB dump, imported on first `up` (gitignored)
│   ├── wp-content/
│   │   ├── themes/southtabor/  ← redesign block theme (primary place for front-end work)
│   │   ├── themes/Exciter/     ← legacy theme, still active on production
│   │   ├── plugins/            ← plugins (see below)
│   │   ├── mu-plugins/         ← 0-worker.php, a leftover ManageWP loader
│   │   └── uploads/            ← media library (gitignored)
│   └── wp-includes/         ← WordPress core (do not edit)
└── logs/                    ← DreamHost server logs (gitignored, reference only)
```

## Local Development

### Stack
| Service   | Image                                     | Port |
|-----------|-------------------------------------------|------|
| WordPress | `wordpress:php8.3-apache` (matches DreamHost) | 8080 |
| Database  | `mysql:8.4` (matches DreamHost) | 3306 |

- The DB container has the network alias `mysql.southtabor.dreamhosters.com`, so the
  `DB_HOST` in `wp-config.php` works unchanged.
- Table prefix is `wp_fwtwtf_`.
- WordPress is 7.1.3.

### Start / Stop
```bash
docker compose up -d                          # start (first run imports db_backup.sql)
docker compose down                           # stop, keeping the DB volume
docker compose down -v && docker compose up -d   # reset the DB and re-import
```
The import only runs when the `db_data` volume is empty. After a reset, the
production dump comes back with Exciter active, which fatals on PHP 8. Switch the theme in
the DB:
```sql
UPDATE wp_fwtwtf_options SET option_value = 'southtabor' WHERE option_name IN ('template', 'stylesheet');
```
The Site Editor needs an administrator; production accounts may only be Editors.

### Local URL
[http://localhost:8080](http://localhost:8080). `wp-config.php` sets `WP_HOME` and
`WP_SITEURL` to it; those values are for local dev only and never go to a server.

### Security (detect-secrets)
A pre-commit hook runs Yelp's `detect-secrets` against `.secrets.baseline`. On a new machine:
```bash
pip install pre-commit detect-secrets
pre-commit install
```
The hook may rewrite `.secrets.baseline` (line numbers); stage it and commit again.
WordPress core and plugin updates trigger many false positives. Check the changed files
against the wordpress.org checksums, then commit with `git commit --no-verify`.

## South Tabor Theme
`wp-content/themes/southtabor/`, a block theme built from the South Tabor design system.
It does not depend on Exciter.
- `theme.json` — all design tokens (colors, fonts, sizes, spacing, radii)
- `style.css` — component styles (`st-*` classes and `is-style-*` block styles). Bump
  `Version` when changing CSS, since it's the cache-busting query string.
- `templates/`, `parts/`, `patterns/`, `inc/post-card.html` — layouts; volunteers can edit
  them in the Site Editor
- `functions.php`:
  - block styles (button secondary/event, group meeting-card/panel/notice, street label, link list)
  - `southtabor_meeting_settings()` — the board meeting schedule (third Thursday, 7–8:30pm,
    no December, St. Mark's Church); filterable with `southtabor_meeting_settings`
  - `[stna_next_meeting]` shortcode and the `southtabor/next-meeting` block (in `blocks/`,
    no build step; the editor shows a server-side render)
  - the "Add to calendar" button: any Button block with class `stna-add-to-calendar`
  - hides a post's featured image on its own page when the same image is already in the content
  - `[stna_copyright]`
- Fonts (Zilla Slab, Public Sans, OFL) are self-hosted in `assets/fonts/`.

## Plugins (in the repo)
| Plugin | Purpose |
|---|---|
| `akismet` | Comment spam filtering |
| `all-in-one-wp-migration` | Full-site backup/migration |
| `contact-form-7` | Contact forms |
| `contact-form-7-to-database-extension` | Saves form submissions to the DB (contains personal data) |
| `really-simple-captcha` | CAPTCHA for the forms |
| `email-address-encoder` | Hides email addresses from scrapers |
| `redirection` | 301 redirects (29 of them) |
| `pdf-viewer-block` | PDF embeds in posts |
| `duplicate-post` | Clone posts and pages |
| `dreamhost-panel-login.php` | DreamHost panel one-click login (DreamHost-specific) |

There is no analytics plugin for now; a replacement is in TODO.md. Production still has
plugins that were removed from the repo; see TODO.md too.

## Servers (DreamHost)
- SSH: `dh_southtabor@pdx1-shared-a3-03.dreamhost.com`, key-based.
- Production: `~/southtabor.org`. Staging: `~/staging.southtabor.org`.
- WP-CLI is installed. The CLI `php` is 8.2, so run it as
  `WP_CLI_PHP=/usr/local/php83/bin/php wp …` to match the web server.
- PHP 7.4 through 8.5 are available; the version is set per site in the panel.

### Staging (https://staging.southtabor.org)
Public, but sends no email and asks search engines not to index it. It is DreamHost's
one-click WordPress install with this repo's theme, plugins and uploads on top, plus a
copy of the local DB. Differences from local:
- Its `wp-config.php` is DreamHost's (own database, `wp_fwtwtf_` prefix, `WP_DEBUG_LOG` to
  `~/staging-debug.log`, `DISALLOW_FILE_EDIT`). It is not in the repo.
- A `mu-plugins/staging-no-email.php` plugin (`pre_wp_mail` → true) fakes success for all
  mail. It must never reach production.
- `blog_public` is 0.
- Originals of its config files are in `~/staging-backup/`.

### Deploying files and data
- Exclude `*.swp`, `.DS_Store`, `wp-config.php` and any `.sql` files.
- Upload DB dumps to the home directory, never a web root, and delete them after importing.
- Run rsync from bash, or brace remote paths as `${H}:path`. In zsh, `$H:s…` is parsed as a
  substitution modifier, and rsync then copies into a local folder.
- After importing, use `wp search-replace '//southtabor.org' '//staging.southtabor.org'
  --all-tables-with-prefix --skip-columns=guid`. The stored site URL is `http://southtabor.org`.

## Known Issues
- Exciter fatals under PHP 8 locally, so use the South Tabor theme locally.
- `.env.swp` was once deployed and blocked by Apache (`AH01630`). Always exclude `*.swp`.
- `wp_fwtwtf_logo_thumbnail_backup` holds the 62 logo featured images that were removed
  (local DB and staging; production once its SQL is run). It has MySQL 8's
  `utf8mb4_0900_ai_ci` collation, which MariaDB rejects, so drop it before moving to a
  MariaDB host such as Hostinger.
- The local DB still has Independent Analytics' 27 tables (history from January to June 2026).
  The plugin is gone; drop the `wp_fwtwtf_independent_analytics_*` tables once nobody needs the data.
