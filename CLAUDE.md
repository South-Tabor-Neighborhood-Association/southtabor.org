# southtabor.org — Project Context

## Overview
WordPress site for South Tabor, hosted on DreamHost shared hosting.
The local development environment uses Docker to mirror production as closely as possible.

## Repository Layout
```
southtabor.org/              ← workspace root
├── docker-compose.yml       ← local dev stack
├── CLAUDE.md                ← this file
├── southtabor.org/          ← WordPress document root (mounted into Docker)
│   ├── wp-config.php        ← DB credentials + local URL overrides
│   ├── wp-content/
│   │   ├── themes/Exciter/  ← active custom theme (primary place for front-end work)
│   │   ├── plugins/         ← installed plugins (see list below)
│   │   ├── uploads/         ← media library
│   │   └── mu-plugins/      ← must-use plugins
│   ├── db_backup.sql        ← production DB dump (used for initial Docker import)
│   └── wp-includes/         ← WordPress core (do not edit)
└── logs/                    ← DreamHost server logs (reference only)
```

## Local Development

### Stack
| Service   | Image                    | Port  |
|-----------|--------------------------|-------|
| WordPress | `wordpress:php7.4-apache`| 8080  |
| MySQL     | `mysql:8.0`              | 3306  |

**Why PHP 7.4?** The `widget-entries` plugin is incompatible with PHP 8.x (`WP_Widget::__construct()` arity error). Keep the Docker image pinned to `php7.4-apache` until the plugin is updated or replaced.

### Start / Stop
```bash
# Start containers
docker-compose up -d

# Stop containers (preserves DB volume)
docker-compose down

# Reset everything including DB (re-imports db_backup.sql on next up)
docker-compose down -v && docker-compose up -d
```

### Local URL
[http://localhost:8080](http://localhost:8080)

### Refreshing the DB from Production
```bash
# 1. Export from production (run locally — requires your IP whitelisted in DreamHost panel)
mysqldump -h mysql.southtabor.dreamhosters.com \
  -u southtabordreamh -p'your_dev_password' \
  southtabor_dreamhosters_ > southtabor.org/db_backup.sql

# 2. Re-initialize the local DB
docker-compose down -v && docker-compose up -d
```

## Production (DreamHost)

| Setting         | Value                                      |
|-----------------|--------------------------------------------|
| Host            | `southtabor.dreamhosters.com`              |
| SSH/SFTP User   | `dh_southtabor`                            |
| DB Host         | `mysql.southtabor.dreamhosters.com:3306`   |
| DB Name         | `southtabor_dreamhosters_`                 |
| DB User         | `southtabordreamh`                         |
| Table Prefix    | `wp_fwtwtf_`                               |

### SSH
```bash
ssh dh_southtabor@southtabor.dreamhosters.com
```
> **Note:** DreamHost shared hosting has a low process limit (~100). If you hit
> `-bash: fork: Resource temporarily unavailable`, wait a few minutes for the
> server watchdog to clear dead processes, or close all SFTP clients before reconnecting.

### Transferring Files
Prefer a single compressed archive over SFTP folder-by-folder transfers:
```bash
# On the server (via SSH)
tar -czf southtabor-backup.tar.gz southtabor.org/

# Extract locally
tar -xzf southtabor-backup.tar.gz
```

For incremental syncs use `rsync` with exclusions to avoid pushing editor temp files:
```bash
rsync -avz --progress \
  --exclude='*.swp' --exclude='*.swo' \
  --exclude='.git/' --exclude='.DS_Store' \
  ./southtabor.org/ dh_southtabor@southtabor.dreamhosters.com:~/southtabor.org/
```

## Active Theme
**Exciter** (`wp-content/themes/Exciter/`)  
Custom theme with its own framework, CSS, JS, and skin system. Front-end changes
should happen here. Key entry points:
- `style.css` — main stylesheet
- `functions.php` — theme setup, hooks, widget registration
- `framework/` — theme framework files
- `css/` / `js/` — theme assets
- `skins/` — theme skin variants

## Active Plugins
| Plugin | Purpose |
|---|---|
| `akismet` | Spam filtering |
| `all-in-one-wp-migration` | Full site backup/migration |
| `contact-form-7` | Contact forms |
| `contact-form-7-to-database-extension` | Saves CF7 submissions to DB |
| `google-analytics-for-wordpress` | Analytics integration |
| `independent-analytics` | Privacy-friendly analytics |
| `redirection` | 301/302 redirect management |
| `widget-entries` | ⚠️ Legacy — incompatible with PHP 8.x |
| `pdf-viewer-block` | Gutenberg PDF viewer block |
| `advanced-accordion-block` | Gutenberg accordion block |
| `velvet-blues-update-urls` | Search & replace URLs in DB |
| `any-mobile-theme-switcher` | Mobile theme switching |
| `wp-edit` | TinyMCE editor enhancements |

## wp-config.php Notes
Local URL overrides are set near the bottom of `wp-config.php` to prevent
redirects to the production domain:
```php
define( 'WP_HOME',    'http://localhost:8080' );
define( 'WP_SITEURL', 'http://localhost:8080' );
```
**Do not commit these to production.** They are only needed for local dev.

## Known Issues
- PHP 8.x Incompatibilities: The `widget-entries` plugin, `wp-edit` plugin, and the legacy `Exciter` theme all throw fatal errors under PHP 8.x. The environment must remain pinned to PHP 7.4 locally until these are migrated or deactivated.
- `.env.swp` swap file was previously deployed to server and blocked by Apache (`AH01630`)  
  — always exclude `*.swp` files from rsync/deploys
