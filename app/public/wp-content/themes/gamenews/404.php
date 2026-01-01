<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package OyunHaber
 */

get_header();
?>

	<main id="primary" class="site-main">
        <div class="container" style="padding: 40px 0;">
			<div class="no-results-wrapper">
                <div class="no-results-card">
                    <div class="no-results-illustration">
                        <span class="dashicons dashicons-warning" style="color: #f39c12;"></span> <!-- Orange warning -->
                        <div class="question-mark" style="background:#f39c12;">404</div>
                    </div>
                    <h2 class="no-results-title">Sayfa Bulunamadı!</h2>
                    <p class="no-results-text">
                        Aradığınız sayfa silinmiş, taşınmış veya hiç var olmamış olabilir. <br>
                        Ana sayfaya dönmeyi ya da arama yapmayı deneyebilirsiniz.
                    </p>
                    
                    <div class="no-results-search-box">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="no-results-actions" style="margin-top: 20px;">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-home-return">
                            <span class="dashicons dashicons-house"></span> Anasayfaya Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
	</main><!-- #primary -->

    <style>
    /* No Results / 404 Styling (Duplicated for 404 independence) */
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

    .no-results-card::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle at center, rgba(255, 255, 255, 0.05) 0%, transparent 60%);
        animation: rotateGlow 20s linear infinite;
        pointer-events: none;
    }
    @keyframes rotateGlow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .no-results-illustration {
        position: relative;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.05);
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
        color: #ddd;
    }
    
    .question-mark {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--accent-color);
        color: white;
        width: 35px;
        height: 35px; /* Slightly bigger for 404 text */
        border-radius: 50%;
        font-weight: bold;
        display: flex; 
        align-items: center;
        justify-content: center;
        font-size: 14px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateY(0);} 40% {transform: translateY(-10px);} 60% {transform: translateY(-5px);} }

    .no-results-title {
        font-size: 2.5rem;
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

    /* Button Style */
    .btn-home-return {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 30px;
        background: var(--accent-color);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .btn-home-return:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 71, 87, 0.4);
        color: #fff;
    }
    </style>

<?php
get_footer();
