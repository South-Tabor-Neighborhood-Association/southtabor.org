# TODO

Next steps for the southtabor.org redesign. The redesign work is on the
`claude/sharp-mayer-oxgae9` branch and on https://staging.southtabor.org.

## Review on staging
- [ ] Walk through staging: homepage, news, minutes, a few old posts, the 390px mobile menu.
- [ ] Log in to staging wp-admin and check the Site Editor.
- [ ] Submit the contact form on staging. It should say "sent" without delivering anything.
- [ ] Share staging with the board or volunteers for feedback.

## Content decisions (for the board or volunteers)
- [ ] Main Nav menu: fix the garbled labels.
- [ ] Minutes posts show up in News; decide whether to keep them there or filter them out.
- [ ] About page lists a different meeting venue than the meeting card (St. Mark's Church).
- [ ] Decide where the `/#meetings` link should go.
- [ ] Decide who gets administrator access. The Site Editor needs it, and some accounts
      are Editors.

## Analytics
- [ ] Pick a replacement for Independent Analytics, which has been removed from the repo
      (Koko Analytics was the front-runner; Umami Cloud or GoatCounter if analytics should
      live outside WordPress). Nothing is being recorded meanwhile; it was already
      inactive on production.
- [ ] On production, export the Independent Analytics history if wanted, then delete the plugin,
      `uploads/iawp-geo-db.mmdb` and `uploads/iawp-favicons/`.

## Launch on production
- [ ] Merge `claude/sharp-mayer-oxgae9` into `main` (open a PR).
- [ ] Back up production (DB and files).
- [ ] Run the logo featured image removal SQL (backs up to `wp_fwtwtf_logo_thumbnail_backup`).
- [ ] Switch production PHP to 8.3 in the panel.
- [ ] Upload the South Tabor theme and activate it.
- [ ] Deactivate and delete the plugins removed from the repo: `advanced-accordion-block`,
      `any-mobile-theme-switcher`, `google-analytics-for-wordpress` (inactive),
      `independent-analytics` (inactive),
      `velvet-blues-update-urls`, `widget-entries`, `wp-edit`.
- [ ] ManageWP: `worker` is active on production, and the `0-worker.php` loader is in the repo.
      Confirm nobody uses ManageWP, then remove both.
- [ ] Delete the unused themes on production (`mobile-edition-mTheme`, old Twenty* themes).
      Remove Exciter once the new theme is settled.
- [ ] Confirm contact form email still arrives after launch.

## Hosting (if moving to Hostinger)
- [ ] Plan the email move: MX is at DreamHost, and the forms send as communications@southtabor.org.
- [ ] Set up SMTP for the forms, since Hostinger's `mail()` is capped at 100 emails a day.
- [ ] Exclude the homepage meeting card from LiteSpeed's page cache, or the date goes stale.
- [ ] Remove DreamHost-specific settings (`DB_HOST`, `dreamhost-panel-login.php`).
- [ ] Drop `wp_fwtwtf_logo_thumbnail_backup` before exporting (MariaDB rejects its collation).
- [ ] Switch the local Docker database to MariaDB to match.

## Cleanup
- [ ] When staging is no longer needed: remove the site and its database in the DreamHost
      panel, and delete `~/staging-backup/` and `~/staging-debug.log`.
- [ ] Delete the unused local MariaDB volume: `docker volume rm southtabororg_db_data_mariadb`.
- [ ] Drop the Independent Analytics tables from the local DB once nobody needs the data.
- [ ] Drop `wp_fwtwtf_logo_thumbnail_backup` once the logo removal is confirmed on production.
