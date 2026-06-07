<?php
// Stop Direct Access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Block
 */
class AAB_Block_Register {
	/**
	 * Constructor.
	 *
	 * Hooks into the init action.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'blocks_init' ] );
	}

	/**
	 * Register block
	 *
	 * @param string $name    Block name.
	 * @param array  $options Block options.
	 */
	public function register_block( $name, $options = [] ) {
		register_block_type( __DIR__ . '/../build/' . $name, $options );
	}

	/**
	 * Initialize blocks.
	 *
	 * Registers all the blocks for the plugin.
	 */
	public function blocks_init() {
		$this->register_block(
			'accordion',
			[
				'render_callback' => [ $this, 'render_separate_accordion' ],
			]
		);
		$this->register_block(
			'group-accordion',
			[
				'render_callback' => [ $this, 'render_group_accordion' ],
			]
		);
		$this->register_block(
			'accordion-item',
			[
				'render_callback' => [ $this, 'render_group_accordion_item' ],
			]
		);
		$this->register_block(
			'accordion-toolbar',
			[
				'render_callback' => [ $this, 'render_accordion_toolbar' ],
			]
		);
		$this->register_block(
			'accordion-default',
			[
				'render_callback' => [ $this, 'render_accordion_default' ],
			]
		);
		$this->register_block(
			'horizontal-accordion',
			[
				'render_callback' => [ $this, 'render_horizontal_accordion' ],
			]
		);
		$this->register_block(
			'horizontal-accordion-item',
			[
				'render_callback' => [ $this, 'render_horizontal_accordion_item' ],
			]
		);
	}

	/**
	 * Sanitize CSS values to prevent injection
	 *
	 * @param string $value Input value.
	 * @return string Sanitized value.
	 */
	private function sanitize_css_value( $value ) {
		// Strip tags and remove characters that could break out of CSS context
		$value = wp_strip_all_tags( $value );
		return preg_replace( '/[;{}<>]/', '', $value );
	}

	/**
	 * Render inline CSS.
	 *
	 * @param string $handle Style handle.
	 * @param string $css    CSS content.
	 */
	public function render_inline_css( $handle, $css ) {
		wp_register_style( $handle, false );
		wp_enqueue_style( $handle );
		wp_add_inline_style( $handle, $css );
	}

