<?php
/**
 * Title: Homepage welcome
 * Slug: southtabor/hero
 * Categories: southtabor
 * Description: Welcome text with the mailing list and meeting buttons, beside the next board meeting card.
 *
 * @package southtabor
 */
?>
<!-- wp:columns {"verticalAlignment":"bottom","align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","width":"60%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:60%"><!-- wp:paragraph {"className":"is-style-street"} -->
<p class="is-style-street">SE Division Street to Powell Boulevard</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"fontSize":"display","style":{"typography":{"lineHeight":"1.08"},"layout":{"selfStretch":"fit"}}} -->
<h1 class="wp-block-heading has-display-font-size" style="line-height:1.08">Neighbors looking after South Tabor since 1972</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"lead"} -->
<p class="has-lead-font-size">If you live, work, or own a business or property in South Tabor, you are invited to take part. Volunteers meet every month to share ideas, make decisions, and plan projects for the neighborhood.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"top":"var:preset|spacing|50"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--50)"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://groups.io/g/southtabor">Join STNA's mailing list</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-secondary"} -->
<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button" href="#meetings">Come to a meeting</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:40%"><!-- wp:pattern {"slug":"southtabor/meeting-card"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
