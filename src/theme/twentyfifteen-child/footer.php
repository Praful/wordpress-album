<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

</div><!-- .site-content -->

<footer id="colophon" class="site-footer" role="contentinfo">
  <div class="site-info">
    <?php
				/**
				 * Fires before the Twenty Fifteen footer text for footer customization.
				 *
				 * @since Twenty Fifteen 1.0
				 */
				do_action( 'twentyfifteen_credits' );
		?>
    <?php
		$sep = '<span role="separator" aria-hidden="true"></span>';
		if ( function_exists( 'the_privacy_policy_link' ) ) {
			the_privacy_policy_link( '', $sep);
		}
		?>
    <a href="<?php echo esc_url( __( '/terms-of-use', 'twentyfifteen' ) ); ?>">
      <?php printf( __( 'Terms of use</a>%s', 'twentyfifteen' ),  $sep); 
		?>
      <?php printf( __( 'Copyright &copy 2007-%s Praful Kapadia.', 'twentyfifteen' ), date('Y') );
		?>
  </div><!-- .site-info -->
</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>

</html>