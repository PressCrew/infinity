# Configuring Infinity

If you have not already successfully installed Infinity
according the the [Installation](infinity://admin/cpanel/docs/setup) page,
you might want to do that first to make this easier to follow.

All of the configuration of Infinity is done with ini files. The ini files all reside
in a subdirectory named `config` in your theme's root:

	wp-content/themes/my-theme/config

If you are not familiar with how ini files work, it is very simple. They contain sections,
directives, and values:

	; a comment
	[section]
	directive_one = 1
	directive_two = true
	directive_three = "a string value"

All of the configuration directives discussed in this document are set in Infinity's
main ini file:

	wp-content/themes/my-theme/config/infinity.ini

### Required Directives

These directives MUST be set for Infinity to work properly.

#### parent\_theme

The parent theme must be either "infinity" or an ancestor of infinity. If you do not set this, it
is assumed that the parent theme is "infinity"

	parent_theme = "my-child-theme"

#### [ui\_theme]

The ui\_theme is where you define your jQuery UI theme style sheet.
The value is the style sheet's relative path *from your theme's root directory.*

	ui_theme = "path/to/ui-custom.css"

### Automatic Style Enqueuing

Infinity allows you to enqueue your theme's style sheets entirely using configuration
directives without writing any PHP code. The WordPress function `wp_enqueue_styles()`
is called to enqueue the styles where the configuration calls for it.

#### [style]

The style section is where you define your styles. A style is a handle (name) of the style sheet
and the style sheet's relative path *from your theme's root directory.*

The format is:

	handle = "path/to/file.css"

Here is an example:

	[style]
	colors = "css/colors.css"
	sliders = "css/vendors/slider.css"
	page-sidebar = "css/pagesidebars.css"

The above code would result in the following three syle sheets being enqueued.

	http://mysite.com/wp-content/themes/my-theme/css/colors.css
	http://mysite.com/wp-content/themes/my-theme/css/vendors/slider.css
	http://mysite.com/wp-content/themes/my-theme/css/pagesidebars.css

*Important:* All styles will ALWAYS be enqueued, UNLESS an action or condition is set (see below)

#### [style\_depends]

The style dependancies section lets you define other style sheets that one of your
style sheets requires to be loaded before it.

The format is:

	handle = "comma,separated,style,handles"

You should use the *exact* same handle names that you defined in the `[styles]` section.
If your list of required handles contains a handle which is not defined by you, it will
still be passed to `wp_enqueue_styles()` as a dependancy assuming that is has already
been registered in WordPress.

Here is an example:

	[style_depends]
	sliders = "colors,thickbox"

In the above example, the `colors` and `thickbox` style sheets would be enqueued before
the `sliders` style sheet.

#### [style\_actions]

The style actions section lets you fine tune when styles are enqueued by assigning one
or more style handles to an action.

The format is:

	action = "comma,separated,style,handles"

You should use the *exact* same handle names that you defined in the `[styles]` section.

Here is an example:

	[style_actions]
	wp = "colors,sliders"

In the above example, the `colors` and `sliders` style sheets would only be enqueued if and when
the `wp` action is called by WordPress. It is up to you to use actions that are called in time
for `wp_enqueue_styles()` to work properly.

#### [style\_conditions]

The style conditions section lets fine tune when styles are enqueued by assigning one
or more style handles to a condition (callback function) which evaluates to true.

The format is:

	callback_name = "comma,separated,style,handles"

You should use the *exact* same handle names that you defined in the `[styles]` section.

Here is an example:

	[style_conditions]
	is_front_page = "sliders"
	is_page = "page-sidebar"

In the above example, the `sliders` style sheet would only be enqueued if the `is_front_page()`
function returns true, and the page-sidebar style sheet would only be enqueued if the `is_page()`
function returns true.

All style conditions are evaluated on the `wp_print_styles` and `admin_print_styles` actions at
priority 10. This is hard coded in Infinity and cannot be changed via any configuration settings.

### Automatic Script Enqueuing

Infinity allows you to enqueue your theme's scripts entirely using configuration
directives without writing any PHP code. The WordPress function `wp_enqueue_scripts()`
is called to enqueue the scripts where the configuration calls for it.

#### [script]

The script section is where you define your scripts. A script is a handle (name) of the script
and the script file's relative path *from your theme's root directory.*

The format is:

	handle = "path/to/script.js"

Here is an example:

	[script]
	sliders = "js/vendors/slider.js"
	ajax = "js/ajax.js"
	custom = "js/custom.js"

The above code would result in the following three scripts being enqueued.

	http://mysite.com/wp-content/themes/my-theme/js/vendors/slider.js
	http://mysite.com/wp-content/themes/my-theme/js/ajax.js
	http://mysite.com/wp-content/themes/my-theme/js/custom.js

*Important:* All scripts will ALWAYS be enqueued, UNLESS an action or condition is set (see below)

#### [script\_depends]

The script dependancies section lets you define other scripts that one of your
scripts requires to be loaded before it.

The format is:

	handle = "comma,separated,script,handles"

You should use the *exact* same handle names that you defined in the `[scripts]` section.
If your list of requuired handles contains a script handle which is not defined by you, it
will still be passed to `wp_enqueue_scripts()` as a dependancy assuming that is has already been
registered in WordPress.

Here is an example:

	[script_depends]
	custom = "sliders,ajax"

In the above example, the `sliders` and `ajax` scripts would be enqueued before
the `custom` script.

#### [script\_actions]

The script actions section lets you fine tune when scripts are enqueued by assigning one
or more script handles to an action.

The format is:

	action = "comma,separated,script,handles"

You should use the *exact* same handle names that you defined in the `[scripts]` section.

Here is an example:

	[script_actions]
	wp = "sliders,custom"

In the above example, the `sliders` and `custom` scripts would only be enqueued if and when
the `wp` action is called by WordPress. It is up to you to use actions that are called in time
for `wp_enqueue_scripts()` to work properly.

#### [script\_conditions]

The script conditions section lets fine tune when scripts are enqueued by assigning one
or more script handles to a condition (callback function) which evaluates to true.

The format is:

	callback_name = "comma,separated,script,handles"

You should use the *exact* same handle names that you defined in the `[scripts]` section.

Here is an example:

	[script_conditions]
	is_front_page = "sliders"

In the above example, the `sliders` script would only be enqueued if the `is_front_page()`
function returns true.

All script conditions are evaluated on the `wp_print_styles` and `admin_print_styles` actions
at priority 10. This is hard coded in Infinity and cannot be changed via any configuration settings.

### Advanced Directives

Infinity has some additional configuration directives which are available for special cases.

#### options\_save\_single

The default is to always show two save buttons for each option on the theme options panel.
Set this to false if you only want to show the "Save All" button.

	options_save_single = false

## Sample Configuration

Take a look at the infinity.sample.ini file located in the `infinity/config` directory
to see complete working examples of all the configuration directives available to you.