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

$this->register(
	'categories',
	array(
		'class' => 'ICE_Ext_Option_Categories',
		'template' => true
	)
);

$this->register(
	'category',
	array(
		'class' => 'ICE_Ext_Option_Category',
		'template' => true
	)
);

$this->register(
	'checkbox',
	array(
		'extends' => 'input-group',
		'class' => 'ICE_Ext_Option_Checkbox'
	)
);

$this->register(
	'colorpicker',
	array(
		'extends' => 'text',
		'class' => 'ICE_Ext_Option_Colorpicker',
		'template' => true
	)
);

$this->register(
	'css/bg-color',
	array(
		'extends' => 'colorpicker',
		'class' => 'ICE_Ext_Option_Css_Bg_Color'
	)
);

$this->register(
	'css/bg-image',
	array(
		'extends' => 'upload',
		'class' => 'ICE_Ext_Option_Css_Bg_Image'
	)
);

$this->register(
	'css/bg-repeat',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Css_Bg_Repeat'
	)
);

$this->register(
	'css/border-color',
	array(
		'extends' => 'colorpicker',
		'class' => 'ICE_Ext_Option_Css_Border_Color'
	)
);

$this->register(
	'css/border-style',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Css_Border_Style'
	)
);

$this->register(
	'css/border-width',
	array(
		'extends' => 'css/length-px',
		'class' => 'ICE_Ext_Option_Css_Border_Width'
	)
);

$this->register(
	'css/custom',
	array(
		'extends' => 'textarea',
		'class' => 'ICE_Ext_Option_Css_Custom'
	)
);

$this->register(
	'css/length-px',
	array(
		'extends' => 'ui/slider',
		'class' => 'ICE_Ext_Option_Css_Length_Px'
	)
);

$this->register(
	'css/overlay-image',
	array(
		'extends' => 'ui/overlay-picker',
		'class' => 'ICE_Ext_Option_Css_Overlay_Image'
	)
);

$this->register(
	'css/overlay-opacity',
	array(
		'extends' => 'ui/slider',
		'class' => 'ICE_Ext_Option_Css_Overlay_Opacity'
	)
);

$this->register(
	'input',
	array(
		'class' => 'ICE_Ext_Option_Input',
		'template' => true
	)
);

$this->register(
	'input-group',
	array(
		'extends' => 'input',
		'class' => 'ICE_Ext_Option_Input_Group',
		'template' => true
	)
);

$this->register(
	'page',
	array(
		'class' => 'ICE_Ext_Option_Page',
		'template' => true
	)
);

$this->register(
	'pages',
	array(
		'class' => 'ICE_Ext_Option_Pages',
		'template' => true
	)
);

$this->register(
	'plugins/domain-mapping',
	array(
		'extends' => 'text',
		'class' => 'ICE_Ext_Option_Plugins_Domain_Mapping'
	)
);

$this->register(
	'position/left-center-right',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Position_Left_Center_Right'
	)
);

$this->register(
	'position/left-right',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Position_Left_Right'
	)
);

$this->register(
	'position/top-bottom',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Position_Top_Bottom'
	)
);

$this->register(
	'post',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Post'
	)
);

$this->register(
	'posts',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Posts'
	)
);

$this->register(
	'radio',
	array(
		'extends' => 'input-group',
		'class' => 'ICE_Ext_Option_Radio'
	)
);

$this->register(
	'select',
	array(
		'class' => 'ICE_Ext_Option_Select',
		'template' => true
	)
);

$this->register(
	'tag',
	array(
		'extends' => 'select',
		'class' => 'ICE_Ext_Option_Tag'
	)
);

$this->register(
	'tags',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Tags'
	)
);

$this->register(
	'text',
	array(
		'extends' => 'input',
		'class' => 'ICE_Ext_Option_Text'
	)
);

$this->register(
	'textarea',
	array(
		'class' => 'ICE_Ext_Option_Textarea',
		'template' => true
	)
);

$this->register(
	'toggle/disable',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Disable'
	)
);

$this->register(
	'toggle/enable',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Enable'
	)
);

$this->register(
	'toggle/enable-disable',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Toggle_Enable_Disable'
	)
);

$this->register(
	'toggle/no',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_No'
	)
);

$this->register(
	'toggle/off',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Off'
	)
);

$this->register(
	'toggle/on',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_On'
	)
);

$this->register(
	'toggle/on-off',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Toggle_On_Off'
	)
);

$this->register(
	'toggle/yes',
	array(
		'extends' => 'checkbox',
		'class' => 'ICE_Ext_Option_Toggle_Yes'
	)
);

$this->register(
	'toggle/yes-no',
	array(
		'extends' => 'radio',
		'class' => 'ICE_Ext_Option_Toggle_Yes_No'
	)
);

$this->register(
	'ui/font-picker',
	array(
		'extends' => 'ui/scroll-picker',
		'class' => 'ICE_Ext_Option_Ui_Font_Picker',
		'template' => true
	)
);

$this->register(
	'ui/image-picker',
	array(
		'extends' => 'ui/scroll-picker',
		'class' => 'ICE_Ext_Option_Ui_Image_Picker'
	)
);

$this->register(
	'ui/overlay-picker',
	array(
		'extends' => 'ui/image-picker',
		'class' => 'ICE_Ext_Option_Ui_Overlay_Picker'
	)
);

$this->register(
	'ui/scroll-picker',
	array(
		'class' => 'ICE_Ext_Option_Ui_Scroll_Picker',
		'template' => true
	)
);

$this->register(
	'ui/slider',
	array(
		'class' => 'ICE_Ext_Option_Ui_Slider',
		'template' => true
	)
);

$this->register(
	'upload',
	array(
		'class' => 'ICE_Ext_Option_Upload',
		'template' => true
	)
);