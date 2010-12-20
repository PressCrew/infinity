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

Pie_Easy_Loader::load( 'files', 'markdown' );

/**
 * Make Documentation easy
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
	 * The directory containing the docs
	 *
	 * @var string
	 */
	private $docs_dir;

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
	 * Constructor
	 * 
	 * @param string $page The page being viewed in the docs dir
	 */
	public function __construct( $dir, $page = null )
	{
		$this->docs_dir = $dir;
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

		// parse content based on markup format
		switch ( $this->page_markup ) {
			// HTML
			case self::MARKUP_HTML:
				return $contents;
			// Markdown
			case self::MARKUP_MARKDOWN:
			case self::MARKUP_MARKDOWN_LONG:
				return Pie_Easy_Markdown::parse( $contents );
		}
	}

	/**
	 * Print the results of parsing
	 */
	public function publish()
	{
		print $this->parse();
	}

	/**
	 * Factory helper
	 *
	 * @param string $page
	 * @return Pie_Easy_Docs
	 */
	static public function create( $page )
	{
		return new self( $page );
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
		return ( preg_match( '/^\w+$/', $page ) );
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
				self::MARKUP_MARKDOWN_LONG
			));

		// list all files that match page
		$files = Pie_Easy_Files::list_filtered( $this->docs_dir, sprintf( '/^%s\.(%s)$/', $page, $formats ), true );
		
		// get any files?
		if ( count($files) ) {
			// return the first one
			return array_shift( $files );
		} else {
			throw new Exception( sprintf( 'A file for the doc page "%s" does not exist in "%s"', $page, $this->docs_dir ) );
		}
	}

	/**
	 * Return URI for a given page
	 *
	 * @param string $page
	 * @return string
	 */
	private function page_url( $page )
	{
		return sprintf( $this->page_url_template, $page );
	}
}

?>
