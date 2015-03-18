<?php
/**
 * Commons In A Box Theme: BuddyPress setup
 */

/**
 * When running BuddyPress Docs, don't allow theme compatibility mode to kick in
 *
 * @since 1.0.5
 */
add_filter( 'bp_docs_do_theme_compat', '__return_false' );
