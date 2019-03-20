<?php
/*
Plugin Name: PK Photo Album
Plugin URI: http://github.com/praful/pk_wordpress_album_post_type
Description: Custom type for photo albums
Author: Praful Kapadia
Author URI: http://www.prafulkapadia.com
Version: 1.2

Copyright: (c) 2019 Praful Kapadia 
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
/*
For info about initialising plugins, see
See https://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to/25979#25979
*/
register_activation_hook(   __FILE__, array( 'PK_wordpress_album_post_type', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'PK_wordpress_album_post_type', 'on_deactivation' ) );
register_uninstall_hook(    __FILE__, array( 'PK_wordpress_album_post_type', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'PK_wordpress_album_post_type', 'init' ) );

class PK_wordpress_album_post_type {
	 protected static $instance;

    public static function init()
    {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }

    public static function on_activation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }

    public static function on_deactivation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }

    public static function on_uninstall()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        check_admin_referer( 'bulk-plugins' );

        // Important: Check if the file is the one
        // that was registered during the uninstall hook.
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }
	
	
	
	/** Frontend methods ******************************************************/
	
	
	/**
	 * Register the custom post type
   * 
   * For reference, see:
   * https://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function init_post_type() {
      $labels = array(
          'name' => 'Albums',
          'singular_name' => 'Album',
          'add_new' => 'Add New',
          'all_items' => 'All Albums',
          'add_new_item' => 'Add New Album',
          'edit_item' => 'Edit album',
          'new_item' => 'New Album',
          'view_item' => 'View Album',
          'search_items' => 'Search Albums',
          'not_found' =>  'No Albums found',
          'not_found_in_trash' => 'No Albums found in trash',
          'parent_item_colon' => 'Parent Album:',
          'menu_name' => 'Albums'
      );
      $args = array(
        'labels' => $labels,
        'description' => "Photo albums",
        'public' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-format-gallery',
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => array('title','editor','author','thumbnail','comments','revisions','page-attributes','post-formats', 'excerpt'),
        'has_archive' => 'albums',
        'rewrite' => array('slug' => 'albums', 'with_front' => false),
        // 'rewrite' => array('slug' => 'albums', 'with_front' => 'before-your-slug'),
        'query_var' => true,
        'can_export' => true,
        'show_in_rest' => true,
        'rest_base' => 'albums',
        'taxonomies' => array( 'category', 'post_tag' ),
      );
	    // register_post_type( 'album', array( 'public' => true, 'label' => 'Albums' ) );
      register_post_type('pk-photo-album', $args);
  }

	public function __construct() {
    // inita();
    add_action( 'init', array( &$this, 'init_post_type' ) );
    // add_action( 'after_switch_theme', array(&$this, 'my_rewrite_flush' ));
    // register_activation_hook( &$this, 'my_rewrite_flush');
	
		// if ( is_admin() ) {
		// 	add_action( 'admin_init', array( &$this, 'admin_init' ) );
		// }
  }

  // public function my_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    // init();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    // flush_rewrite_rules();
  // }
	
	/** Admin methods ******************************************************/
	
	
	/**
	 * Initialize the admin, adding actions to properly display and handle 
	 * the Book custom post type add/edit page
	 */
	// public function admin_init() {
		// global $pagenow;
		
		// if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {
			
		// 	add_action( 'add_meta_boxes', array( &$this, 'meta_boxes' ) );
		// 	add_filter( 'enter_title_here', array( &$this, 'enter_title_here' ), 1, 2 );
			
		// 	add_action( 'save_post', array( &$this, 'meta_boxes_save' ), 1, 2 );
		// }
	// }
	
	
		
}

// finally instantiate our plugin class and add it to the set of globals
$GLOBALS['pk_wordpress_album_post_type'] = new PK_wordpress_album_post_type();
?>