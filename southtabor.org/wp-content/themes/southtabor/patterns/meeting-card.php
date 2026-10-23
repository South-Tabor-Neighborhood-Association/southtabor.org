<?php
/**
 * Title: Next board meeting
 * Slug: southtabor/meeting-card
 * Categories: southtabor
 * Description: Date of the next board meeting, worked out from the third-Thursday schedule, with venue and an Add to calendar button.
 *
 * @package southtabor
 */
?>
<!-- wp:group {"tagName":"section","anchor":"meetings","className":"is-style-meeting-card","layout":{"type":"default"}} -->
<section id="meetings" class="wp-block-group is-style-meeting-card"><!-- wp:southtabor/next-meeting {"lock":{"remove":true}} /-->

<!-- wp:paragraph {"className":"st-muted"} -->
<p class="st-muted"><strong>St. Mark's Church</strong>, 5415 SE Powell Blvd. Enter from the north parking lot.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"st-muted"} -->
<p class="st-muted">Third Thursday of every month except December. All are welcome at any STNA meeting.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"stna-add-to-calendar"} -->
<div class="wp-block-button stna-add-to-calendar"><a class="wp-block-button__link wp-element-button" href="#">Add to calendar</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-secondary"} -->
<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button" href="/category/minutes/">Past minutes</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->
