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
ice_register_extension(
	'feature',
	'bp/fb-autoconnect',
	array(
		'class' => 'ICE_Ext_Feature_Bp_Fb_Autoconnect',
		'template' => true
	)
);

// BuddyPress Protect
ice_register_extension(
	'feature',
	'bp/protect',
	array(
		'class' => 'ICE_Ext_Feature_Bp_Protect'
	)
);

// BuddyPress Plugin Support
ice_register_extension(
	'feature',
	'bp/support',
	array(
		'class' => 'ICE_Ext_Feature_Bp_Support'
	)
);

// Default (generic feature)
ice_register_extension(
	'feature',
	'default',
	array(
		'class' => 'ICE_Ext_Feature_Default'
	)
);

// Echo
ice_register_extension(
	'feature',
	'echo',
	array(
		'class' => 'ICE_Ext_Feature_Echo',
		'template' => true
	)
);

// Gravatar
ice_register_extension(
	'feature',
	'gravatar',
	array(
		'class' => 'ICE_Ext_Feature_Gravatar',
		'template' => true
	)
);

// Header Logo
ice_register_extension(
	'feature',
	'header-logo',
	array(
		'class' => 'ICE_Ext_Feature_Header_Logo',
		'template' => true
	)
);

// Responsive Layout
ice_register_extension(
	'feature',
	'responsive/layout',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Layout',
		'template' => true
	)
);

// Responsive Menu
ice_register_extension(
	'feature',
	'responsive/menu',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Menu',
		'template' => true
	)
);

// Responsive Video
ice_register_extension(
	'feature',
	'responsive/videos',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Videos',
		'template' => true
	)
);

// Joyride Script
ice_register_extension(
	'feature',
	'scripts/joyride',
	array(
		'class' => 'ICE_Ext_Feature_Scripts_Joyride',
		'template' => true
	)
);

// BuddyPress Joyride Tour
ice_register_extension(
	'feature',
	'bp/tour',
	array(
		'extends' => 'scripts/joyride',
		'class' => 'ICE_Ext_Feature_Bp_Tour'
	)
);
