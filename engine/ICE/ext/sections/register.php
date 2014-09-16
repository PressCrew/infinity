<?php
/**
 * ICE API: bundled section extensions loader.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage sections
 * @since 1.2
 */

ice_register_extension(
	'section',
	'default',
	array(
		'class' => 'ICE_Ext_Section_Default',
		'template' => true
	)
);
