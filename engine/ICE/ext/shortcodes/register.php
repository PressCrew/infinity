<?php
/**
 * ICE API: bundled shortcode extensions loader.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage shortcodes
 * @since 1.2
 */

ice_register_extension(
	'shortcode',
	'access',
	array(
		'class' => 'ICE_Ext_Shortcode_Access',
		'template' => true
	)
);

ice_register_extension(
	'shortcode',
	'visitor',
	array(
		'class' => 'ICE_Ext_Shortcode_Visitor',
		'template' => true
	)
);