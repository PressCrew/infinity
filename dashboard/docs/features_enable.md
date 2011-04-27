# Enabling Theme Support

You can enable features in your child theme's functions.php just like you would with any other
theme, or you can use the power of the infinity config file to make it even easier:

In functions.php

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );

In infinity.ini

	[feature]
	post-thumbnails = on
	automatic-feed-links = on

Using either method has the same result.
