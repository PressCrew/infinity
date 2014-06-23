<?php
/**
 * ICE API: bundled feature extensions loader.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage features
 * @since 1.2
 */

// BuddyPress FaceBook Autoconnect
$this->register(
	'bp/fb-autoconnect',
	array(
		'class' => 'ICE_Ext_Feature_Bp_Fb_Autoconnect',
		'template' => self::DEFAULT_TPL
	)
);

// BuddyPress Plugin Support
$this->register(
	'bp/support',
	array(
		'class' => 'ICE_Ext_Feature_Bp_Support'
	)
);

// Default (generic feature)
$this->register(
	'default',
	array(
		'class' => 'ICE_Ext_Feature_Default'
	)
);

// Echo
$this->register(
	'echo',
	array(
		'class' => 'ICE_Ext_Feature_Echo',
		'template' => self::DEFAULT_TPL
	)
);

// Gravatar
$this->register(
	'gravatar',
	array(
		'class' => 'ICE_Ext_Feature_Gravatar',
		'template' => self::DEFAULT_TPL
	)
);

// Header Logo
$this->register(
	'header-logo',
	array(
		'class' => 'ICE_Ext_Feature_Header_Logo',
		'template' => self::DEFAULT_TPL
	)
);

// Responsive Layout
$this->register(
	'responsive/layout',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Layout',
		'template' => self::DEFAULT_TPL
	)
);

// Responsive Menu
$this->register(
	'responsive/menu',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Menu',
		'template' => self::DEFAULT_TPL
	)
);

// Responsive Video
$this->register(
	'responsive/videos',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Videos',
		'template' => self::DEFAULT_TPL
	)
);
