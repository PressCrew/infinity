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

## Sections

To define a section, you add something like this to your options.ini file BEFORE any options that
will be assigned to that section are defined. Section names must be all lower case.

	[.typography]
	title = "Typography"

Now you have a new section to which typography options can be assigned.

### Section Directives

You configure sections with the directives below:

#### title

The section title which is displayed in the theme options panel menu. Section titles
can contain just about any character except double quotes.

	title = "Welcome Text"

#### parent

Sections can be nested to build up a tree. In order to configure a section to belong to a parent
section, you simply add the "parent" directive and set its value to another section that has
already been defined:

	parent = "layout"

Any section which has children sections assigned to it cannot contain options. If you try to
assign options to a parent section, a fatal error will occur.

## Options

Each option configuration block begins with a unique name that you use to call
the option output in your WordPress theme.
This works exactly the same as you would normally do in when building a theme.
Option names _MUST_ begin with the name of your theme's directory name.

	[mytheme_storefront_image]

Child themes inherit *All* of the options from *EVERY* ancestor theme, allowing you to
create a highly extensible theme hierarchy. In addition, some of the option directives can
be overridden for fine grained control over the look and features of your theme.

### Option Directives

You configure options with the directives below:

#### section

This tells Infinity in which section of the theme options you want to put the option.
You can create your own sections to divide the theme options, and Infinity handles the
layout automagically. This allows you to provide your users with a well organized and easy
to configure theme.

	section = "home_page"

Child theme can override section: **No**

#### title

Use a descriptive name to tell the user what this option does.

	title = "Welcome Text"

Child theme can override title: **No**

#### description

A more detailed description of what the options does inside your theme.

	description = "Edit the welcome text on your home page"

Child theme can override description: **No**

#### class

You can assign a extra CSS class to any option to customize the looks using CSS.
This is a powerful feature which allows you to completely customize the look and feel of
the options panel and make it fit your theme's or client's needs.

	class = "my-css-class"

Child theme can override class: **Yes**

#### field\_id

You can assign a CSS id to the option field in case you need to provide advanced functionality.

	field_id = "my-css-id"

Child theme can override field\_id: **Yes**

#### field\_class

You can assign an extra CSS class to the option field for customized styling of the input.

	field_class = "my-css-class"

Child theme can override field\_class: **Yes**

#### field\_type

This is where you configure what type of option you want to display.

	field_type = "text"

The available types are:

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
	* _css_ - Custom CSS integration (requires the "infinity-custom-css" theme feauture)

Child theme can override field\_type: **No**

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

Child theme can override field\_options: **No**

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

There are two valid formats for doc pages.

* **Markdown**

	The doc page must end with the `.md` extension and contain valid Markdown markup. This is the
	preferred method for themes that will be extended so that non-classed element styles can be
	applied.

	For help with Markdown, see
	[Basic Usage](http://daringfireball.net/projects/markdown/basics) and the
	[Syntax Reference](http://daringfireball.net/projects/markdown/syntax)

	**Note:** Custom Infinity `<a>` and `<img>` tags support is under development

* **HTML**

	The doc page must end with the `.html` extension and contain valid HTML markup *with all tags
	properly closed*.

	Please keep in mind that anyone extending your theme will have to spend
	additional time on styling of the options panel if your markup implements special formatting
	which relies on custom CSS ids or classes. **The use of ids and classes is strongly discouraged.**

There are three documentation modes:

* **Disabled** (default)

	`documentation = false`

	This disables documentation for the option.

* **Automatic**

	`documentation = true`

	In this case the doc page should have the exact same name as the option.

* **Manual**

	`documentation = "mytheme_option"`

	In this case the doc page should be named `mytheme_option.md` or `mytheme_option.html`.

	Sub-directory:

	`documentation = "foo/bar/mytheme_option"`

	Create the directories `config/docs/foo/bar` and place your doc page in the `foo` directory.

	Infinity makes heavy use of the manual mode due to the fact that you can re-use the same
	page doc for as many options as you wish. This allows you to build up and pull from a library
	of reusable documentation.

The output of the doc page will be shown in the Documentation tab of the option. If documentation
for the option is disabled, or no doc page can be found in the theme hierarchy, no documentation
tab will be displayed.

If you are extending a theme and wish to provide different documentation than the parent's author
has provided, you simply create the same doc page in your theme, and your documentation will override
the parent file. In this case you are not limited to using the same format. If the parent theme
has a file named `mytheme_option.html`, you can create a file called `mytheme_option.md` and your Markdown
file will override the HTML file.

Child theme can override documentation setting: **Yes**  
Child theme can override documentation pages: **Yes**

#### capabilities

A comma separated list of WordPress capabilities that the user must have in order to edit
this specific option.

	capabilities = "edit_posts"

Child theme can override capabilities: **No**
Child theme can add/append to capabilities: **Yes**

#### required\_option

Providing the name of another option will cause this "dependant option" to always
be displayed when the required option is displayed.

	required_option = "option_name"

Additionally, this option will not be displayed in the theme options menu.

Child theme can override required\_option: **No**

#### required\_feature

Providing the name of a theme feature will invoke a call to `current_theme_supports()`
to determine if the option should be visible to the site administrator.

	required_feature = "a-feature-name"

Child theme can override required\_feature: **No**

## Sample Configuration

Take a look at the options-sample.ini file located in the `infinity/config` directory
to see complete working examples of all the options available to you.