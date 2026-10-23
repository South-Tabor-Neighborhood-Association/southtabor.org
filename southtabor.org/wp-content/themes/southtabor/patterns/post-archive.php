<?php
/**
 * Title: Post list for archives
 * Slug: southtabor/post-archive
 * Categories: query
 * Inserter: no
 *
 * @package southtabor
 */
?>
<!-- wp:query {"queryId":2,"query":{"inherit":true},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<?php require get_theme_file_path( 'inc/post-card.html' ); ?>
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"space-between"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<!-- wp:query-pagination-previous {"label":"Newer"} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":"Older"} /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>Nothing here yet. Try the search, or go back to the <a href="/">homepage</a>.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
