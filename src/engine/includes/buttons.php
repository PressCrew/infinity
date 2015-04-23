<?php
/**
 * Infinity Theme: buttons
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson & CUNY Academic Commons
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

/**
 * Return an array of selectors to apply button styling to.
 *
 * @staticvar array Cached selectors.
 * @param string $pseudo_class Optional pseudo class to append to each selector.
 * @return array
 */
function infinity_button_selectors( $pseudo_class = null )
{
	// cache filter results for performance
	static $selectors = null;

	// filter selectors yet?
	if ( null === $selectors ) {
		// yep, pass all selectors through filter
		$selectors = apply_filters(
			'infinity_button_selectors',
			array(
				'button-link'
					=> '.infinity-btns a.button',
				'more-link'
					=> '.infinity-btns a.more-link',
				'post-tag-link'
					=> '.infinity-btns .post-tags a',
				'widget-tag-cloud-link'
					=> '.infinity-btns .widget_tag_cloud a',
				'input-submit'
					=> '.infinity-btns *[type="submit"]',
				'input-reset'
					=> '.infinity-btns *[type="reset"]',
				'bp-gen-button'
					=> '.infinity-btns.infinity-bp .generic-button',
				'bp-legacy-button-link'
					=> '.infinity-btns.infinity-bp #buddypress a.button',
				'bp-legacy-gen-button'
					=> '.infinity-btns.infinity-bp #buddypress .generic-button',
				'bp-legacy-input-submit'
					=> '.infinity-btns.infinity-bp #buddypress *[type="submit"]',
				'bp-legacy-input-reset'
					=> '.infinity-btns.infinity-bp #buddypress *[type="reset"]',
			)
		);
	}

	// did we get a pseudo class?
	if ( false === empty( $pseudo_class ) ) {
		// yes, loop all selectors
		foreach ( $selectors as &$selector ) {
			// append the pseudo class
			$selector .= ':' . $pseudo_class;
		}
	}

	// return selectors
	return $selectors;
}

/**
 * Return a button theme.
 *
 * @staticvar array $themes Cached themes.
 * @param string $theme The button theme to return.
 * @return array|false
 */
