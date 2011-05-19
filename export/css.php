<?php
/**
 * Infinity Theme: css exporter
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package export
 * @since 1.0
 */

// set content type
header('Content-Type: text/css');

// last modified
if ( array_key_exists( 'ver', $_GET ) ) {
	$last_modified = (integer) trim( $_GET['ver'] );
} else {
	$last_modified = null;
}

// if modified header
if( array_key_exists( 'HTTP_IF_MODIFIED_SINCE', $_SERVER ) ) {
	$if_modified_since = strtotime(
		preg_replace( '/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) );
} else {
	$if_modified_since = null;
}

// both variables must be present
if ( $if_modified_since > 0 && $last_modified > 0 ) {
	// compare times
	if ( $if_modified_since >= $last_modified ) {
		header("HTTP/1.0 304 Not Modified");
		exit();
	}
} else {
	// output css
	header('Last-Modified: ' . date('r', $last_modified));
	require_once( '../../../../wp-load.php' );
	print Infinity_Options_Policy::instance()->registry()->export_css();
}
?>