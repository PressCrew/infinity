# Easy Theme Options with Infinity

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

	infinity_option( $option_name )
	infinity_option_image_src( $option_name, $size )
	infinity_option_image_url( $option_name, $size )

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

## Sections

To define a section, you add something like this to your options.ini file BEFORE any options that
will be assigned to that section are defined. Section names must be all lower case. Section titles
can contain just about any character except double quotes.

	[.typography]
	title = "Typography"

Now you have a new section to which typography options can be assigned.

## Options

Each option configuration block begins with a unique name that you use to call
the option output in your WordPress theme.
This works exactly the same as you would normally do in when building a theme.
Option names _MUST_ begin with the name of your theme's directory name.

	`[mytheme_storefront_image]`

Child themes inherit *All* of the options from *EVERY* ancestor theme, allowing you to
create a highly extensible theme hierarchy.

You configure each option using the directives that follow.

### `section`

This tells Infinity in which section of the theme options you want to put the option.
You can create your own sections to divide the theme options, and Infinity handles the
layout automagically. This allows you to provide your users with a well organized and easy
to configure theme.

### `title`

Use a descriptive name to tell the user what this option does.

### `description`

A more detailed description of what the options does inside your theme.

### `class`

You can assign a extra CSS class to any option to customize the looks using CSS.
This is a powerful feature which allows you to completely customize the look and feel of
the options panel and make it fit your theme's or client's needs.

### `field_id`

You can assign a CSS id to the option field in case you need to provide advanced functionality.

### `field_class`

You can assign an extra CSS class to the option field for customized styling of the input.

### `field_type`

This is where you configure what type of option you want to display. The available types are

* _Basic Fields_
	* _text_ - Text input
	* _textarea_ Text area input
	* _select_ - Select box
	* _radio_ - Radio buttons
	* _checkbox_ - Check boxes
* _Dynamic Fields_
	* _category_ - Select one category
	* _categories_ - Select multiple categories
	* _page_ - Select one page
	* _pages_ - Select multiple pages
	* _post_ - Select one post
	* _posts_ - Select multiple posts
* _Widget Fields_
	* _upload_ - Media uploader widget
	* _colorpicker_ - Color picker widget

### `field_options`

Basic multi-select field types, which include `checkbox`, `radio` and `select` support this option.

You can provide static options, as seen in the following two examples.

	field_options[] = "1=On"
	field_options[] = "0=Off"

	field_options[] = "red=Red"
	field_options[] = "green=Green"
	field_options[] = "blue=Blue"
	field_options[] = "sunny=Sunny Yellow"

Alternatively, you can provide a callback function to populate the available options.

	field_options = "my_theme_color_options"

The callback must return a one dimensional array where the array keys are the option values,
and the corresponding value for the keys are the description.

	function my_theme_color_options() {
		return array(
			'red' => 'Red',
			'green' => 'Green',
			'blue' => 'Blue',
			'sunny' => 'Sunny Yellow',
		);
	}

### `default_value`

You can provide a default value for the option here. This will be returned by calls to
`infinity_option()` if the option has not been set by the site administrator.

Child themes can override the default value set in any of their ancestor themes.

### `capabilities`

A comma separated list of WordPress capabilities that the user must have in order to edit
this specific option.

### `required_feature`

Providing the name of a theme feature will invoke a call to `current_theme_supports()`
to determine if the option should be visible to the site administrator.

Take a look at the options-sample.ini file located in the `infinity/config` directory
to see complete working examples of all the options available to you.