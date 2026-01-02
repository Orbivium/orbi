<?php
/**
 * Advanced Demo Data Importer for OyunHaber
 * Populates the site with extensive content for all platforms.
 */

function oyunhaber_import_demo_data() {
    // Check if V5 is imported (Bump version to force run)
    if ( get_option( 'oyunhaber_demo_v5_imported' ) ) {
        return;
    }

    // Required for media_sideload_image
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $user_id = get_current_user_id();

    // Platforms and their associated keywords for images
    $segments = [
        'PC' => ['steam', 'pc gaming', 'nvidia', 'keyboard', 'esports'],
        'PlayStation' => ['playstation', 'controller', 'ps5', 'sony'],
        'XBOX' => ['xbox', 'halo', 'green gaming'],
        'Nintendo' => ['mario', 'nintendo', 'zelda', 'switch'],
        'Mobil' => ['mobile game', 'iphone gaming', 'android game', 'tablet game'],
    ];

    // Post Types
    $types = ['news', 'reviews', 'videos'];

    // Topics generator
    $topics = ['İnceleme', 'Sızıntı', 'Fragman', 'Güncelleme', 'Rehber', 'Turnuva', 'Röportaj', 'Analiz'];
    
    // Game Titles pool
    $games = [
        'GTA VI', 'Elder Scrolls VI', 'Starfield', 'Valorant', 'CS:GO 2', 'League of Legends', 
        'Dota 2', 'Elden Ring', 'God of War', 'Spider-Man 2', 'Mario Kart', 'Zelda: TotK',
        'Metroid Prime', 'Halo Infinite', 'Forza Horizon', 'Gran Turismo', 'Tekken 8', 
        'Street Fighter 6', 'Mortal Kombat 1', 'Final Fantasy XVI', 'Diablo IV', 'Overwatch 2',
        'Minecraft', 'Roblox', 'Fortnite', 'PUBG Mobile', 'Call of Duty: Warzone', 'FIFA 25'
    ];

    $count_created = 0;

    // Generate 50 posts
    for ($i = 0; $i < 50; $i++) {
        // Random Selection
        $game = $games[array_rand($games)];
        $topic = $topics[array_rand($topics)];
        $platform_name = array_rand($segments);
        $pt = $types[array_rand($types)];
        
        // Build Title
        $title = "$game: $topic Hakkında Bilmeniz Gerekenler - Bölüm " . ($i+1);
        if ($pt == 'reviews') $title = "$game İncelemesi: Beklemeye Değer Mi?";
        if ($pt == 'videos') $title = "$game Oynanış Videosu ve İlk Bakış";

        // Check duplicate
         $existing = get_page_by_title( $title, OBJECT, $pt );
         if ( $existing ) continue;

        // Content
        $content = '<!-- wp:paragraph --><p><strong>' . $game . '</strong> dünyası genişlemeye devam ediyor. ' . $topic . ' konusunda son gelişmeleri sizin için derledik.</p><!-- /wp:paragraph -->
        <!-- wp:heading --><h2>Detaylar ve Özellikler</h2><!-- /wp:heading -->
        <!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><!-- /wp:paragraph -->
        <!-- wp:paragraph --><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><!-- /wp:paragraph -->
        <!-- wp:quote --><blockquote class="wp-block-quote"><p>Oyun dünyasında dengeleri değiştirecek bir hamle.</p></blockquote><!-- /wp:quote -->
        <!-- wp:paragraph --><p>Daha fazla detay için takipte kalın.</p><!-- /wp:paragraph -->';

        // Youtube URL for videos
        $video_url = '';
        if ($pt == 'videos') $video_url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // Placeholder

        // Create Post
        $post_data = array(
            'post_title'    => $title,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_type'     => $pt,
            'post_author'   => $user_id,
        );

        $post_id = wp_insert_post( $post_data );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            // Set Platform Taxonomy
            $term = get_term_by( 'name', $platform_name, 'platform' );
            if ( $term ) {
                wp_set_object_terms( $post_id, $term->term_id, 'platform' );
            }
            
            // Set Video URL
            if ( $video_url ) {
                update_post_meta( $post_id, '_oyunhaber_video_url', $video_url );
            }

            // Image
            $keywords = $segments[$platform_name];
            $keyword = $keywords[array_rand($keywords)];
            $image_url = 'https://loremflickr.com/800/600/' . urlencode($keyword) . '/all?random=' . $i;

            $desc = "Demo Image $i";
            $image_id = media_sideload_image( $image_url, $post_id, $desc, 'id' );

            if ( ! is_wp_error( $image_id ) ) {
                set_post_thumbnail( $post_id, $image_id );
            }
            $count_created++;
        }
    }

    // ALSO POPULATE ADS AUTOMATICALLY (Simulate button click)
    // We reuse the logic from ads-manager but we need to include it or duplicate logic here.
    // Instead of duplication, let's just do direct DB updates for all platforms
    $ad_platforms = array('general', 'pc', 'playstation', 'xbox', 'nintendo', 'mobil');
    $ad_locations = array('header', 'home_below_slider', 'sidebar', 'single_top', 'single_bottom', 'footer', 'in_feed');
    $icons = [
        'general' => 'dashicons-admin-site', 'pc' => 'dashicons-desktop', 
        'playstation' => 'dashicons-games', 'xbox' => 'dashicons-cloud', 
        'nintendo' => 'dashicons-smiley', 'mobil' => 'dashicons-smartphone'
    ];

    foreach($ad_platforms as $p) {
        $suffix = ($p === 'general') ? '' : '_' . $p;
        $icon = $icons[$p] ?? 'dashicons-megaphone';
        $label = ucfirst($p) . ' Reklamı';
        
        foreach($ad_locations as $loc) {
            $key = 'oyunhaber_ad_' . $loc . $suffix;
            // Only update if empty to avoid overwriting custom stuff (though user asked to fill completely)
            // User said "fill completely", so we overwrite or fill. let's fill.
            
            $html = '<div class="premium-ad-placeholder"><span class="ad-icon dashicons '.$icon.'"></span><span class="ad-text">'.$label.'</span></div>';
            update_option($key, $html);
        }
    }

    // Mark as imported
    update_option( 'oyunhaber_demo_v5_imported', '1' );
    flush_rewrite_rules();
}
add_action( 'admin_init', 'oyunhaber_import_demo_data' );
