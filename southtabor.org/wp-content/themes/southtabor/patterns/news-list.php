<?php
/**
 * Title: Latest news
 * Slug: southtabor/news-list
 * Categories: southtabor, query
 * Description: The five newest posts with roundel date stamps.
 *
 * @package southtabor
 */
?>
<!-- wp:group {"tagName":"section","anchor":"news","layout":{"type":"default"},"style":{"spacing":{"blockGap":"0"}}} -->
<section id="news" class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"bottom"},"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|30"}}}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">News</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-weight:600"><a href="/category/news/">All news</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":1,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false,"sticky":"exclude"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<?php require get_theme_file_path( 'inc/post-card.html' ); ?>
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>No news yet.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></section>
<!-- /wp:group -->
