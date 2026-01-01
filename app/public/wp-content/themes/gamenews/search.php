<?php
get_header();
?>

	<main id="primary" class="site-main">
        <div class="container" style="padding: 40px 0;">

		<?php if ( have_posts() ) : ?>

			<header class="page-header" style="margin-bottom: 30px;">
				<h1 class="page-title" style="font-size: 2rem; border-left: 4px solid var(--accent-color); padding-left: 20px;">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( '"%s" için arama sonuçları', 'oyunhaber' ), '<span style="color:var(--accent-color)">' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
            echo '<div class="card-grid">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
                get_template_part( 'template-parts/content-card' );
			endwhile;
            echo '</div>';

			the_posts_navigation();

		else :
            ?>
            <div class="no-results-wrapper">
                <div class="no-results-card">
                    <div class="no-results-illustration">
                        <span class="dashicons dashicons-search"></span>
                        <div class="question-mark">?</div>
                    </div>
                    <h2 class="no-results-title">Eyvah! Sonuç Bulunamadı</h2>
                    <p class="no-results-text">
                        Aradığınız <strong>"<?php echo get_search_query(); ?>"</strong> kelimesiyle eşleşen bir içerik bulamadık. <br>
                        Belki farklı bir kelime ile tekrar denemek istersiniz?
                    </p>
                    
                    <div class="no-results-search-box">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="no-results-suggestions">
                        <span>Popüler aramalar:</span>
                        <?php
                            $tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 5));
                            if ($tags) {
                                foreach ($tags as $tag) {
                                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="suggestion-tag">#' . $tag->name . '</a>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
		endif;
		?>
        </div>
	</main><!-- #primary -->

    <style>
    /* No Results / 404 Styling */
    .no-results-wrapper {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }
    
    .no-results-card {
        background: #1e1e1e;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 50px;
        border-radius: 24px;
        text-align: center;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        position: relative;
        overflow: hidden;
    }

    /* Ambient Background Glow */
    .no-results-card::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle at center, rgba(255, 71, 87, 0.1) 0%, transparent 60%);
        animation: rotateGlow 20s linear infinite;
        pointer-events: none;
    }
    @keyframes rotateGlow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .no-results-illustration {
        position: relative;
        width: 100px;
        height: 100px;
        background: rgba(255, 71, 87, 0.1);
        border-radius: 50%;
        margin: 0 auto 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .no-results-illustration .dashicons {
        font-size: 50px;
        width: 50px;
        height: 50px;
        color: var(--accent-color);
    }
    
    .question-mark {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--accent-color);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-weight: bold;
        display: flex; /* Centering fix */
        align-items: center;
        justify-content: center;
        font-size: 18px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateY(0);} 40% {transform: translateY(-10px);} 60% {transform: translateY(-5px);} }

    .no-results-title {
        font-size: 2rem;
        margin-bottom: 15px;
        color: #fff;
        position: relative; 
    }

    .no-results-text {
        font-size: 1.1rem;
        color: #aaa;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .no-results-search-box {
        margin-bottom: 30px;
        position: relative;
    }
    /* Simple override for form inside card */
    .no-results-search-box form {
        max-width: 100%;
        margin: 0 auto;
        display: flex;
        position: relative;
    }

    .no-results-search-box form label {
        width: 100%;
    }
    
    .no-results-search-box .search-field {
        width: 100%;
        background: rgba(255,255,255,0.08); /* Lighter bg for input */
        border: 2px solid rgba(255,255,255,0.1);
        padding: 15px 50px 15px 20px; /* Space for button */
        border-radius: 50px;
        color: #fff;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s;
    }
    
    .no-results-search-box .search-field:focus {
        border-color: var(--accent-color);
        background: rgba(0,0,0,0.2);
        box-shadow: 0 0 15px rgba(255, 71, 87, 0.2);
    }

    .no-results-search-box .search-submit {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--accent-color);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .no-results-search-box .search-submit:hover {
        transform: translateY(-50%) scale(1.1);
        background: #fff;
        color: var(--accent-color);
    }

    .no-results-suggestions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        font-size: 0.9rem;
        color: #777;
        position: relative;
    }
    
    .suggestion-tag {
        color: var(--accent-color);
        background: rgba(255, 71, 87, 0.1);
        padding: 4px 10px;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .suggestion-tag:hover {
        background: var(--accent-color);
        color: #fff;
    }
    </style>

<?php
get_footer();