	/**
	 * Render separate accordion callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_separate_accordion( $attributes, $content ) {
		wp_register_style( 'aagb-separate-accordion', AAGB_URL . 'build/accordion/style-index.css' );

		if ( ! empty( $attributes['anchorLinkShow'] ) || ! empty( $attributes['defaultStyles']['anchorLinkShow'] ) ) {
			wp_enqueue_script( 'anchor' );
		}
		if ( ! empty( $attributes['feedbackShow'] ) ) {
			wp_enqueue_script( 'aagb-separate-accordion-feedback' );
		}

		// Security: Sanitize uniqueId
		$unique_id = isset( $attributes['uniqueId'] ) ? sanitize_html_class( $attributes['uniqueId'] ) : '';

		if ( $unique_id && ! is_admin() ) {
			$css_rules = [];
			$base      = '#aab_accordion_' . $unique_id;

			// Custom CSS
			if ( ! empty( $attributes['customCSS'] ) ) {
				$css_content = $this->sanitize_css_value( $attributes['customCSS'] );
				if ( ! empty( $css_content ) ) {
					$css_rules[] = $base . ' { ' . $css_content . ' }';
				}
			}

			// Anchor Link Color
			$anchor_link_show = ! empty( $attributes['anchorLinkShow'] );
			$heading_color    = isset( $attributes['headingColor'] ) ? $attributes['headingColor'] : null;

			if ( $anchor_link_show && $heading_color ) {
				$css_rules[] = $base . ' .aab__accordion_heading .anchorjs-link { color: ' . $this->sanitize_css_value( $heading_color ) . '; }';
			}

			// Accessibility Focus Outline
			$accessibility_on    = isset( $attributes['accessibilityOn'] ) ? $attributes['accessibilityOn'] : true;
			$focus_outline_color = isset( $attributes['focusOutlineColor'] ) ? $attributes['focusOutlineColor'] : '#C2DBFE';

			if ( $accessibility_on && '#C2DBFE' !== $focus_outline_color ) {
				$css_rules[] = $base . ':focus-visible { outline: 2px solid ' . $this->sanitize_css_value( $focus_outline_color ) . '; }';
			}

			if ( ! empty( $css_rules ) ) {
				$handle = 'aagb-separate-' . $unique_id;
				$this->render_inline_css( $handle, implode( ' ', $css_rules ) );
			}

			// Add AnchorJS script inline
			if ( $anchor_link_show ) {
				$script = 'document.addEventListener("DOMContentLoaded", () => { var Anchor1 = new AnchorJS(); Anchor1.add("#aab_accordion_' . $unique_id . ' .aab__accordion_heading .title_wrapper"); });';
				wp_add_inline_script( 'anchor', $script );
			}
		}

		return $content;
	}

	/**
	 * Group accordion item render callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_group_accordion_item( $attributes, $content ) {
		wp_register_style( 'aagb-group-accordion-item', AAGB_URL . 'build/accordion-item/style-index.css' );

		return $content;
	}

	/**
	 * Accordion Toolbar render callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_accordion_toolbar( $attributes, $content ) {
		wp_register_style( 'aagb-accordion-toolbar', AAGB_URL . 'build/accordion-toolbar/index.css' );

		return $content;
	}

	/**
	 * Accordion Default render callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_accordion_default( $attributes, $content ) {
		wp_register_style( 'aagb-accordion-defaults', AAGB_URL . 'build/accordion-default/index.css' );

		return $content;
	}

	/**
	 * Group accordion render callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_group_accordion( $attributes, $content ) {

		if ( ! empty( $attributes['anchorLinksShow'] ) || ! empty( $attributes['defaultStyles']['anchorLinkShow'] ) ) {
			wp_enqueue_script( 'anchor' );
		}

		if ( ! empty( $attributes['searchShow'] ) ) {
			wp_enqueue_script( 'mark' );
		}

		// Security: Sanitize uniqueId
		$unique_id = isset( $attributes['uniqueId'] ) ? sanitize_html_class( $attributes['uniqueId'] ) : '';

		if ( empty( $unique_id ) || is_admin() ) {
			return $content;
		}

		// Generate dynamic styles
		$custom_css = $this->generate_group_accordion_styles( $attributes, $unique_id );

		if ( ! empty( $custom_css ) ) {
			$handle = 'aagb-' . $unique_id;
			$this->render_inline_css( $handle, $custom_css );
		}

		return $content;
	}

	/**
	 * Generate all dynamic CSS for the group accordion block.
	 *
	 * This centralizes style generation server-side, allowing minimal save output
	 * while maintaining full styling capabilities. External plugins can insert
	 * blocks with just the HTML structure and attributes.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $unique_id  Sanitized unique ID.
	 * @return string Generated CSS rules.
	 */
	private function generate_group_accordion_styles( $attributes, $unique_id ) {
		$css_rules  = [];
		$base       = '.aagb_accordion_' . $unique_id;
		$qa_style   = ! empty( $attributes['QaStyle'] );
		$check_list = ! empty( $attributes['checkList'] );

		// Helper to get attribute with fallback to defaultStyles
		$get_attr = function( $key ) use ( $attributes ) {
			if ( isset( $attributes[ $key ] ) ) {
				return $attributes[ $key ];
			}
			if ( isset( $attributes['defaultStyles'][ $key ] ) ) {
				return $attributes['defaultStyles'][ $key ];
			}
			return null;
		};

		// -----------------
		// Active Accordion Border
		// -----------------
		$active_border = isset( $attributes['activeAccordionBorder'] ) ? $attributes['activeAccordionBorder'] : [];
		$border_width  = isset( $active_border['width'] ) ? $this->sanitize_css_value( $active_border['width'] ) : '';
		$border_style  = isset( $active_border['style'] ) ? $this->sanitize_css_value( $active_border['style'] ) : '';
		$border_color  = isset( $active_border['color'] ) ? $this->sanitize_css_value( $active_border['color'] ) : '';
		$active_bg     = isset( $attributes['activeBackground'] ) ? $this->sanitize_css_value( $attributes['activeBackground'] ) : '';

		$container_styles = '';

		if ( $active_bg ) {
			$container_styles .= ' background: ' . $active_bg . ' !important;';
		}

		if ( $border_width && '0' !== $border_width && '0px' !== $border_width && $border_style && $border_color ) {
			// Active container styles
			$container_styles = 'border-width: ' . $border_width . ' !important;';
			$container_styles .= ' border-style: ' . $border_style . ' !important;';
			$container_styles .= ' border-color: ' . $border_color . ' !important;';

			// Active body border-top
			$css_rules[] = $base . ' .aagb__accordion_active .aagb__accordion_body { border-top: ' . $border_width . ' ' . $border_style . ' ' . $border_color . '; }';
		}
		
		if ( ! empty( $container_styles ) ) {
			$css_rules[] = $base . ' > .aagb__accordion_container.aagb__accordion_active { ' . $container_styles . ' }';
		}

		// -----------------
		// Header Styles
		// -----------------
		$header_bg = $get_attr( 'headerBg' );
		$paddings  = $get_attr( 'paddings' );

		// Calculate header background (transparent for QA style if default)
		if ( $qa_style && $header_bg && '#e3dfdf38' === strtolower( $header_bg ) ) {
			$header_bg = 'transparent';
		}

		$qa_selector    = $qa_style ? '.style-qa' : '';
		$head_selector  = $base . ' .aagb__accordion_container.wp-block-aab-accordion-item' . $qa_selector . ' .aagb__accordion_head';
		$head_styles    = [];

		if ( $header_bg ) {
			$head_styles[] = 'background-color: ' . $this->sanitize_css_value( $header_bg );
		}

		if ( is_array( $paddings ) && array_filter( $paddings ) ) {
			$head_styles[] = 'padding: ' . $this->sanitize_css_value( $paddings['top'] ?? '' ) . ' '
				. $this->sanitize_css_value( $paddings['right'] ?? '' ) . ' '
				. $this->sanitize_css_value( $paddings['bottom'] ?? '' ) . ' '
				. $this->sanitize_css_value( $paddings['left'] ?? '' );
		}

		$heading_border = $get_attr( 'headingBorder' );
		if ( is_array( $heading_border ) && ! empty( $heading_border['width'] ) && '0px' !== $heading_border['width'] ) {
			$head_styles[] = 'border: ' . $this->sanitize_css_value( $heading_border['width'] ?? '' ) . ' '
				. $this->sanitize_css_value( $heading_border['style'] ?? 'solid' ) . ' '
				. $this->sanitize_css_value( $heading_border['color'] ?? '' );
		}

		if ( ! empty( $head_styles ) ) {
			$css_rules[] = $head_selector . ' { ' . implode( '; ', $head_styles ) . '; }';
		}

		// -----------------
		// Body Styles
		// -----------------
		$body_bg       = $get_attr( 'bodyBg' );
		$body_border   = $get_attr( 'bodyBorder' );
		$body_selector = $base . ' .aagb__accordion_container.wp-block-aab-accordion-item' . $qa_selector . ' .aagb__accordion_body';
		$body_styles   = [];

		if ( $body_bg ) {
			$body_styles[] = 'background-color: ' . $this->sanitize_css_value( $body_bg );
		}

		if ( $qa_style ) {
			$body_styles[] = 'border-top: none !important';
		}

		// Body paddings based on QaStyle and checkList
		if ( is_array( $paddings ) && array_filter( $paddings ) ) {
			$body_paddings = $this->calculate_body_paddings( $paddings, $qa_style, $check_list );
			if ( ! empty( $body_paddings ) ) {
				$body_styles[] = 'padding: ' . $body_paddings;
			}
		}

		// Body border
		if ( is_array( $body_border ) ) {
			$body_border_css = $this->get_border_css( $body_border );
			if ( $body_border_css ) {
				$body_styles[] = $body_border_css;
			}
		}

		if ( ! empty( $body_styles ) ) {
			$css_rules[] = $body_selector . ' { ' . implode( '; ', $body_styles ) . '; }';
		}

		// -----------------
		// QA Style Icons
		// -----------------
		if ( $qa_style ) {
			$q_icon_color = $get_attr( 'qIconColor' );
			$q_icon_bg    = $get_attr( 'qIconBg' );
			$a_icon_color = $get_attr( 'aIconColor' );
			$a_icon_bg    = $get_attr( 'aIconBg' );

			if ( $q_icon_color || $q_icon_bg ) {
				$q_styles = [];
				if ( $q_icon_color ) {
					$q_styles[] = 'color: ' . $this->sanitize_css_value( $q_icon_color );
				}
				if ( $q_icon_bg ) {
					$q_styles[] = 'background-color: ' . $this->sanitize_css_value( $q_icon_bg );
				}
				$css_rules[] = $base . ' .wp-block-aab-accordion-item .aagb__accordion_head .icon-container .icon-q { ' . implode( '; ', $q_styles ) . '; }';
			}

			if ( $a_icon_color || $a_icon_bg ) {
				$a_styles = [];
				if ( $a_icon_color ) {
					$a_styles[] = 'color: ' . $this->sanitize_css_value( $a_icon_color );
				}
				if ( $a_icon_bg ) {
					$a_styles[] = 'background-color: ' . $this->sanitize_css_value( $a_icon_bg );
				}
				$css_rules[] = $base . ' .wp-block-aab-accordion-item .aagb__accordion_head .icon-container .icon-a { ' . implode( '; ', $a_styles ) . '; }';
			}
		}

		// -----------------
		// Subheading Color
		// -----------------
		$subheading_color = $get_attr( 'subheadingColor' );
		if ( $subheading_color ) {
			$css_rules[] = $base . ' .wp-block-aab-accordion-item .aagb__accordion_subheading { margin: 5px 0 0 0; color: ' . $this->sanitize_css_value( $subheading_color ) . '; }';
		}

		// -----------------
		// Icon Styles
		// -----------------
		$icon_color      = $get_attr( 'iconColor' );
		$icon_background = $get_attr( 'iconBackground' );
		$icon_font_size  = $get_attr( 'iconFontSize' );

		if ( $icon_color || $icon_background ) {
			$icon_styles = [];
			if ( $icon_color ) {
				$icon_styles[] = 'color: ' . $this->sanitize_css_value( $icon_color );
			}
			if ( $icon_background ) {
				$icon_styles[] = 'background-color: ' . $this->sanitize_css_value( $icon_background );
			}
			$css_rules[] = $base . ' .wp-block-aab-accordion-item .aagb__accordion_icon { ' . implode( '; ', $icon_styles ) . '; }';
		}

		if ( $icon_font_size && is_numeric( $icon_font_size ) && $icon_font_size > 0 ) {
			$css_rules[] = $base . ' .wp-block-aab-accordion-item .aagb__icon { font-size: ' . intval( $icon_font_size ) . 'px; }';
		}

		// -----------------
		// Heading Color
		// -----------------
		$heading_color = $get_attr( 'headingColor' );
		if ( $heading_color ) {
			$css_rules[] = $base . ' .aagb__accordion_title { margin: 0; color: ' . $this->sanitize_css_value( $heading_color ) . '; }';
		}

		// -----------------
		// Container Border & Margins
		// -----------------
		$border        = $get_attr( 'border' );
		$margins       = $get_attr( 'margins' );
		$border_radius = $get_attr( 'borderRadius' );
		$cont_styles   = [];

		if ( is_array( $border ) && ! empty( $border['width'] ) ) {
			$cont_styles[] = 'border: ' . $this->sanitize_css_value( $border['width'] ?? '' ) . ' '
				. $this->sanitize_css_value( $border['style'] ?? 'solid' ) . ' '
				. $this->sanitize_css_value( $border['color'] ?? '' );
		}

		if ( is_array( $margins ) ) {
			if ( ! empty( $margins['top'] ) ) {
				$cont_styles[] = 'margin-top: ' . $this->sanitize_css_value( $margins['top'] );
			}
			if ( ! empty( $margins['bottom'] ) ) {
				$cont_styles[] = 'margin-bottom: ' . $this->sanitize_css_value( $margins['bottom'] );
			}
		}

		if ( $border_radius && is_numeric( $border_radius ) && $border_radius > 0 ) {
			$cont_styles[] = 'border-radius: ' . intval( $border_radius ) . 'px';
		}

		if ( ! empty( $cont_styles ) ) {
			$css_rules[] = $base . ' .aagb__accordion_container { ' . implode( '; ', $cont_styles ) . '; }';
		}

		// -----------------
		// Auto Numbering
		// -----------------
		$auto_numbering        = ! empty( $attributes['autoNumbering'] );
		$auto_numbering_margin = isset( $attributes['autoNumberingMargin'] ) ? intval( $attributes['autoNumberingMargin'] ) : 10;
		$auto_numbering_color  = $get_attr( 'autoNumberingColor' );

		if ( $auto_numbering ) {
			$css_rules[] = $base . ' .aagb__accordion_number { margin-right: ' . $auto_numbering_margin . 'px; }';
			$css_rules[] = $base . ' .wp-block-aab-accordion-item .aagb__accordion_subheading { margin-left: calc(42px + ' . $auto_numbering_margin . 'px) !important; }';

			if ( $auto_numbering_color ) {
				$css_rules[] = $base . ' .aagb__accordion_number { color: ' . $this->sanitize_css_value( $auto_numbering_color ) . '; }';
			}
		}

		// -----------------
		// Step Progress Colors
		// -----------------
		$step_progress_active_color = $get_attr( 'stepProgressActiveColor' );
		$step_progress_bg_color     = $get_attr( 'stepProgressBgColor' );

		if ( $step_progress_active_color ) {
			$css_rules[] = $base . ' .aab-step-progress { background-color: ' . $this->sanitize_css_value( $step_progress_active_color ) . '; }';
		}
		if ( $step_progress_bg_color ) {
			$css_rules[] = $base . ' .aab-step-progress-bar { background-color: ' . $this->sanitize_css_value( $step_progress_bg_color ) . '; }';
		}

		// -----------------
		// Progress Bar Colors
		// -----------------
		$progress_bar_bg_color     = $get_attr( 'progressBarBgColor' );
		$progress_bar_active_color = $get_attr( 'progressBarActiveColor' );

		if ( $progress_bar_bg_color ) {
			$css_rules[] = $base . ' .aab-progress-bar-container { background-color: ' . $this->sanitize_css_value( $progress_bar_bg_color ) . '; }';
		}
		if ( $progress_bar_active_color ) {
			$css_rules[] = $base . ' .aab-progress-bar { background-color: ' . $this->sanitize_css_value( $progress_bar_active_color ) . '; }';
		}

		// -----------------
		// Feature Image Direction
		// -----------------
		$feature_img_direction = isset( $attributes['featureImgDirection'] ) ? $attributes['featureImgDirection'] : 'right';
		if ( 'left' === $feature_img_direction ) {
			$css_rules[] = '.aagb__group_accordion_container.has_img { flex-direction: row-reverse; }';
		}

		// -----------------
		// Next/Previous Navigation
		// -----------------
		$next_previous           = ! empty( $attributes['nextPrevious'] );
		$next_previous_direction = isset( $attributes['nextPreviousDirection'] ) ? $attributes['nextPreviousDirection'] : 'left';
		$np_icon_bg_color        = isset( $attributes['nextPreviousIconBgColor'] ) ? $this->sanitize_css_value( $attributes['nextPreviousIconBgColor'] ) : '';
		$np_icon_color           = isset( $attributes['nextPreviousIconColor'] ) ? $this->sanitize_css_value( $attributes['nextPreviousIconColor'] ) : '';

		if ( $next_previous ) {
			// Container padding
			$padding_map = [
				'left'   => 'padding-left: 50px !important',
				'right'  => 'padding-right: 50px !important',
				'top'    => 'padding-top: 80px !important',
				'bottom' => 'padding-bottom: 80px !important',
			];
			if ( isset( $padding_map[ $next_previous_direction ] ) ) {
				$css_rules[] = $base . '.wp-block-aab-group-accordion.aab-next-previous-enabled { ' . $padding_map[ $next_previous_direction ] . '; }';
			}

			// Navigation position
			$position_map = [
				'left'   => 'left: 0; top: 50%; transform: translateY(-50%); position: absolute;',
				'right'  => 'right: 0; top: 50%; transform: translateY(-50%); position: absolute;',
				'top'    => 'top: 0; left: 50%; transform: rotate(270deg); position: absolute;',
				'bottom' => 'bottom: 0; left: 50%; transform: rotate(270deg); position: absolute;',
			];
			if ( isset( $position_map[ $next_previous_direction ] ) ) {
				$css_rules[] = $base . ' .aagb-accordion-next-previous { ' . $position_map[ $next_previous_direction ] . ' }';
			}

			// Icon styles
			if ( $np_icon_bg_color ) {
				$css_rules[] = $base . ' .aagb-accordion-previous-icon, ' . $base . ' .aagb-accordion-next-icon { background-color: ' . $np_icon_bg_color . '; }';
			}
			if ( $np_icon_color ) {
				$css_rules[] = $base . ' .aagb-accordion-previous-icon svg, ' . $base . ' .aagb-accordion-next-icon svg { fill: ' . $np_icon_color . '; }';
			}

			// Feature image container
			$has_img_styles = 'position: relative;';
			if ( 'top' === $next_previous_direction ) {
				$has_img_styles .= ' padding-top: 80px !important;';
			} elseif ( 'right' === $next_previous_direction ) {
				$has_img_styles .= ' padding-right: 50px !important;';
			}
			$css_rules[] = '.aagb__group_accordion_container.has_img { ' . $has_img_styles . ' }';

			// Child enabled
			$child_styles = 'position: static; padding-top: 0 !important;';
			if ( 'right' === $next_previous_direction ) {
				$child_styles .= ' padding-right: 0 !important;';
			}
			$css_rules[] = '.aagb__group_accordion_container.has_img .aab-next-previous-enabled { ' . $child_styles . ' }';
		}

		// -----------------
		// Anchor Links Color
		// -----------------
		$step = ! empty( $attributes['step'] );
		if ( ! empty( $attributes['anchorLinksShow'] ) && $heading_color && ! $step ) {
			$css_rules[] = $base . ' .aagb__accordion_heading .anchorjs-link { color: ' . $this->sanitize_css_value( $heading_color ) . '; }';
		}

		// -----------------
		// Accessibility
		// -----------------
		$accessibility_on    = isset( $attributes['accessibilityOn'] ) ? $attributes['accessibilityOn'] : true;
		$focus_outline_color = isset( $attributes['focusOutlineColor'] ) ? $this->sanitize_css_value( $attributes['focusOutlineColor'] ) : '#000000';

		if ( $accessibility_on ) {
			$css_rules[] = $base . ' .aagb__accordion_container { transition-duration: 0ms !important; outline: 2px solid transparent; }';
			$css_rules[] = $base . ' .aagb__accordion_container:focus-visible { outline: 2px solid ' . $focus_outline_color . '; }';
		}

		return implode( "\n", $css_rules );
	}

