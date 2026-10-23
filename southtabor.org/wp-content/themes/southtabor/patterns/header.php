<?php
/**
 * Title: Site header
 * Slug: southtabor/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package southtabor
 */
?>
<!-- wp:group {"tagName":"header","align":"full","className":"st-header","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"1120px"}} -->
<header class="wp-block-group alignfull st-header" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"st-brand","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-group st-brand"><!-- wp:image {"width":"56px","height":"56px","sizeSlug":"full","linkDestination":"custom"} -->
<figure class="wp-block-image size-full is-resized"><a href="/"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/seal.png' ) ); ?>" alt="South Tabor Neighborhood Association home" style="width:56px;height:56px"/></a></figure>
<!-- /wp:image -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"st-brand__name"} -->
<p class="st-brand__name"><a href="/">South Tabor</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"st-brand__sub"} -->
<p class="st-brand__sub">Neighborhood Association</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:navigation {"textColor":"on-band","overlayBackgroundColor":"band","overlayTextColor":"on-band","overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
<!-- wp:navigation-link {"label":"News","url":"/category/news/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Meetings","url":"/#meetings","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Projects & Events","url":"/projects-events/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Land Use","url":"/land-use/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Resources","url":"/neighborhood-resources/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"About","url":"/about/","kind":"custom"} /-->
<!-- /wp:navigation --></div>
<!-- /wp:group --></header>
<!-- /wp:group -->
