<?php
/**
 * ICE API: bundled widget extensions loader.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage widgets
 * @since 1.2
 */

ice_register_extension(
	'widget',
	'default',
	array(
		'class' => 'ICE_Ext_Widget_Default'
	)
);

ice_register_extension(
	'widget',
	'menu',
	array(
		'class' => 'ICE_Ext_Widget_Menu',
		'template' => true
	)
);

ice_register_extension(
	'widget',
	'posts-list',
	array(
		'class' => 'ICE_Ext_Widget_Posts_List',
		'template' => true
	)
);

ice_register_extension(
	'widget',
	'title-block',
	array(
		'class' => 'ICE_Ext_Widget_Title_Block'
	)
);