	/**
	 * Calculate body paddings based on QaStyle and checkList.
	 *
	 * @param array $paddings   Padding values.
	 * @param bool  $qa_style   Whether QA style is enabled.
	 * @param bool  $check_list Whether checklist is enabled.
	 * @return string CSS padding value.
	 */
	private function calculate_body_paddings( $paddings, $qa_style, $check_list ) {
		$top    = $this->sanitize_css_value( $paddings['top'] ?? '' );
		$right  = $this->sanitize_css_value( $paddings['right'] ?? '' );
		$bottom = $this->sanitize_css_value( $paddings['bottom'] ?? '' );
		$left   = $this->sanitize_css_value( $paddings['left'] ?? '15px' );

		if ( $qa_style && $check_list ) {
			return '0 ' . $right . ' ' . $bottom . ' calc(' . $left . ' + 140px)';
		}

		if ( $qa_style ) {
			return '0 ' . $right . ' ' . $bottom . ' calc(' . $left . ' + 90px)';
		}

		if ( $check_list ) {
			$left_calc = $left ? 'calc(' . $left . ' + 10px)' : '10px';
			return $top . ' ' . $right . ' ' . $bottom . ' ' . $left_calc;
		}

		return $top . ' ' . $right . ' ' . $bottom . ' ' . $left;
	}

