# Disabling Theme Support

If you wanted to remove theme support in a child theme that was set in an ancestor theme,
you would have:

In functions.php

	remove_theme_support( 'automatic-feed-links' );

In infinity.ini

	[feature]
	automatic-feed-links = off

Using either method has the same result.
