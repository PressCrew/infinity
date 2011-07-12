## Theme Options: Options

Infinity options are the most powerful feature of Infinity, allowing you to build
custom themes extremely quickly and efficiently.

<ul class="infinity-docs-menu"></ul>

### Configuration

Each option configuration block begins with a unique name that you use to call
the option output in your WordPress theme.
This works exactly the same as you would normally do in when building a theme.
Option names _MUST_ begin with the name of your theme's directory name.

	[mytheme_storefront_image]

Child themes inherit *All* of the options from *EVERY* ancestor theme, allowing you to
create a highly extensible theme hierarchy. In addition, some of the option directives can
be overridden for fine grained control over the look and features of your theme.

### Overriding

Some option directives can be overridden by child themes. After each directive there is a note
which explains in what cases each directive can be overridden.

### Directives

You configure options with the directives below:

#### type

This is where you configure what type of option you want to display.

	type = "text"

The available option types are:

* __Basic__
	* __text__ - Text input
	* __textarea__ Text area input
	* __select__ - Select box
	* __radio__ - Radio buttons
	* __checkbox__ - Check boxes
	* __css__ - Custom CSS textarea
* __Dynamic__
	* __category__ - Select one category
	* __categories__ - Select multiple categories
	* __page__ - Select one page
	* __pages__ - Select multiple pages
	* __post__ - Select one post
	* __posts__ - Select multiple posts
* __Toggles__
	* __on__ - "On" checkbox
	* __off__ - "Off" checkbox
	* __yes__ - "Yes" checkbox
	* __no__ - "No" checkbox
	* __enable__ - "Enable" checkbox
	* __disable__ - "Disable" checkbox
	* __onoff__ - On/Off radios
	* __yesno__ - Yes/No radios
	* __enabledisable__ - Enable/Disable radios
	* __leftright__ - Left/Right radios
* __Widgets__
	* __upload__ - Media uploader widget
	* __colorpicker__ - Color picker widget

Child theme can override field\_type: **No**

#### section

This tells Infinity in which section of the theme options you want to put the option.
You can create your own sections to divide the theme options, and Infinity handles the
layout automagically. This allows you to provide your users with a well organized and easy
to configure theme.

	section = "home_page"

> *Important* Every option must be assigned to at least one section.

Child theme can override section: **Yes**

#### title

Use a descriptive name to tell the user what this option does.

	title = "Welcome Text"

Child theme can override title: **Yes**

#### description

A more detailed description of what the options does inside your theme.

	description = "Edit the welcome text on your home page"

Child theme can override description: **Yes**

#### class

You can assign a extra CSS class to any option to customize the looks using CSS.
This is a powerful feature which allows you to completely customize the look and feel of
the options panel and make it fit your theme's or client's needs.

	class = "my-css-class"

Child theme can override class: **Yes**

#### field\_id

You can assign a CSS id to the option field in case you need to provide advanced functionality.

	field_id = "my-css-id"

> Since radio and checkbox groups cannot all have the same CSS id, the entire group of
inputs will be wrapped with a div where the id equals the `field_id`.

Child theme can override field\_id: **Yes**

#### field\_class

You can assign an extra CSS class to the option field for customized styling of the input.

	field_class = "my-css-class"

Child theme can override field\_class: **Yes**

#### field\_options

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

Child theme can override field\_options: **Yes**

#### default\_value

You can provide a default value for the option here. This will be returned by calls to
`infinity_option()` if the option has not been set by the site administrator.

	default_value = "blue"

Child theme can override default\_value: **Yes**

#### documentation

Documentation can be provided for any option by creating a doc page in the configuration
docs folder:

	wp-content/themes/child_theme/config/docs/mytheme_option.md

Sub-directories are also allowed:

	wp-content/themes/child_theme/config/docs/foo/bar/mytheme_option.md

There are three valid formats for doc pages.

