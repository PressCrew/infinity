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

$this->register(
	'access',
	array(
		'class' => 'ICE_Ext_Shortcode_Access',
		'template' => true
	)
);

$this->register(
	'visitor',
	array(
		'class' => 'ICE_Ext_Shortcode_Visitor',
		'template' => true
	)
);