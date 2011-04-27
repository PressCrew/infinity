## Configuration: Advanced Directives

Infinity has some additional configuration directives which are available for special cases.

### Directives

#### jui\_theme

The jui\_theme is where you define your jQuery UI theme style sheet handle as defined in
the `[scripts]` section if you which to override the default theme.

	jui_theme = "ui_style_handle"

#### options\_save\_single

The default is to always show two save buttons for each option on the theme options panel.
Set this to `off` if you only want to show the "Save All" button.

	options_save_single = off
