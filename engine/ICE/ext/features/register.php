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
		'template' => true,
		'options' => array(
			'toggle' => array(
				'type' => 'toggle/yes-no',
				'title' => 'FaceBook Connect',
				'description' => 'Show connect with Facebook button?',
				'default_value' => true
			)
		)
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
		'template' => true
	)
);

// Gravatar
$this->register(
	'echo',
	array(
		'class' => 'ICE_Ext_Feature_Gravatar',
		'template' => true
	)
);

// Header Logo
$this->register(
	'header-logo',
	array(
		'class' => 'ICE_Ext_Feature_Header_Logo',
		'template' => true,
		'style' => 'logo.css',
		'options' => array(
			'image' => array(
				'type' => 'upload',
				'title' => 'Logo Image',
				'description' => 'Upload a custom logo to appear in the header'
			),
			'toggle' => array(
				'type' => 'toggle/on-off',
				'title' => 'Logo Image On/Off',
				'description' => 'Turn this Off to prevent any image logo from displaying, even the default.',
				'default_value' => true,
				'parent' => '%feature%.image'
			),
			'pos' => array(
				'type' => 'position/left-center-right',
				'title' => 'Logo Position',
				'description' => 'Select a position for the logo',
				'default_value' => 'l',
				'parent' => '%feature%.image',
			),
			'top' => array(
				'type' => 'ui/slider',
				'title' => 'Logo Top Spacing',
				'description' => 'Enter a height in pixels for top spacing',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'suffix' => ' pixels',
				'style_selector' => '.icext-feature.icext-header-logo a',
				'style_property' => 'margin-top',
				'style_unit' => 'px',
				'parent' => '%feature%.image',
			),
			'left' => array(
				'type' => 'ui/slider',
				'title' => 'Logo Left Spacing',
				'description' => 'Enter a width in pixels for left spacing',
				'min' => 0,
				'max' => 250,
				'step' => 1,
				'suffix' => ' pixels',
				'style_selector' => '.icext-feature.icext-header-logo a',
				'style_property' => 'margin-left',
				'style_unit' => 'px',
				'parent' => '%feature%.image',
			),
			'right' => array(
				'type' => 'ui/slider',
				'title' => 'Logo Right Spacing',
				'description' => 'Enter a width in pixels for right spacing',
				'min' => 0,
				'max' => 250,
				'step' => 1,
				'suffix' => ' pixels',
				'style_selector' => '.icext-feature.icext-header-logo a',
				'style_property' => 'margin-right',
				'style_unit' => 'px',
				'parent' => '%feature%.image',
			)
		)
	)
);

// Responsive Layout
$this->register(
	'responsive/layout',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Layout',
		'template' => true
	)
);

// Responsive Menu
$this->register(
	'responsive/menu',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Menu',
		'template' => true
	)
);

// Responsive Video
$this->register(
	'responsive/videos',
	array(
		'class' => 'ICE_Ext_Feature_Responsive_Videos',
		'template' => true
	)
);
