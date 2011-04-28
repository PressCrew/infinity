## Configuration: Overview

If you have not already successfully installed Infinity
according the the [Installation](infinity://admin:doc/install_setup) page,
you might want to do that first to make this easier to follow.

<ul class="infinity-docs-menu"></ul>

### INI File Basics

All of the configuration of Infinity is done with ini files. The ini files all reside
in a subdirectory named `config` in your theme's root:

	wp-content/themes/my-theme/config

If you are not familiar with how ini files work, it is very simple. They contain sections,
directives, and values:

	; a comment
	[section]
	directive_one = 1
	directive_two = on
	directive_three = "a string value"

### The Infinity Config File

All of the configuration directives discussed in this document are set in Infinity's
main ini file:

	wp-content/themes/my-theme/config/infinity.ini

> Take a look at the infinity.sample.ini file located in the same directory
to see complete working examples of all the configuration directives available to you.
