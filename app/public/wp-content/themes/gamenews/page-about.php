<?php
/**
 * Template Name: Hakkımızda
 *
 * @package OyunHaber
 */

get_header(); 
?>

<div class="about-page-wrapper">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container hero-content">
            <h1 class="hero-title"><?php the_title(); ?> <span class="highlight">Nabzı</span></h1>
            <p class="hero-subtitle">
                <?php echo has_excerpt() ? get_the_excerpt() : 'Biz sadece haber yapmıyoruz; oyun kültürünü yaşıyoruz.'; ?>
            </p>
        </div>
        <div class="hero-bg-glow"></div>
    </section>

    <!-- Main Content -->
    <div class="container about-container">
        <div class="about-grid">
            
            <!-- Left: Text Content -->
            <div class="about-text-col">
                <div class="glass-card about-card">
                    <?php 
                    if ( have_posts() ) :
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile;
                    else : 
                    ?>
                        <h2>Biz Kimiz?</h2>
                        <p>Orbi, 2025 yılında tutkulu bir grup oyuncu tarafından kuruldu...</p>
                    <?php endif; ?>
                </div>

                <div class="glass-card stats-card">
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Yazar & Editör</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">İnceleme</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10K</span>
                        <span class="stat-label">Aylık Okuyucu</span>
                    </div>
                </div>
            </div>

            <!-- Right: Visual/Team -->
            <div class="about-visual-col">
                <div class="visual-wrapper">
                    <div class="image-card" <?php if(has_post_thumbnail()) echo 'style="background-image: url(' . get_the_post_thumbnail_url(null, 'large') . '); background-size: cover; background-position: center;"'; ?>>
                        <?php if(!has_post_thumbnail()): ?>
                        <!-- Placeholder only if no image -->
                        <div class="placeholder-icon"><span class="dashicons dashicons-groups"></span></div>
                        <?php endif; ?>
                    </div>
                    <div class="glass-card join-card-premium">
                        <div class="join-card-content">
                            <div class="join-icon-circle">
                                <span class="dashicons dashicons-email-alt"></span>
                            </div>
                            <h4>Reklam ve İletişim</h4>
                            <p>Markanızı oyuncu kitlemizle buluşturun. Özel projeler, sponsorluklar ve reklam çalışmaları için bizimle iletişime geçin.</p>
                            <a href="<?php echo home_url('/iletisim/'); ?>" class="btn-join-premium">
                                Bize Ulaşın <span class="dashicons dashicons-arrow-right-alt2" style="font-size:16px; width:16px; height:16px; margin-top:2px;"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Variables */
    :root {
        --a-bg: #0B0F14;
        --a-accent: #E11D48;
        --a-text: #E8EEF6;
        --a-text-muted: rgba(232, 238, 246, 0.7);
        --a-card-bg: rgba(255, 255, 255, 0.03);
        --a-border: rgba(255, 255, 255, 0.1);
    }

    .about-page-wrapper {
        background-color: var(--a-bg);
        color: var(--a-text);
        font-family: 'Segoe UI', system-ui, sans-serif;
        padding-bottom: 80px;
    }

    /* Hero */
    .about-hero {
        position: relative;
        padding: 100px 0;
        text-align: center;
        overflow: hidden;
        border-bottom: 1px solid var(--a-border);
        margin-bottom: 60px;
    }

    .hero-content { position: relative; z-index: 2; }

    .hero-title {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 20px;
        line-height: 1.1;
    }
    .hero-title .highlight {
        color: transparent;
        background: linear-gradient(to right, var(--a-accent), #ff6b6b);
        -webkit-background-clip: text;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: var(--a-text-muted);
        max-width: 700px;
        margin: 0 auto;
    }

    .hero-bg-glow {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 600px; height: 300px;
        background: var(--a-accent);
        filter: blur(150px);
        opacity: 0.15;
        z-index: 1;
        pointer-events: none;
    }

    /* Grid */
    .about-container { max-width: 1100px; }
    .about-grid {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 40px;
    }

    /* Text Col */
    .glass-card {
        background: var(--a-card-bg);
        border: 1px solid var(--a-border);
        border-radius: 20px; /* Consistent rounded corners */
        padding: 40px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px); /* Glass effect */
    }

    .about-card h2 { font-size: 2rem; margin-bottom: 20px; color: #fff; font-weight: 700; }
    .about-card h3 { font-size: 1.5rem; margin: 30px 0 15px; color: #fff; }
    .about-card p { line-height: 1.7; color: var(--a-text-muted); margin-bottom: 20px; font-size: 1.05rem; }

    /* Custom Check List for UL */
    .about-card ul {
        list-style: none; /* Remove default bullets */
        padding: 0;
        margin-bottom: 20px;
    }
    
    .about-card ul li {
        position: relative;
        padding-left: 35px; /* Space for icon */
        margin-bottom: 15px;
        color: #fff;
        font-size: 1.05rem;
        display: flex;
        align-items: center;
    }

    .about-card ul li::before {
        content: "\f147"; /* Dashicon 'yes' (check) */
        font-family: 'dashicons';
        position: absolute;
        left: 0;
        top: 2px; /* Slight adjustment */
        width: 24px;
        height: 24px;
        background: rgba(225, 29, 72, 0.15); /* Light red circle */
        color: var(--a-accent);
        border-radius: 50%;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Stats */
    .stats-card {
        display: flex;
        justify-content: space-around;
        padding: 30px;
        text-align: center;
    }
    .stat-number { display: block; font-size: 2.5rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 5px; }
    .stat-label { font-size: 0.9rem; color: var(--a-text-muted); text-transform: uppercase; letter-spacing: 1px; }

    /* Visual Col */
    .image-card {
        height: 300px;
        background: #1e1e24;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--a-border);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .image-card::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top right, rgba(0,0,0,0.8), transparent);
    }
    .placeholder-icon .dashicons { font-size: 64px; color: rgba(255,255,255,0.1); width: 64px; height: 64px; }

    /* Premium Join Card */
    .join-card-premium {
        position: relative;
        overflow: hidden;
        text-align: center;
        border: 1px solid rgba(225, 29, 72, 0.2); /* Subtle red border */
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5);
    }
    .join-card-premium::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(225, 29, 72, 0.08) 0%, transparent 60%);
        z-index: 0;
        pointer-events: none;
    }
    .join-card-content { position: relative; z-index: 1; }
    
    .join-icon-circle {
        width: 64px; height: 64px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, rgba(225, 29, 72, 0.1), rgba(225, 29, 72, 0.05));
        border: 1px solid rgba(225, 29, 72, 0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--a-accent);
        box-shadow: 0 0 20px rgba(225, 29, 72, 0.1);
    }
    .join-icon-circle .dashicons { font-size: 28px; width: 28px; height: 28px; }

    .join-card-premium h4 { font-size: 1.6rem; color: #fff; margin-bottom: 15px; font-weight: 800; letter-spacing: -0.5px; }
    .join-card-premium p { color: #ccc; font-size: 1rem; margin-bottom: 30px; line-height: 1.6; }

    .btn-join-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(90deg, var(--a-accent), #f43f5e);
        color: #fff;
        padding: 14px 35px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(225, 29, 72, 0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-join-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(225, 29, 72, 0.5);
    }

    @media (max-width: 900px) {
        .hero-title { font-size: 2.5rem; }
        .about-grid { grid-template-columns: 1fr; }
        .stats-card { flex-direction: column; gap: 30px; }
        .image-card { height: 250px; }
    }
</style>

<?php get_footer(); ?>
