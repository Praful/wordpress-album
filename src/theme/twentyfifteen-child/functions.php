<?php

if (!defined("ALBUM_TYPE")) {
  define("ALBUM_TYPE", 'pk-photo-album');
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
//Required for child themes
function enqueue_parent_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
/**
 * Utility functions
 */

//Add this line if moving into separate plugin
//See https://www.elegantthemes.com/blog/tips-tricks/using-the-wordpress-debug-log
// if ( !function_exists('write_log')) {
function write_log ( $log )  {
  if ( is_array( $log ) || is_object( $log ) ) {
    error_log( print_r( $log, true ) );
  } else {
    error_log( $log );
  }
}

function endsWith($haystack, $needle) {
  // search forward starting from end minus needle length characters
  if ($needle === '') {
      return true;
  }
  $diff = \strlen($haystack) - \strlen($needle);
  return $diff >= 0 && strpos($haystack, $needle, $diff) !== false;
}


// Stop WordPress from modifying .htaccess permalink rules.
// This is required because for prafulkapadia.com, we've added extra lines
// to fix an issue with basic auth on servers running FastCGI:
//   RewriteCond %{HTTP:Authorization} ^(.+)$
//   RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
// See https://github.com/WP-API/Basic-Auth/blob/c1a5e9524ea9107aadcf5dfdc04d45b32240d76c/README.md
add_filter('flush_rewrite_rules_hard','__return_false');

/**
 * Query to show albums in alphabetical order.
 * For Home page show posts and albums in modified date order.
 */

add_action('pre_get_posts','album_query');

//Change query to show all albums in alphabetical order
function album_query($query) {
  //gets the global query var object
  // global $wp_query;
  
  // write_log($wp_query);
  // write_log($query);

  // write_log('post type');
  // write_log(get_post_type());
  // write_log('is admin');
  // write_log(is_admin());
  // write_log('is album');
  // write_log(is_post_type_archive(ALBUM_TYPE));
  // write_log('is main query');
  // write_log($query->is_main_query());

  // if ( !is_admin() &&  is_post_type_archive(ALBUM_TYPE) && $query->is_main_query() ) {
  if ( !is_admin() &&  $query->is_main_query() ) {
    if (is_post_type_archive(ALBUM_TYPE)){
      $query->set('post_type', ALBUM_TYPE);
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', '-1');
    } else if ($query-> is_home()) {
      $query->set( 'post_type', array('post', ALBUM_TYPE) );
      $query->set('orderby', 'modified');
    }
  }
}

/**
 * Create post extract and determine if "Continue reading" and/or "View Photos"
 * continuation links are required
 */

add_action( 'after_setup_theme', 'child_theme_setup' );

function child_theme_setup() {
  // write_log('remove filter');
  // override parent theme's 'more' text for excerpts
  remove_filter( 'excerpt_more', 'twentyfifteen_excerpt_more' ); 
  // remove_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );
}

//copied from template-tags.php then amended for photos
function twentyfifteen_child_excerpt_more( $more_text ) {
  $link = sprintf(
    '<a href="%1$s" class="more-link">%2$s</a>',
    esc_url( get_permalink( get_the_ID() ) ),
    /* translators: %s: Name of current post */
    sprintf( __( '%s %s', 'twentyfifteen' ), $more_text, '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
  );
  // return ' &hellip; ' . $link;
  return ' ' . $link;
}

//Don't use filter because we're constructing the excerpt and adding the more part below.
// add_filter( 'excerpt_more', 'twentyfifteen_child_excerpt_more' );

add_filter('get_the_excerpt', 'twentyfifteen_child_get_excerpt');

//display read more link when there is a manually entered exceprt
function twentyfifteen_child_get_excerpt($excerpt) {
  // if (has_excerpt() && !is_attachment()) {
  //     $excerpt .= '&hellip; <a href="' . get_permalink() . '">Read More</a>';
  // }
  global $post;
  $excerpt = wp_trim_excerpt(); 

  //remove [...] if it's at the end since we'll add "..." before Continue reading
  $more_text = '';
  $ellipsis = '&hellip;';
  $ellispis_brackets = '[' . $ellipsis . ']';

  $has_more_indicator = strpos($post->post_content, '<!--more-->') !==false;
  if (endsWith($excerpt, $ellispis_brackets) || $has_more_indicator) {
    $more_text = 'Continue reading';
    $new_elipsis = ' ' . $ellipsis . ' ';
    if ($has_more_indicator) {
      $excerpt = $excerpt . $new_elipsis;
    } else {
      $excerpt = str_replace($ellispis_brackets, $new_elipsis, $excerpt);
    }
  }

  if (gallery_shortcode_exists() || has_photos()){
    if ($more_text == ''){
      $more_text = 'View photos';
    } else {
      $more_text = $more_text . ' / view photos';
    }
  }
  
  if ($more_text != '') $excerpt = $excerpt . twentyfifteen_child_excerpt_more($more_text);
  return $excerpt;
}

function has_photos(){
  global $post;
  //the "!== false" instead of "=== true" is a feature of strpos/php
  if (strpos($post->post_content,'<!-- wp:gallery') !== false) return true;
  if (strpos($post->post_content,'<!-- wp:image') !== false) return true;
  if (strpos($post->post_content,'<img') !== false) return true;
  return false;
}

function gallery_shortcode_exists(){
  global $post;

  # Check the content for an instance of [gallery] with or without arguments
  $pattern = get_shortcode_regex();
  if(
      preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
      && array_key_exists( 2, $matches )
      && in_array( 'gallery', $matches[2] )
  )
      return true;

  # Sourced from http://codex.wordpress.org/Function_Reference/get_shortcode_regex
}

/** 
* Add albums to the Recent Posts list in sidebar
* See https://wordpress.stackexchange.com/questions/241060/display-custom-post-type-in-recent-posts
*/

add_filter( 'widget_posts_args', 'filter_recent_get_posts' );

function filter_recent_get_posts($params) {
  // write_log('filter recent get posts');

  // Pass array of all post types we want to show in the Recent Posts widget. We're
  // passing two post types: the default ("post") as well as our album custom post type.
  $params['post_type'] = array( 'post', ALBUM_TYPE);

  // https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
  $params['orderby'] = 'modified';

  return $params;
}

/**
 * Customize Adjacent Post Link Order
 * See https://wordpress.stackexchange.com/questions/73190/can-the-next-prev-post-links-be-ordered-by-menu-order-or-by-a-meta-key
 */

function is_album_sql_being_requested(){
  return ( is_main_query() && is_singular(ALBUM_TYPE) );
}

//Return SQL WHERE clause for getting next/previous post
function album_where_clause($sql, $previous_next){
  if (!is_album_sql_being_requested())
    return $sql;

  $the_post = get_post( get_the_ID() );
  $comparator = ($previous_next == 'previous' ? '<' : '>');
  return sprintf("WHERE p.post_title %s '%s' AND p.post_type = '%s' AND p.post_status = '%s'", $comparator, $the_post->post_title, ALBUM_TYPE, 'publish' );
}

add_filter( 'get_next_post_where', 'next_album_where_clause' );
add_filter( 'get_previous_post_where', 'previous_album_where_clause' );

function previous_album_where_clause($sql) {
  return album_where_clause($sql, 'previous');
}

function next_album_where_clause($sql) {
  return album_where_clause($sql, 'next');
}

//Return SQL ORDER BY clause for getting next/previous post
function album_sort_clause($sql, $previous_next) {
  if (!is_album_sql_being_requested())
    return $sql;

  $direction = ($previous_next == 'previous' ? 'DESC' : 'ASC');
  return sprintf("ORDER BY p.post_title %s LIMIT 1", $direction);
}

add_filter( 'get_next_post_sort', 'next_album_sort_clause' );
add_filter( 'get_previous_post_sort', 'previous_album_sort_clause' );

function next_album_sort_clause($sql) {
  return album_sort_clause($sql, 'next');
}

function previous_album_sort_clause($sql) {
  return album_sort_clause($sql, 'previous');
}

?>