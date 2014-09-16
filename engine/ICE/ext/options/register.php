<?php
/**
 * ICE API: bundled option extensions loader.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage options
 * @since 1.2
 */

ice_register_extension(
	'option',
	'categories',
	array(
		'class' => 'ICE_Ext_Option_Categories',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'category',
	array(
		'class' => 'ICE_Ext_Option_Category',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'checkbox',
	array(
		'extends' => 'input-group',
		'class' => 'ICE_Ext_Option_Checkbox'
	)
);

ice_register_extension(
	'option',
	'colorpicker',
	array(
		'extends' => 'text',
		'class' => 'ICE_Ext_Option_Colorpicker',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'css/bg-color',
	array(
		'extends' => 'colorpicker',
		'class' => 'ICE_Ext_Option_Css_Bg_Color'
	)
);

ice_register_extension(
	'option',
	'css/bg-image',
	array(
		'extends' => 'upload',
		'class' => 'ICE_Ext_Option_Css_Bg_Image'
	)
);

ice_register_extension(
	'option',
	'css/bg-repeat',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Css_Bg_Repeat'
	)
);

ice_register_extension(
	'option',
	'css/border-color',
	array(
		'extends' => 'colorpicker',
		'class' => 'ICE_Ext_Option_Css_Border_Color'
	)
);

ice_register_extension(
	'option',
	'css/border-style',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Css_Border_Style'
	)
);

ice_register_extension(
	'option',
	'css/border-width',
	array(
		'extends' => 'css/length-px',
		'class' => 'ICE_Ext_Option_Css_Border_Width'
	)
);

ice_register_extension(
	'option',
	'css/custom',
	array(
		'extends' => 'textarea',
		'class' => 'ICE_Ext_Option_Css_Custom'
	)
);

ice_register_extension(
	'option',
	'css/length-px',
	array(
		'extends' => 'ui/slider',
		'class' => 'ICE_Ext_Option_Css_Length_Px'
	)
);

ice_register_extension(
	'option',
	'css/overlay-image',
	array(
		'extends' => 'ui/overlay-picker',
		'class' => 'ICE_Ext_Option_Css_Overlay_Image'
	)
);

ice_register_extension(
	'option',
	'css/overlay-opacity',
	array(
		'extends' => 'ui/slider',
		'class' => 'ICE_Ext_Option_Css_Overlay_Opacity'
	)
);

ice_register_extension(
	'option',
	'input',
	array(
		'class' => 'ICE_Ext_Option_Input',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'input-group',
	array(
		'extends' => 'input',
		'class' => 'ICE_Ext_Option_Input_Group',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'page',
	array(
		'class' => 'ICE_Ext_Option_Page',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'pages',
	array(
		'class' => 'ICE_Ext_Option_Pages',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'plugins/domain-mapping',
	array(
		'extends' => 'text',
		'class' => 'ICE_Ext_Option_Plugins_Domain_Mapping'
	)
);

ice_register_extension(
	'option',
	'position/left-center-right',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Position_Left_Center_Right'
	)
);

ice_register_extension(
	'option',
	'position/left-right',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Position_Left_Right'
	)
);

ice_register_extension(
	'option',
	'position/top-bottom',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Position_Top_Bottom'
	)
);

ice_register_extension(
	'option',
	'post',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Post'
	)
);

ice_register_extension(
	'option',
	'posts',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Posts'
	)
);

ice_register_extension(
	'option',
	'radio',
	array(
		'extends' => 'input-group',
		'class' => 'ICE_Ext_Option_Radio'
	)
);

ice_register_extension(
	'option',
	'select',
	array(
		'class' => 'ICE_Ext_Option_Select',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'tag',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Tag'
	)
);

ice_register_extension(
	'option',
	'tags',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Tags'
	)
);

ice_register_extension(
	'option',
	'text',
	array(
		'extends' => 'input',
		'class' => 'ICE_Ext_Option_Text'
	)
);

ice_register_extension(
	'option',
	'textarea',
	array(
		'class' => 'ICE_Ext_Option_Textarea',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'toggle/disable',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Disable'
	)
);

ice_register_extension(
	'option',
	'toggle/enable',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Enable'
	)
);

ice_register_extension(
	'option',
	'toggle/enable-disable',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Toggle_Enable_Disable'
	)
);

ice_register_extension(
	'option',
	'toggle/no',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_No'
	)
);

ice_register_extension(
	'option',
	'toggle/off',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Off'
	)
);

ice_register_extension(
	'option',
	'toggle/on',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_On'
	)
);

ice_register_extension(
	'option',
	'toggle/on-off',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Toggle_On_Off'
	)
);

ice_register_extension(
	'option',
	'toggle/yes',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Yes'
	)
);

ice_register_extension(
	'option',
	'toggle/yes-no',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Toggle_Yes_No'
	)
);

ice_register_extension(
	'option',
	'ui/font-picker',
	array(
		'extends' => 'ui/scroll-picker',
		'class' => 'ICE_Ext_Option_Ui_Font_Picker',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'ui/image-picker',
	array(
		'extends' => 'ui/scroll-picker',
		'class' => 'ICE_Ext_Option_Ui_Image_Picker'
	)
);

ice_register_extension(
	'option',
	'ui/overlay-picker',
	array(
		'extends' => 'ui/image-picker',
		'class' => 'ICE_Ext_Option_Ui_Overlay_Picker'
	)
);

ice_register_extension(
	'option',
	'ui/scroll-picker',
	array(
		'class' => 'ICE_Ext_Option_Ui_Scroll_Picker',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'ui/slider',
	array(
		'class' => 'ICE_Ext_Option_Ui_Slider',
		'template' => true
	)
);

ice_register_extension(
	'option',
	'upload',
	array(
		'class' => 'ICE_Ext_Option_Upload',
		'template' => true
	)
);