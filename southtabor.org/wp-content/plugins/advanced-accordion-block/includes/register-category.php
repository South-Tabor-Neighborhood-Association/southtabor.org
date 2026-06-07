<?php
// Stop Direct Access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AAB_Block_Category_Register
 *
 * Registers the block category for the plugin.
 */
class AAB_Block_Category_Register {
	/**
	 * Register block category.
	 *
	 * @param array   $categories List of categories.
	 * @param WP_Post $post       Current post object.
	 * @return array Modified categories.
	 */
	public function register_block_category( $categories, $post ) {
		return array_merge(
			[
				[
					'slug'  => 'accordion-block',
					'title' => esc_html__( 'Accordion Blocks', 'advanced-accordion-block' ),
				],
			],
			$categories
		);
	}
}

/**
 * Register block patterns.
 *
 * Loads pattern files from the block-patterns directory.
 */
function aab_register_block_patterns() {
	$patterns = glob( dirname( __DIR__ ) . '/block-patterns/*.php' );
	if ( $patterns ) {
		foreach ( $patterns as $pattern ) {
			include $pattern;
		}
	}
}
add_action( 'init', 'aab_register_block_patterns' );
