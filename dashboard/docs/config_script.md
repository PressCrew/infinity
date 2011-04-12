## Configuration: Automatic Script Enqueuing

Infinity allows you to enqueue your theme's scripts entirely using configuration
directives without writing any PHP code. The WordPress function `wp_enqueue_scripts()`
is called to enqueue the scripts where the configuration calls for it.

### Directives

#### [script]

The script section is where you define your scripts. A script is a handle (name) of the script
and the script file's relative path *from your theme's root directory.* Full length URLs to
style sheets are also supported if the begin with `http`.

The format is:

	handle = "path/to/script.js"

Here is an example:

	[script]
	sliders = "js/vendors/slider.js"
	ajax = "js/ajax.js"
	custom = "js/custom.js"
	gmaps = http://maps.google.com/maps/api/js

The above code would result in the following three scripts being enqueued.

	http://mysite.com/wp-content/themes/my-theme/js/vendors/slider.js
	http://mysite.com/wp-content/themes/my-theme/js/ajax.js
	http://mysite.com/wp-content/themes/my-theme/js/custom.js
	http://maps.google.com/maps/api/js

> *Important:* All scripts will ALWAYS be enqueued, UNLESS an action or condition is set (see below)

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
	custom = "gmaps,ajax"

In the above example, the `gmaps` and `ajax` scripts would be enqueued before
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

The basic format is:

	callback_name = "comma,separated,script,handles"

The format to pass any number of arguments to the callback is:

	callback_name:arg1,arg2,arg3 = "comma,separated,script,handles"

You should use the *exact* same handle names that you defined in the `[scripts]` section.

Here is an example:

	[script_conditions]
	is_front_page = "sliders"

In the above example, the `sliders` script would only be enqueued if the `is_front_page()`
function returns true.

> All script conditions are evaluated on the `wp_print_styles` and `admin_print_styles` actions
at priority 10. This is hard coded in Infinity and cannot be changed via any configuration settings.
