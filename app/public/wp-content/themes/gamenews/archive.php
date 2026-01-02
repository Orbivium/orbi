<?php
get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
            echo '<div class="card-grid">';
            /* Start the Loop */
            /* Start the Loop */
            $post_count = 0;
            while ( have_posts() ) :
                the_post();
                $post_count++;
                get_template_part( 'template-parts/content-card' );

                // Inject Ad every 6 posts
                if ( $post_count % 6 === 0 && function_exists('oyunhaber_display_in_feed_ad') ) {
                    oyunhaber_display_in_feed_ad();
                }
            endwhile;
            echo '</div>'; // .card-grid

			the_posts_navigation();

		else :
            echo '<p>' . esc_html__( 'No posts found.', 'oyunhaber' ) . '</p>';
		endif;
		?>
        </div>
	</main><!-- #primary -->

<?php
get_footer();
