<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */


get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

    <?php if ( have_posts() ) : ?>

    <header class="page-header">
      <?php
				echo '<h1 class="page-title"> Photo albums </h1>';
				?>
    </header><!-- .page-header -->

    <?php

				// $args = array( 'post_type' => 'pk-photo-album', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1 );
				// $loop = new WP_Query( $args );
				echo '<div class="pk-photo-album-container">';

			// Start the Loop.
				while ( have_posts() ) :
					the_post();
				// while ( $loop->have_posts() ) : $loop->the_post();
					if ( has_post_thumbnail()) :?>
    <div class="pk-photo-album-image-container">
      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
        <?php the_post_thumbnail('large', ['id' => 'album-image']); ?></a>
      <div class="image-title"><?php the_title() ?></div>
    </div>
    <?php endif; ?>
    <?php
		endwhile;

				echo '</div>';
// End the loop.
//			endwhile;

// Previous/next page navigation.

// If no content, include the "No posts found" template.
else :
	get_template_part( 'content', 'none' );

endif;
?>

  </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>