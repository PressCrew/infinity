## Configuration: Advanced Directives

Infinity has some additional configuration directives which are available for special cases.

### Directives

#### ui\_stylesheet

The ui\_stylesheet is where you define an alternate jQuery UI theme stylesheet if you wish
to use a theme other than the default which is defined internally.

	ui_stylesheet = "path/to/ui-custom.css"

#### options\_save\_single

The default is to always show two save buttons for each option on the theme options panel.
Set this to `off` if you only want to show the "Save All" button.

	options_save_single = off
