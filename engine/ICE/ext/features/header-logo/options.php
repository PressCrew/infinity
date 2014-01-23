<?php

$options['image'] = array(
	'type' => 'upload',
	'title' => 'Logo Image',
	'description' => 'Upload a custom logo to appear in the header'
);

$options['toggle'] = array(
	'type' => 'toggle/on-off',
	'title' => 'Logo Image On/Off',
	'description' => 'Turn this Off to prevent any image logo from displaying, even the default.',
	'default_value' => true,
	'parent' => '%feature%.image'
);

$options['pos'] = array(
	'type' => 'position/left-center-right',
	'title' => 'Logo Position',
	'description' => 'Select a position for the logo',
	'default_value' => 'l',
	'parent' => '%feature%.image',
);

$options['top'] = array(
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
);

$options['left'] = array(
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
);

$options['right'] = array(
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
);

// return config array to caller
return $options;