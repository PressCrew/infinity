## Theme Options: Overview

Our main focus when developing Infinity, was to make it *really* easy to work with
it as a theme designer. If you're familiar with WordPress theme development then you
know how much time is spent on implementing, maintaining and extending your theme
options panels in the WordPress backend. We have spent a lot of time making this
process move as close to the speed of thought as possible.

This means that you can add every possible theme option you can probably imagine by
simply adding a few lines in the options.ini configuration file included in every child theme.

Infinity theme option values are retrieved with a few custom functions. It is important that
you use these functions to get your theme option values as they do many special things behind
the scenes, like handling default values and checking user capabilities.

	infinity_option( $option_name );
	infinity_option_image_src( $option_name, $size );
	infinity_option_image_url( $option_name, $size );

The options.ini file tells Infinity what options you want to provide with your theme,
and loads them into the WordPress dashboard for the site administrator to configure.
For instance if you would like to add a file uploader to your theme to let people upload
an image of their storefront, you simply add this to your options.ini file:

	[mytheme_storefront_image]
	section = "default"
	title = "Storefront Photo"
	description = "Upload a picture of your storefront which will show on the main page."
	field_type = "upload"

That's all there is to it! Your users can now upload an image using our highly advanced uploader
widget, and use the built in WordPress editor to crop and edit their image.

Now you can add the following markup to your theme template to ouput
the uploaded image:

	<img src="<?php echo infinity_option_image_url( 'mytheme_storefront_image' ) ?>">

That's all there is to it! Infinity handles all the hard stuff behind the scenes so you
can focus on making your theme awesome, instead of making it work.

Next Page: [Sections](infinity://admin:doc/options_sections)