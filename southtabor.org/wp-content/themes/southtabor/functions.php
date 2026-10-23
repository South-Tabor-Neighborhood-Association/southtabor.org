<?php
/**
 * South Tabor theme setup.
 *
 * Design tokens live in theme.json; component styles live in style.css.
 * This file adds block styles, the next-meeting shortcodes and the
 * "Add to calendar" link.
 *
 * @package southtabor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load style.css on the front end and in the editor.
 */
function southtabor_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'southtabor_setup' );

function southtabor_enqueue_styles() {
	wp_enqueue_style(
		'southtabor',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'southtabor_enqueue_styles' );

/**
 * Block styles editors can pick in the Styles panel.
 */
function southtabor_register_block_styles() {
	register_block_style( 'core/button', array( 'name' => 'secondary', 'label' => __( 'Secondary', 'southtabor' ) ) );
	register_block_style( 'core/button', array( 'name' => 'event', 'label' => __( 'Event', 'southtabor' ) ) );
	register_block_style( 'core/group', array( 'name' => 'meeting-card', 'label' => __( 'Meeting card', 'southtabor' ) ) );
	register_block_style( 'core/group', array( 'name' => 'panel', 'label' => __( 'Panel', 'southtabor' ) ) );
	register_block_style( 'core/group', array( 'name' => 'notice', 'label' => __( 'Notice: alert', 'southtabor' ) ) );
	register_block_style( 'core/group', array( 'name' => 'notice-info', 'label' => __( 'Notice: info', 'southtabor' ) ) );
	register_block_style( 'core/paragraph', array( 'name' => 'street', 'label' => __( 'Street label', 'southtabor' ) ) );
	register_block_style( 'core/list', array( 'name' => 'links', 'label' => __( 'Link list', 'southtabor' ) ) );
}
add_action( 'init', 'southtabor_register_block_styles' );

function southtabor_register_pattern_category() {
	register_block_pattern_category( 'southtabor', array( 'label' => __( 'South Tabor', 'southtabor' ) ) );
}
add_action( 'init', 'southtabor_register_pattern_category' );

/**
 * Board meeting schedule. Change it here, or from a plugin with the
 * `southtabor_meeting_settings` filter.
 *
 * @return array
 */
function southtabor_meeting_settings() {
	return apply_filters(
		'southtabor_meeting_settings',
		array(
			'title'      => 'STNA Board Meeting',
			'weekday'    => 4,          // ISO-8601: 4 = Thursday.
			'nth'        => 3,          // Third Thursday.
			'skip'       => array( 12 ), // No meeting in December.
			'start'      => '19:00',
			'end'        => '20:30',
			'location'   => "St. Mark's Church, 5415 SE Powell Blvd, Portland, OR 97206",
			'details'    => 'All are welcome at any STNA meeting. Enter from the north parking lot.',
		)
	);
}

/**
 * The next board meeting that hasn't ended yet.
 *
 * @return array{start: DateTimeImmutable, end: DateTimeImmutable}|null
 */
function southtabor_next_meeting() {
	$s     = southtabor_meeting_settings();
	$tz    = wp_timezone();
	$now   = new DateTimeImmutable( 'now', $tz );
	$month = $now->modify( 'first day of this month' )->setTime( 0, 0 );

	for ( $i = 0; $i < 14; $i++ ) {
		$m = $month->modify( "+{$i} months" );
		if ( in_array( (int) $m->format( 'n' ), $s['skip'], true ) ) {
			continue;
		}
		$offset = ( $s['weekday'] - (int) $m->format( 'N' ) + 7 ) % 7;
		$day    = $m->modify( '+' . ( $offset + 7 * ( $s['nth'] - 1 ) ) . ' days' );

		list( $sh, $sm ) = array_map( 'intval', explode( ':', $s['start'] ) );
		list( $eh, $em ) = array_map( 'intval', explode( ':', $s['end'] ) );
		$start = $day->setTime( $sh, $sm );
		$end   = $day->setTime( $eh, $em );

		if ( $end > $now ) {
			return array( 'start' => $start, 'end' => $end );
		}
	}
	return null;
}

/**
 * "7–8:30 p.m." in the site's house style.
 */
function southtabor_time_range( DateTimeImmutable $start, DateTimeImmutable $end ) {
	$fmt = function ( DateTimeImmutable $t ) {
		return '00' === $t->format( 'i' ) ? $t->format( 'g' ) : $t->format( 'g:i' );
	};
	$suffix = 'pm' === $end->format( 'a' ) ? 'p.m.' : 'a.m.';
	return $fmt( $start ) . '–' . $fmt( $end ) . ' ' . $suffix;
}

/**
 * [stna_next_meeting] — roundel date stamp, label, date and time.
 */
function southtabor_next_meeting_shortcode() {
	$next = southtabor_next_meeting();
	if ( ! $next ) {
		return '';
	}
	$start = $next['start'];
	ob_start();
	?>
	<div class="st-meeting__head">
		<div class="st-roundel" aria-hidden="true">
			<b><?php echo esc_html( $start->format( 'j' ) ); ?></b>
			<span><?php echo esc_html( $start->format( 'M' ) ); ?></span>
		</div>
		<div>
			<p class="is-style-street"><?php esc_html_e( 'Next board meeting', 'southtabor' ); ?></p>
			<h2 class="st-meeting__date">
				<time datetime="<?php echo esc_attr( $start->format( 'c' ) ); ?>"><?php echo esc_html( $start->format( 'l, M j' ) ); ?></time>
			</h2>
		</div>
	</div>
	<p class="st-meeting__when"><?php echo esc_html( southtabor_time_range( $start, $next['end'] ) ); ?></p>
	<?php
	return trim( ob_get_clean() );
}
add_shortcode( 'stna_next_meeting', 'southtabor_next_meeting_shortcode' );

/**
 * Google Calendar link for the next board meeting.
 */
function southtabor_calendar_url() {
	$next = southtabor_next_meeting();
	if ( ! $next ) {
		return '';
	}
	$s = southtabor_meeting_settings();
	return add_query_arg(
		array(
			'action'   => 'TEMPLATE',
			'text'     => rawurlencode( $s['title'] ),
			'dates'    => $next['start']->format( 'Ymd\THis' ) . '/' . $next['end']->format( 'Ymd\THis' ),
			'ctz'      => rawurlencode( wp_timezone_string() ),
			'location' => rawurlencode( $s['location'] ),
			'details'  => rawurlencode( $s['details'] ),
		),
		'https://calendar.google.com/calendar/render'
	);
}

/**
 * A Button block with the extra class `stna-add-to-calendar` links to the
 * next meeting, whatever URL was typed into it.
 */
function southtabor_calendar_button( $block_content ) {
	if ( false === strpos( $block_content, 'stna-add-to-calendar' ) ) {
		return $block_content;
	}
	$url = southtabor_calendar_url();
	if ( ! $url ) {
		return '';
	}
	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'href', esc_url_raw( $url ) );
		$tags->set_attribute( 'target', '_blank' );
		$tags->set_attribute( 'rel', 'noopener' );
	}
	return $tags->get_updated_html();
}
add_filter( 'render_block_core/button', 'southtabor_calendar_button' );

/**
 * Block templates run shortcodes before patterns and template parts are
 * expanded, so a Shortcode block inside one would print its raw text.
 * Run shortcodes when the Shortcode block itself renders.
 */
function southtabor_render_shortcode_block( $block_content ) {
	return do_shortcode( $block_content );
}
add_filter( 'render_block_core/shortcode', 'southtabor_render_shortcode_block' );

/**
 * [stna_copyright] — footer fine print with the current year.
 */
function southtabor_copyright_shortcode() {
	return sprintf(
		'<p class="st-footer__fine">Copyright © 2013–%s South Tabor Neighborhood Association. A member of SE Uplift Neighborhood Coalition.</p>',
		esc_html( wp_date( 'Y' ) )
	);
}
add_shortcode( 'stna_copyright', 'southtabor_copyright_shortcode' );
