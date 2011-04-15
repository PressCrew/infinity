## Theme Options: Sections

Infinity options are grouped into sections. Sections play an important role in how
options are displayed in the options panel.

<ul class="infinity-docs-menu"></ul>

### Configuration

To define a section, you add something like this to your options.ini file BEFORE any options that
will be assigned to that section are defined. Section names must be all lower case.

	[.typography]
	title = "Typography"

Now you have a new section to which typography options can be assigned.

### Directives

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

> Any section which has children sections assigned to it cannot contain options. If you try to
assign options to a parent section, a fatal error will occur.

Next Page: [Sections](infinity://admin/cpanel/docs/options_options)
