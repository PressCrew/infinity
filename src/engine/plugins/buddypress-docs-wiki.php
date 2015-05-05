<?php
/**
 * Infinity Theme: buddypress-docs-wiki compat
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

/**
 * Return URL of the create wiki page.
 * 
 * @return string
 */
function infinity_bp_docs_wiki_create_url()
{
	return trailingslashit( home_url( bpdw_slug() ) ) . trailingslashit( BP_DOCS_CREATE_SLUG );
}