	/**
	 * Generate border CSS from border object.
	 *
	 * @param array $border Border object with sides or shorthand.
	 * @return string CSS border declarations.
	 */
	private function get_border_css( $border ) {
		if ( empty( $border ) ) {
			return '';
		}

		// Shorthand border
		if ( isset( $border['width'] ) && isset( $border['style'] ) && isset( $border['color'] ) ) {
			return 'border: ' . $this->sanitize_css_value( $border['width'] ) . ' '
				. $this->sanitize_css_value( $border['style'] ) . ' '
				. $this->sanitize_css_value( $border['color'] );
		}

		// Individual sides
		$sides  = [ 'top', 'right', 'bottom', 'left' ];
		$result = [];

		foreach ( $sides as $side ) {
			if ( isset( $border[ $side ] ) && is_array( $border[ $side ] ) ) {
				$s = $border[ $side ];
				if ( ! empty( $s['width'] ) ) {
					$result[] = 'border-' . $side . ': ' . $this->sanitize_css_value( $s['width'] ?? '' ) . ' '
						. $this->sanitize_css_value( $s['style'] ?? 'solid' ) . ' '
						. $this->sanitize_css_value( $s['color'] ?? '' );
				}
			}
		}

		return implode( '; ', $result );
	}

	/**
	 * Horizontal accordion render callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_horizontal_accordion( $attributes, $content ) {
		wp_register_style( 'aahb-horizontal-accordion', AAGB_URL . 'build/horizontal-accordion/style-index.css' );
		return $content;
	}

	/**
	 * Horizontal accordion item render callback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Block content.
	 */
	public function render_horizontal_accordion_item( $attributes, $content ) {
		wp_register_style( 'aahb-horizontal-accordion-item', AAGB_URL . 'build/horizontal-accordion-item/style-index.css' );
		return $content;
	}
}
