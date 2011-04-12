<?php
/**
 * PIE docs class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage docs
 * @since 1.0
 */

Pie_Easy_Loader::load( 'files' );

/**
 * Make Documentation easy
 *
 * @package pie
 * @subpackage docs
 */
class Pie_Easy_Docs
{
	/**
	 * The default page name
	 */
	const DEFAULT_PAGE = 'index';

	/**
	 * HTML markup type
	 */
	const MARKUP_HTML = 'html';

	/**
	 * Markdown markup type
	 * @link http://daringfireball.net/projects/markdown/
	 */
	const MARKUP_MARKDOWN = 'md';
	const MARKUP_MARKDOWN_LONG = 'markdown';

	/**
	 * Textile markup type
	 * @link http://textile.thresholdstate.com/
	 */
	const MARKUP_TEXTILE = 'text';
	const MARKUP_TEXTILE_LONG = 'textile';

	/**
	 * Directories which may contain the docs
	 *
	 * @var array
	 */
	private $doc_dirs;

	/**
	 * The page name
	 *
	 * @var string
	 */
	private $page;

	/**
	 * The page file to parse
	 *
	 * @var string
	 */
	private $page_file;

	/**
	 * The page markup format
	 *
	 * @var string
	 */
	private $page_markup;

	/**
	 * The URL template to use for link generation
	 *
	 * This template is processed with sprintf() and should contain exactly one string token
	 * which will be replaced with the page name.
	 *
	 * @var string
	 */
	private $page_url_template;
	
	/**
	 * The callback to filter markup before parsing
	 * 
	 * @var string|array 
	 */
	private $pre_parse_callback;
	
	/**
	 * The callback to filter markup after parsing
	 * 
	 * @var string|array 
	 */
	private $post_parse_callback;

	/**
	 * Constructor
	 * 
	 * @param string|array $dir Directory or directories which may contain the doc page
	 * @param string $page The page being viewed in the docs dir
	 */
	public function __construct( $dir, $page = null )
	{
		if ( is_array( $dir ) ) {
			$this->doc_dirs = $dir;
		} else {
			$this->doc_dirs = array( $dir );
		}
		
		$this->set_page( $page );
	}

	/**
	 * Get parsed and formatted contents of page file
	 *
	 * @return string Valid HTML markup
	 */
	public function parse()
	{
		// grab entire contents of file
		$contents = file_get_contents( $this->page_file );

		// call pre parse filter if exists
		if ( $this->pre_parse_callback ) {
			$contents = call_user_func( $this->pre_parse_callback, $contents );
		}

		// parse content based on markup format
		switch ( $this->page_markup ) {
			// HTML
			case self::MARKUP_HTML:
				break;
			// Markdown
			case self::MARKUP_MARKDOWN:
			case self::MARKUP_MARKDOWN_LONG:
				Pie_Easy_Loader::load( 'markdown' );
				$contents = Pie_Easy_Markdown::parse( $contents );
				break;
			// Textile
			case self::MARKUP_TEXTILE:
			case self::MARKUP_TEXTILE_LONG:
				Pie_Easy_Loader::load( 'textile' );
				$contents = Pie_Easy_Textile::parse( $contents );
				break;
			// Invalid
			default:
				throw new Exception( sprintf( 'The markup format "%s" is not valid', $this->page_markup ) );
		}

		// call post parse filter if exists
		if ( $this->post_parse_callback ) {
			$contents = call_user_func( $this->post_parse_callback, $contents );
		}

		return $contents;
	}

	/**
	 * Print the results of parsing
	 */
	public function publish()
	{
		print $this->parse();
	}

	/**
	 * Set the pre parse filter callback
	 *
	 * @param mixed $callback
	 */
	public function set_pre_filter( $callback )
	{
		if ( is_callable( $callback ) ) {
			$this->pre_parse_callback = $callback;
		} else {
			throw new Exception( sprintf( 'Invalid callback' ) );
		}
	}

	/**
	 * Set the post parse filter callback
	 *
	 * @param mixed $callback
	 */
	public function set_post_filter( $callback )
	{
		if ( is_callable( $callback ) ) {
			$this->post_parse_callback = $callback;
		} else {
			throw new Exception( sprintf( 'Invalid callback' ) );
		}
	}

	/**
	 * Set the current page
	 *
	 * @param string $page
	 * @return boolean
	 */
	private function set_page( $page = null )
	{
		// fall back to default
		if ( empty( $page ) ) {
			$page = self::DEFAULT_PAGE;
		} else {
			// split page at static directory separators
			$splits = Pie_Easy_Files::path_split( $page );
			// page is last item
			$page = array_pop( $splits );
			// anything left?
			if ( count($splits) ) {
				// append remaining parts to each doc dir
				foreach ( $this->doc_dirs as &$doc_dir ) {
					$doc_dir .= Pie_Easy_Files::path_build( $splits );
				}
			}
		}

		// validate the page name
		if ( $this->validate_page_name( $page ) ) {
			$this->page = $page;
			$this->update_page_file();
			return true;
		} else {
			throw new Exception( 'Doc page file names can only contain letters, numbers and underscore characters' );
		}
	}

	/**
	 * Set the file of the page
	 * 
	 * @param string $page 
	 */
	private function update_page_file()
	{
		$this->page_file = $this->find_page_file( $this->page );
		$this->update_page_markup();
	}

	/**
	 * Set the markup format of the page
	 *
	 * @param string $page
	 */
	private function update_page_markup()
	{
		if ( preg_match( '/\.([a-z]+)$/', $this->page_file, $matches ) ) {
			$this->page_markup = $matches[1];
		}
	}
	
	/**
	 * Validate a page name
	 *
	 * @param string $page
	 * @return boolean
	 */
	private function validate_page_name( $page )
	{
		return ( preg_match( '/^\.?\w+$/', $page ) );
	}

	/**
	 * Return the file path for a given page
	 *
	 * @param string $page
	 * @return string
	 */
	private function find_page_file( $page )
	{
		// valid formats
		$formats =
			join( '|', array(
				self::MARKUP_HTML,
				self::MARKUP_MARKDOWN,
				self::MARKUP_MARKDOWN_LONG,
				self::MARKUP_TEXTILE,
				self::MARKUP_TEXTILE_LONG
			));

		// loop through all doc dirs looking for doc page
		foreach ( $this->doc_dirs as $doc_dir ) {

			try {
				// list all files in current dir that match page
				$files = Pie_Easy_Files::list_filtered( $doc_dir, sprintf( '/^%s\.(%s)$/', $page, $formats ), true );
			} catch ( Pie_Easy_Files_Exception $e ) {
				// ignore file errors
				continue;
			}

			// get any files?
			if ( count($files) ) {
				// return the first one
				return array_shift( $files );
			}
		}

		// no doc page found
		throw new Exception( sprintf( 'A file for the doc page "%s" does not exist in any of the configured directories', $page ) );
	}

	/**
	 * Return URL for a given page
	 *
	 * @param string $page
	 * @return string
	 */
	public function page_url( $page )
	{
		return sprintf( $this->page_url_template, $page );
	}
}

?>
