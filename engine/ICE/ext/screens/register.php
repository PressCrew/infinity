<?php
/**
 * ICE API: bundled screen extensions loader.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage screens
 * @since 1.2
 */

$this->register(
	'cpanel',
	array(
		'class' => 'ICE_Ext_Screen_Cpanel'
	)
);