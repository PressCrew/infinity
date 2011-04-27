# Infinity Core Theme Features

Infinity has a few built-in features that you can use to quickly add functionality
to your theme. We've done this, because we think that certain customization features are
essential for any theme.

Unlike the standard solutions WordPress provides for custom headers and custom backgrounds,
we've integrated this functionality directly into the theme options panel, so your users do
not have to switch between different pages in the dashboard.

<ul class="infinity-docs-menu"></ul>

## Directives

Currently there are four core features that you can use in your themes.

> Some of the features require that template tags be added to your template files,
particularly your header.php file, *so pay close attention to the documentation
for each feature*.

### Custom CSS

This feature enables a custom CSS option in the theme options panel.

To enable custom css support, add the following to the `[feature]` section
of your theme's infinity.ini:

	infinity-custom-css = on

> All of the values of registered options with the `field_type` of "css" are merged
and sent to the browser by enqueueing a special export script. This is done automatically,
there are no additional steps to take.

### Header Logo

This feature enables a file upload option in the theme options panel for a custom logo.

To enable header logo support, add the following to the `[feature]` section
of your theme's infinity.ini:

	infinity-header-logo = on

> This feature requires the additional step of placing a template tag
in the `<head>` element of your theme! If you skip the step below,
this feature will *not* work properly.

To display the logo you must execute this function in the `<head>` element of your theme:

	<?php infinity_feature_header_logo_style(); ?>

To associate the output with your own CSS selector, you can do something like this:

	<?php infinity_feature_header_logo_style('a#my-logo-id'); ?>

### Header Background

This feature enables a file upload option in the theme options
panel for a custom header background image.

To enable header background support, add the following to the `[feature]` section
of your theme's infinity.ini:

	infinity-header-background = on

> This feature requires the additional step of placing a template tag
in the `<head>` element of your theme! If you skip the step below,
this feature will *not* work properly.

To display the background you must execute this function in the `<head>` element of your theme:

	<?php infinity_feature_header_background_style(); ?>

To associate the output with your own CSS selector, you can do something like this:

	<?php infinity_feature_header_background_style('div#my-header-id'); ?>

### Site Background

This feature enables a file upload option in the theme options
panel for a custom site background image.

To enable site background support, add the following to the `[feature]` section
of your theme's infinity.ini:

	infinity-site-background = on

> This feature requires the additional step of placing a template tag
in the `<head>` element of your theme! If you skip the step below,
this feature will *not* work properly.

To display the background you must execute this function in the `<head>` element of your theme:

	<?php infinity_feature_site_background_style(); ?>

To associate the output with your own CSS selector (`<body>` is the default selector), you can
do something like this:

	<?php infinity_feature_site_background_style('div#my-container'); ?>