* **Markdown**

	The doc page must end with the `.md` extension and contain valid Markdown markup. This is the
	preferred method for themes that will be extended so that non-classed element styles can be
	applied.

	For help with Markdown, see
	[Basic Usage](http://daringfireball.net/projects/markdown/basics) and the
	[Syntax Reference](http://daringfireball.net/projects/markdown/syntax)

	> Custom Infinity `<a>` and `<img>` tags support is under development

* **Textile**

	The doc page must end with the `.text` or `.textile` extensions and contain valid Textile
	markup.

	For help with Textile, see the [Syntax Reference](http://textile.thresholdstate.com/)

	> Custom Infinity `<a>` and `<img>` tags support is under development.

* **HTML**

	The doc page must end with the `.html` extension and contain valid HTML markup *with all tags
	properly closed*.

	> Please keep in mind that anyone extending your theme will have to spend
	additional time on styling of the options panel if your markup implements special formatting
	which relies on custom CSS ids or classes. **The use of ids and classes is strongly discouraged.**

There are three documentation modes:

* **Disabled** (default)

	`documentation = off`

	This disables documentation for the option.

* **Automatic**

	`documentation = on`

	In this case the doc page should have the exact same name as the option, and be placed
	in a subdirectory of your `config/docs` folder called `options`. For example:

	`my-theme/config/options/option_name.md`

* **Manual**

	`documentation = "mytheme_option"`

	In this case the doc page should be named `mytheme_option.text`,
	`mytheme_option.md` or `mytheme_option.html`.

	Sub-directory:

	`documentation = "foo/bar/mytheme_option"`

	Create the directories `config/docs/foo/bar` and place your doc page in the `foo` directory.

	> Infinity makes heavy use of the manual mode due to the fact that you can re-use the same
	page doc for as many options as you wish. This allows you to build up and pull from a library
	of reusable documentation.

The output of the doc page will be shown in the Documentation tab of the option. If documentation
for the option is disabled, or no doc page can be found in the theme hierarchy, no documentation
tab will be displayed.

If you are extending a theme and wish to provide different documentation than the parent's author
has provided, you simply create the same doc page in your theme, and your documentation will override
the parent file. In this case you are not limited to using the same format. If the parent theme
has a file named `mytheme_option.html`, you can create a file called `mytheme_option.md` and
your Markdown file will override the HTML file.

Child theme can override documentation setting: **Yes**<br />
Child theme can override documentation pages: **Yes**

#### capabilities

A comma separated list of WordPress capabilities that the user must have in order to edit
this specific option.

	capabilities = "edit_posts"

Child theme can override capabilities: **No**<br />
Child theme can add/append to capabilities: **Yes**

#### required\_option

Providing the name of another option will cause this "dependant option" to always
be displayed when the required option is displayed.

	required_option = "option_name"

> This option will not be displayed in the theme options menu.

Child theme can override required\_option: **No**

#### required\_feature

Providing the name of a theme feature will invoke a call to `current_theme_supports()`
to determine if the option should be visible to the site administrator.

	required_feature = "a-feature-name"

Child theme can override required\_feature: **No**

### Sample Configuration

> Take a look at the options.sample.ini file located in the `wp-content/themes/infinity/config` directory
to see complete working examples of all the options available to you.

### Edit Anywhere

Infinity handles all of the dirty work for maintaining the options panel. However, there may be
times when you wish to make it possible to edit an option from a different screen. You can easily
embed option fields for editing on any Infinity control panel screen by using some simple template
tags.

#### Basic Example

Embedding options using PHP follows this logic.

1. Opening form tag.
1. Call render begin function for option A.
1. Call any number of element renderering functions.
1. Call render end function.
1. Call render begin function for option B.
1. Call any number of element renderering functions.
1. Call render end function.
1. Closing form tag.

Here is a basic example which would embed an option field into a screen template.

	<form>
		<?php
			infinity_option_render_begin( 'infinity_header_logo' );
			infinity_option_render_label();
			infinity_option_render_field();
			infinity_option_render_buttons();
			infinity_option_render_end();
		?>
	</form>

#### Rendering Functions

* **infinity\_option\_render\_begin**

	Prints opening option block element and flash message element.

		(void) infinity_option_render_begin( string $option_name )

	> _$option\_name_ : Name of option as configured in options.ini

* **infinity\_option\_render\_end**

	Prints closing option block element.

		(void) infinity_option_render_end()

* **infinity\_option\_render\_title**

	Prints option title as plain text

		(void) infinity_option_render_title()

* **infinity\_option\_render\_description**

	Prints option description as plain text

		(void) infinity_option_render_description()

* **infinity\_option\_render\_label**

	Prints option title wrapper in a label tag

		(void) infinity_option_render_label()

* **infinity\_option\_render\_field**

	Prints option form field and related elements

		(void) infinity_option_render_field()

* **infinity\_option\_render\_buttons**

	Prints one or both option save buttons

		(void) infinity_option_render_buttons()

* **infinity\_option\_render\_save_all**

	Prints option save all button

		(void) infinity_option_render_save_all()

* **infinity\_option\_render\_save_one**

	Prints option save one button

		(void) infinity_option_render_save_one()

* **infinity\_option\_render\_meta**

	Prints option meta information. (Currently only last updated date/time)

		(void) infinity_option_render_meta()