function infinity_button_themes( $theme = 'default' )
{
	// cache filtered schemes
	static $themes = null;

	// set yet?
	if ( null === $themes ) {
		// pass all selectors through filter
		$themes = apply_filters(
			'infinity_button_themes',
			array(
				'grey' => array(
					'default' => array(
						'color' => '#555',
						'background' => '#bdbdbd',
						'gradient' => array( 'f' => '#cacaca', 't' => '#aeaeae' ),
						'border-color' => array( '#b5b5b5', '#a1a1a1', '#8f8f8f' ),
						'text-shadow' => array(
							array( 'c' => '#d4d4d4')
						),
						'box-shadow' => array(
							array( 'c' => '#c9c9c9' ),
							array( 'c' => '#d7d7d7', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#444',
						'background' => '#c2c2c2',
						'gradient' => array( 'f' => '#bcbcbc', 't' => '#c2c2c2' ),
						'border-color' => array( '#989898', '#8e8e8e', '#878787' ),
						'text-shadow' => array(
							array( 'c' => '#dadada')
						),
						'box-shadow' => array(
							array( 'c' => '#cdcdcd' ),
							array( 'c' => '#ccc', 'p' => 'inset' )
						)
					)
				),
				'pink' => array(
					'default' => array(
						'color' => '#913944',
						'background' => '#f67689',
						'gradient' => array( 'f' => '#f78297', 't' => '#f56778' ),
						'border-color' => array( '#df6f8b', '#da5f75', '#d55061' ),
						'text-shadow' => array(
							array( 'c' => '#f89ca9')
						),
						'box-shadow' => array(
							array( 'c' => '#c1c1c1' ),
							array( 'c' => '#f9a1b1', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#913944',
						'background' => '#f67c90',
						'gradient' => array( 'f' => '#f56c7e', 't' => '#f78297' ),
						'border-color' => array( '#c36079', '#c25669', '#c14e5c' ),
						'text-shadow' => array(
							array( 'c' => '#f9a6b4')
						),
						'box-shadow' => array(
							array( 'c' => '#c3c3c3' ),
							array( 'c' => '#f8909e', 'p' => 'inset' )
						)
					)
				),
				'orange' => array(
					'default' => array(
						'color' => '#996633',
						'background' => '#fecc5f',
						'gradient' => array( 'f' => '#feda71', 't' => '#febb4a' ),
						'border-color' => array( '#f5b74e', '#e5a73e', '#d6982f' ),
						'text-shadow' => array(
							array( 'c' => '#fedd9b')
						),
						'box-shadow' => array(
							array( 'c' => '#d4d4d4' ),
							array( 'c' => '#fed17e', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#996633',
						'background' => '#fecb5e',
						'gradient' => array( 'f' => '#fec354', 't' => '#fecd61' ),
						'border-color' => array( '#d29a3a', '#cc9436', '#c89133' ),
						'text-shadow' => array(
							array( 'c' => '#fee1a0')
						),
						'box-shadow' => array(
							array( 'c' => '#d4d4d4' ),
							array( 'c' => '#fed17e', 'p' => 'inset' )
						)
					),
				),
				'green' => array(
					'default' => array(
						'color' => '#5d7731',
						'background' => '#b7d770',
						'gradient' => array( 'f' => '#cae285', 't' => '#9fcb57' ),
						'border-color' => array( '#adc671', '#98b65b', '#87aa4a' ),
						'text-shadow' => array(
							array( 'c' => '#cfe5a4')
						),
						'box-shadow' => array(
							array( 'c' => '#d3d3d3' ),
							array( 'c' => '#d7e9a4', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#5d7731',
						'background' => '#b9d972',
						'gradient' => array( 'f' => '#b8d872', 't' => '#b9d972' ),
						'border-color' => array( '#8bb14d', '#83a648', '#7d9e45' ),
						'text-shadow' => array(
							array( 'c' => '#d5e8aa')
						),
						'box-shadow' => array(
							array( 'c' => '#d5d5d5' ),
							array( 'c' => '#cae295', 'p' => 'inset' )
						)
					),
				),
				'blue' => array(
					'default' => array(
						'color' => '#42788e',
						'background' => '#92dbf6',
						'gradient' => array( 'f' => '#abe4f8', 't' => '#6fcef3' ),
						'border-color' => array( '#8dc5da', '#76b7cf', '#63abc7' ),
						'text-shadow' => array(
							array( 'c' => '#b6e6f9')
						),
						'box-shadow' => array(
							array( 'c' => '#d6d6d6' ),
							array( 'c' => '#c0ebfa', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#42788e',
						'background' => '#92dbf6',
						'border-color' => array( '#7caec0', '#68a3ba', '#5a9cb5' ),
						'text-shadow' => array(
							array( 'c' => '#bee9fa')
						),
						'box-shadow' => array(
							array( 'c' => '#d6d6d6' ),
							array( 'c' => '#ade4f8', 'p' => 'inset' )
						)
					),
				),
				'purple' => array(
					'default' => array(
						'color' => '#7b5777',
						'background' => '#dfaeda',
						'gradient' => array( 'f' => '#e8c4e4', 't' => '#d494ce' ),
						'border-color' => array( '#bc9db9', '#ad89aa', '#a1799d' ),
						'text-shadow' => array(
							array( 'c' => '#eacae6' )
						),
						'box-shadow' => array(
							array( 'c' => '#d5d5d5' ),
							array( 'c' => '#eed3eb', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#7b5777',
						'background' => '#e0b1db',
						'gradient' => array( 'f' => '#deabd9', 't' => '#e0b1db' ),
						'border-color' => array( '#a482a0', '#9b7897', '#947090' ),
						'text-shadow' => array(
							array( 'c' => '#ecd0e9' )
						),
						'box-shadow' => array(
							array( 'c' => '#cdcdcd' ),
							array( 'c' => '#ccc', 'p' => 'inset' )
						)
					),
				),
				'teal' => array(
					'default' => array(
						'color' => '#437b7d',
						'background' => '#9cedef',
						'gradient' => array( 'f' => '#b7f2f4', 't' => '#7ce7ea' ),
						'border-color' => array( '#90c6c8', '#78bdc0', '#65b6ba' ),
						'text-shadow' => array(
							array( 'c' => '#bef3f5')
						),
						'box-shadow' => array(
							array( 'c' => '#d5d5d5' ),
							array( 'c' => '#c9f5f7', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#437b7d',
						'background' => '#9fedf0',
						'border-color' => array( '#7db9bb', '#6bb2b5', '#5dacaf' ),
						'text-shadow' => array(
							array( 'c' => '#c5f4f6')
						),
						'box-shadow' => array(
							array( 'c' => '#d5d5d5' ),
							array( 'c' => '#b7f2f4', 'p' => 'inset' )
						)
					),
				),
				'darkblue' => array(
					'default' => array(
						'color' => '#515f6a',
						'background' => '#a5b8c6',
						'gradient' => array( 'f' => '#becbd6', 't' => '#88a1b4' ),
						'border-color' => array( '#a2afb8', '#8696a1', '#6f818f' ),
						'text-shadow' => array(
							array( 'c' => '#c4d0d9')
						),
						'box-shadow' => array(
							array( 'c' => '#d3d3d3' ),
							array( 'c' => '#ced8e0', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#515f6a',
						'background' => '#adbfcb',
						'border-color' => array( '#8996a0', '#798791', '#6c7a85' ),
						'text-shadow' => array(
							array( 'c' => '#ced9e0')
						),
						'box-shadow' => array(
							array( 'c' => '#d3d3d3' ),
							array( 'c' => '#c2cfd8', 'p' => 'inset' )
						)
					),
				),
				'white' => array(
					'default' => array(
						'color' => '#555',
						'background' => '#f5f5f5',
						'gradient' => array( 'f' => '#f9f9f9', 't' => '#f0f0f0' ),
						'border-color' => array( '#dedede', '#d8d8d8', '#d3d3d3' ),
						'text-shadow' => array(
							array( 'c' => '#fff')
						),
						'box-shadow' => array(
							array( 'c' => '#eaeaea' ),
							array( 'c' => '#fbfbfb', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#555',
						'background' => '#f4f4f4',
						'gradient' => array( 'f' => '#efefef', 't' => '#f8f8f8' ),
						'border-color' => array( '#c7c7c7', '#c3c3c3', '#bebebe' ),
						'text-shadow' => array(
							array( 'c' => '#fdfdfd')
						),
						'box-shadow' => array(
							array( 'c' => '#ebebeb' ),
							array( 'c' => '#f3f3f3', 'p' => 'inset' )
						)
					)
				),
				'black' => array(
					'default' => array(
						'color' => '#fff',
						'background' => '#525252',
						'gradient' => array( 'f' => '#5e5e5e', 't' => '#434343' ),
						'border-color' => array( '#4c4c4c', '#313131', '#1f1f1f' ),
						'text-shadow' => array(
							array( 'c' => '#2e2e2e')
						),
						'box-shadow' => array(
							array( 'c' => '#afafaf' ),
							array( 'c' => '#868686', 'p' => 'inset' )
						)
					),
					'hover' => array(
						'color' => '#ddd',
						'background' => '#5a5a5a',
						'border-color' => array( '#2c2c2c', '#1c1c1c', '#101010' ),
						'text-shadow' => array(
							array( 'c' => '#363636')
						),
						'box-shadow' => array(
							array( 'c' => '#b1b1b1' ),
							array( 'c' => '#838383', 'p' => 'inset' )
						)
					)
				)
			)
		);
	}

	// is theme set?
	if ( true === isset( $themes[$theme] ) ) {
		// return theme
		return $themes[ $theme ];
	}

	// theme not found
	return false;
}

/**
 * Get formatted button colors for given theme and mouse state.
 *
 * @param string $theme
 * @param string $state
 * @return array|false
 */
function infinity_button_colors( $theme = 'default', $state = 'default' )
{
	// generic defaults
	$defaults = array(
		'color' => 'inherit',
		'background' => 'inherit',
		'border-color' => 'inherit',
		'gradient' => false,
		'text-shadow' => false,
		'box-shadow' => false
	);

	// gradient defaults
	$gr_defaults = array(
		'f' => 'white',
		't' => 'black'
	);

	// text shadow defaults
	$ts_defaults = array(
		'h' => '0',
		'v' => '1px',
		'b' => '0',
		'c' => 'gray'
	);

	// box shadow defaults
	$bs_defaults = array(
		'p' => '',
		'h' => '0',
		'v' => '1px',
		'b' => '1px',
		's' => '0',
		'c' => 'gray'
	);

	// grab the theme
	$theme_states = infinity_button_themes( $theme );

	// do we have that theme?
	if ( true === isset( $theme_states[ $state ] ) ) {

		// yep, get settings and apply defaults
		$settings = wp_parse_args( $theme_states[ $state ], $defaults );

		// is border color set?
		if ( false === empty( $settings['border-color'] ) ) {
			// is it an array?
			if ( true === is_array( $settings['border-color'] ) ) {
				// yep, join with spaces
				$settings['border-color'] = implode( ' ', $settings['border-color'] );
			}
		}

		// have gradient?
		if (
			true === isset( $settings['gradient'] ) &&
			true === is_array( $settings['gradient'] )
		) {
			// yes, apply gradient defaults
			$settings['gradient'] = wp_parse_args( $settings['gradient'], $gr_defaults );
		} else {
			// force to false
			$settings['gradient'] = false;
		}

		// have text shadows?
		if (
			true === isset( $settings['text-shadow'] ) &&
			true === is_array( $settings['text-shadow'] )
		) {
			// final text shadows
			$text_shadows = array();
			// loop all text shadow settings
			foreach( $settings['text-shadow'] as $ts ) {
				// apply text shadow defaults
				$ts = wp_parse_args( $ts, $ts_defaults );
				// append formatted string to final array
				$text_shadows[] = "{$ts['h']} {$ts['v']} {$ts['b']} {$ts['c']}";
			}
			// format final declaration
			$settings['text-shadow'] = implode( ', ', $text_shadows );
		} else {
			// force to false
			$settings['text-shadow'] = false;
		}

		// have box shadows?
		if (
			true === isset( $settings['box-shadow'] ) &&
			true === is_array( $settings['box-shadow'] )
		) {
			// final box shadows
			$box_shadows = array();
			// loop all box shadow settings
			foreach( $settings['box-shadow'] as $bs ) {
				// apply box shadow defaults
				$bs = wp_parse_args( $bs, $bs_defaults );
				// append formatted string to final array
				$box_shadows[] = "{$bs['p']} {$bs['h']} {$bs['v']} {$bs['b']} {$bs['s']} {$bs['c']}";
			}
			// format final declaration
			$settings['box-shadow'] = implode( ', ', $box_shadows );
		} else {
			// force to false
			$settings['box-shadow'] = false;
		}

		// yes, return it
		return $settings;
	}

	// return false by default
	return false;
}

/**
 * Inject button style block into document header.
 *
 * @return void
 */
function infinity_button_styles()
{
	// is custom colors enabled
	if ( current_theme_supports( 'infinity:buttons', 'color' ) ) {
		// get button color theme
		$theme = infinity_option_get( 'buttons.custom-color' );
		// did we get a color?
		if ( true === empty( $theme ) ) {
			// no color!
			return;
		}
	} else {
		// button color feature disabled, bail
		return;
	}

	// what states should we loop through?
	$states = array( 'default', 'active', 'hover' );

	// render button styles ?>
	<style>
<?php
	foreach( $states as $state ):
		// try to get colors for theme/state
		$colors = infinity_button_colors( $theme, $state );
		// did we get an array?
		if ( true === is_array( $colors ) ) {
			// yes, determine pseudo class
			$pseudo_class = ( 'default' === $state ) ? null : $state;
			// get selectors for state
			$selectors = infinity_button_selectors( $pseudo_class );
		} else {
			// not an array, go to next loop
			continue;
		}
?>
		<?php echo implode( ",\n\t\t", $selectors ); ?> {
			color: <?php echo $colors['color'] ?>;
			border-color: <?php echo $colors['border-color'] ?>;
			background-color: <?php echo $colors['background'] ?>;
<?php
			if ( true === is_array( $colors['gradient'] ) ):
				$grad_f = $colors['gradient']['f'];
				$grad_t = $colors['gradient']['t'];
?>
			background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo $grad_f ?>), to(<?php echo $grad_t ?>));
			background-image: -webkit-linear-gradient(top, <?php echo $grad_f ?>, <?php echo $grad_t ?>);
			background-image: -moz-linear-gradient(top, <?php echo $grad_f ?>, <?php echo $grad_t ?>);
			background-image: -o-linear-gradient(top, <?php echo $grad_f ?>, <?php echo $grad_t ?>);
			background-image: -ms-linear-gradient(top, <?php echo $grad_f ?>, <?php echo $grad_t ?>);
			background-image: linear-gradient(to bottom, <?php echo $grad_f ?>, <?php echo $grad_t ?>);
<?php
			endif;

			if ( true === is_string( $colors['text-shadow'] ) ):
?>
			text-shadow: <?php echo $colors['text-shadow'] ?>;
<?php
			endif;

			if ( true === is_string( $colors['box-shadow'] ) ):
?>
			box-shadow: <?php echo $colors['box-shadow'] ?>;
			-moz-box-shadow: <?php echo $colors['box-shadow'] ?>;
			-webkit-box-shadow: <?php echo $colors['box-shadow'] ?>;
<?php
			endif;

			// allow for injecting additional declarations
			do_action( 'infinity_button_styles', $theme, $state );
?>
		}
<?php
	endforeach;

		// allow for injecting additional rules
		do_action( 'infinity_button_styles', $theme, null );
?>
	</style><?php
}
add_action( 'wp_head', 'infinity_button_styles' );
