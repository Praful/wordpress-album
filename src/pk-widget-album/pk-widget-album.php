<?php
/*
Plugin Name: PK album widget
Description: Album widget to show links to album related pages 
Author: Praful Kapadia
Author URI: https://prafulkapadia.com
Version: 1.2
*/
/* Start Adding Functions Below this Line */
	
//This plugin is no longer used. This functionality, a list of menu items on sidebar,
//can be more easily achieved using navigation menus in the customise menu.

// Register and load the widget
function pk_load_album_widget() {
    register_widget( 'pk_widget_album' );
}
add_action( 'widgets_init', 'pk_load_album_widget' );
 
class PK_Widget_Album extends WP_Widget {

	/**
	 * Sets up a new Album widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => __( 'Display album related links' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'album', __( 'Album' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current Album widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Meta widget instance.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Album' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
			?>
<ul>

  <li>
    <a href="<?php echo esc_url( __( '/albums/featured', 'twentyfifteen' ) ); ?>">
      <?php printf( __( 'Featured photos</a>', 'twentyfifteen' )); 
		?>
  </li>
  <li>
    <a href="<?php echo esc_url( __( '/albums/people', 'twentyfifteen' ) ); ?>">
      <?php printf( __( 'People</a>', 'twentyfifteen' )); 
		?>
  </li>
  <li>
    <a href="<?php echo esc_url( __( '/albums', 'twentyfifteen' ) ); ?>">
      <?php printf( __( 'All photo albums</a>', 'twentyfifteen' )); 
		?>
  </li>
</ul>
<?php

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Meta widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Meta widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}