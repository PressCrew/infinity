<?php
/**
 * PIE API: widget extensions, posts list widget class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage widgets-ext
 * @since 1.0
 */

Pie_Easy_Loader::load( 'widgets/component' );

/**
 * Posts list widget
 *
 * @package PIE
 * @subpackage widgets-ext
 */
class Pie_Easy_Exts_Widget_Posts_List
	 extends Pie_Easy_Widgets_Widget
{
	protected function init()
	{
		parent::init();

		// requires edit posts
		$this->add_capabilities( 'edit_posts' );

		// requires cookie and nested sortable
		$this->script()->add_dep( 'jquery-cookie' );
		$this->script()->add_dep( 'jquery-ui-nestedsortable' );
	}

	/**
	 * @ignore
	 */
	public function get_template_vars()
	{
		// set it up!
		$posts_list = new Pie_Easy_Posts_List( $this->_post_type );
		$posts_list->prepare_items();

		return array(
			'widget' => $this,
			'posts_list' => $posts_list
		);
	}
}

//
// Supporting Classes
//

Pie_Easy_Loader::load_wpadmin_lib( 'class-wp-list-table', 'class-wp-posts-list-table' );

/**
 * @todo find a home for this
 */
class Pie_Easy_Posts_List extends WP_Posts_List_Table
{
	/**
	 * The current post being rendered
	 *
	 * @var stdClass
	 */
	private $post;
	
	/**
	 * The post type of the list of posts
	 * 
	 * @var string
	 */
	private $post_type;

	/**
	 * Array of page objects for hierarchical listings
	 *
	 * @var array
	 */
	private $pages = array();

	/**
	 * Array for storing temporary page results
	 *
	 * @var array
	 */
	private $pages_tmp = array();

	/**
	 * Create a new posts list
	 *
	 * @param string $post_type
	 */
	public function __construct( $post_type = 'post' )
	{
		// set local properties
		$this->post_type = $post_type;

		// set this is for wp_query (yeah, i know)
		$_GET['post_type'] = $this->post_type;

		// run parent constructor
		parent::WP_Posts_List_Table();
	}

	/**
	 * Call this to execute the query and prepare posts for listing
	 */
	public function prepare_items()
	{
		global $post_type, $post_type_object;

		// make sure the globals are set correctly
		$post_type = $this->post_type;
		$post_type_object = get_post_type_object( $post_type );

		// call parent
		return parent::prepare_items();
	}

	/**
	 * Display the list
	 *
	 * Overriding the parent since it spits out the default admin table structure
	 */
	public function display()
	{
		// skip right to display rows!
		$this->display_rows();
	}

	/**
	 * Display posts in a nested manner
	 *
	 * @param array $pages
	 * @param integer $pagenum NOT YET IMPLEMENTED
	 * @param integer $per_page NOT YET IMPLEMENTED
	 * @return boolean
	 */
	function _display_rows_hierarchical( $pages, $pagenum = 1, $per_page = 20 )
	{
		global $wpdb;

		if ( !$pages ) {
			$pages = get_pages( array( 'sort_column' => 'menu_order' ) );

			if ( !$pages )
				return false;
		}

		$this->pages_tmp = $pages;

		// find root pages
		foreach ( $this->pages_tmp as $key => $page ) {
			if ( empty( $page->post_parent ) ) {
				unset($this->pages_tmp[$key]);
				$this->pages[$page->ID]['object'] = $page;
				$this->pages[$page->ID]['children'] = $this->page_children( $page );
			}
		}

		return $this->nested_list( $this->pages );
	}

	/**
	 * Try to find children of this page from the temp pages array
	 *
	 * @param stdClass $page
	 * @return array
	 */
	public function page_children( $page )
	{
		$children = array();

		if ( count( $this->pages_tmp ) ) {
			foreach( $this->pages_tmp as $key => $maybe_child_page ) {
				if ( $page->ID == $maybe_child_page->post_parent ) {
					unset($this->pages_tmp[$key]);
					$children[$maybe_child_page->ID] = array(
						'object' => $maybe_child_page,
						'children' => $this->page_children( $maybe_child_page )
					);
				}
			}
		}

		return $children;
	}

	/**
	 * Recursively print a nested list of posts
	 *
	 * @param array $posts
	 * @return boolean
	 */
	public function nested_list( $posts )
	{
		// open container
		?><ul><?php

		foreach( $posts as $post ) {
			// set up local property
			$this->post = $post['object'];
			// open the list
			?><li id="post-<?php print $this->post->ID; ?>"><?php
				// print the row content
				$this->single_row( $this->post );
				// generate children list
				if ( count( $post['children'] ) ) {
					$this->nested_list( $post['children'] );
				}
			// close list
			?></li><?php
		}

		// close container
		?></ul><?php

		return true;
	}

	/**
	 * Print the content of one post row
	 *
	 * @param stdClass $a_post
	 * @return boolean
	 */
	function single_row( $a_post )
	{
		global $post, $post_type;

		$global_post = $post;
		$post = $a_post;
		setup_postdata( $post );

		$post_type_object = get_post_type_object( $post->post_type );
		$can_edit_post = current_user_can( $post_type_object->cap->edit_post, $post->ID );

		if ( $can_edit_post ) {
			?><div><?php
				$this->post_title();
				$this->post_status();
				$this->post_trash();
			?></div><?php
		}

		$post = $global_post;
		
		return;
	}

	/**
	 * Print the post title (link)
	 */
	private function post_title()
	{
		// start rendering ?>
		<a href="<?php print $this->post_edit_url() ?>"><?php print _draft_or_post_title() ?></a><?php
	}

	/**
	 * Print post status buttons/text
	 */
	private function post_status()
	{
		switch ( $this->post->post_status ) {
			case 'draft':
			case 'publish':
				// render buttons ?>
				<a href="#draft"><?php _e( 'Draft' ) ?></a>
				<a href="#publish"><?php _e( 'Published' ) ?></a><?php
				break;
			case 'pending':
				// render text ?>
				<span><?php _e( 'Pending' ) ?></span><?php
				break;
			case 'future':
				// render text ?>
				<span><?php _e( 'Scheduled' ) ?></span><?php
				break;
			case 'trash':
				// render text ?>
				<span><?php _e( 'Trashed' ) ?></span><?php
				break;
		}
		
		// render current status hint ?>
		<input type="hidden" value="<?php print $this->post->post_status ?>" /><?php
	}

	/**
	 * Print post trash/delete markup
	 */
	private function post_trash()
	{
		// render button ?>
		<a href="#trash"><?php _e( 'Trash' ) ?></a><?php
	}

	/**
	 * Get post edit url
	 */
	private function post_edit_url()
	{
		return '?post_id=' . $this->post->ID;
	}
}

?>
