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

$this->register(
	'default',
	array(
		'class' => 'ICE_Ext_Widget_Default'
	)
);

$this->register(
	'menu',
	array(
		'class' => 'ICE_Ext_Widget_Menu',
		'template' => self::DEFAULT_TPL
	)
);

$this->register(
	'posts-list',
	array(
		'class' => 'ICE_Ext_Widget_Posts_List',
		'template' => self::DEFAULT_TPL
	)
);

$this->register(
	'title-block',
	array(
		'class' => 'ICE_Ext_Widget_Title_Block'
	)
);