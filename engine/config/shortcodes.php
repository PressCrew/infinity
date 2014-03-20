<?php
/**
 * Infinity Shortcodes Configuration.
 *
 * !!! DO NOT EDIT THIS FILE !!!
 * Only edit files in a child theme.
 *
 * @package Infinity
 * @subpackage config
 */

$this->register(
	'access',
	array(
		'type' => 'access',
		'title' => 'Access Check',
		'description' => 'Display content to logged in users only',
		'attributes' => array(
			"capability=read"
		)
	)
);

$this->register(
	'visitor',
	array(
		'type' => 'visitor',
		'title' => 'Visitor Content',
		'description' => 'Display content to non-logged in users only'
	)
);