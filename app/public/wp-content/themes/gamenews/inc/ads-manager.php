<?php
/**
 * Advertisement Management System
 * 
 * @package OyunHaber
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Ad Management Menu
 */
function oyunhaber_register_ads_menu() {
    add_menu_page(
        'Reklam Yönetimi', 
        'Reklam Alanları', 
        'manage_options', 
        'oyunhaber-ads', 
        'oyunhaber_ads_page_html', 
        'dashicons-megaphone', 
        60
    );
}
add_action('admin_menu', 'oyunhaber_register_ads_menu');

/**
 * Register Settings
 */
function oyunhaber_register_ad_settings() {
    $platforms = ['pc', 'playstation', 'xbox', 'nintendo', 'mobil'];
    $locations = ['header', 'home_below_slider', 'sidebar', 'single_top', 'single_bottom', 'footer', 'in_feed'];

    // Register Global
    foreach($locations as $loc) {
        register_setting('oyunhaber_ads_group', 'oyunhaber_ad_' . $loc);
    }

    // Register Platform Specifics
    foreach($platforms as $p) {
        foreach($locations as $loc) {
            register_setting('oyunhaber_ads_group', 'oyunhaber_ad_' . $loc . '_' . $p);
        }
    }
}
add_action('admin_init', 'oyunhaber_register_ad_settings');

/**
 * Admin Page HTML
 */
function oyunhaber_ads_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // Helpers
    $platforms = [
        'general' => ['name' => 'Genel (Varsayılan)', 'icon' => 'dashicons-admin-site', 'color' => '#ccc'],
        'pc' => ['name' => 'PC', 'icon' => 'dashicons-desktop', 'color' => '#0abde3'],
        'playstation' => ['name' => 'PlayStation', 'icon' => 'dashicons-games', 'color' => '#003791'],
        'xbox' => ['name' => 'Xbox', 'icon' => 'dashicons-cloud', 'color' => '#107c10'],
        'nintendo' => ['name' => 'Nintendo', 'icon' => 'dashicons-smiley', 'color' => '#e60012'],
        'mobil' => ['name' => 'Mobil', 'icon' => 'dashicons-smartphone', 'color' => '#ff9f43'],
    ];

    $locations = [
        'header' => 'Header (Üst Kısım)',
        'home_below_slider' => 'Slider Altı (Sadece Anasayfa/Arşiv)',
        'sidebar' => 'Sidebar (Yan Panel)',
        'single_top' => 'İçerik Detay - Üst',
        'single_bottom' => 'İçerik Detay - Alt',
        'footer' => 'Footer Üstü',
        'in_feed' => 'Liste İçi (Kart Arası)',
    ];

    // --- Actions ---

    // Fill Demo Data
    if ( isset($_POST['fill_demo_trigger']) && check_admin_referer('fill_demo_ads_action', 'fill_demo_nonce_field') ) {
        $placeholder_base = '<div class="premium-ad-placeholder"><span class="ad-icon dashicons %s"></span><span class="ad-text">%s</span></div>';
        
        foreach($platforms as $slug => $info) {
            $suffix = ($slug === 'general') ? '' : '_' . $slug;
            $label_suffix = ($slug === 'general') ? 'Sponsorlu Alan' : $info['name'] . ' Özel Reklam';
            
            foreach($locations as $loc_slug => $loc_name) {
                $final_key = 'oyunhaber_ad_' . $loc_slug . $suffix;
                $html = sprintf($placeholder_base, $info['icon'], $label_suffix);
                update_option($final_key, $html);
            }
        }
        echo '<div class="notice notice-success is-dismissible"><p>Tüm platform reklam verileri dolduruldu.</p></div>';
    }

    // Remove All
    if ( isset($_POST['remove_all_ads_trigger']) && check_admin_referer('remove_all_ads_action', 'remove_all_nonce_field') ) {
        foreach($platforms as $slug => $info) {
            $suffix = ($slug === 'general') ? '' : '_' . $slug;
            foreach($locations as $loc_slug => $loc_name) {
                update_option('oyunhaber_ad_' . $loc_slug . $suffix, '');
            }
        }
        echo '<div class="notice notice-info is-dismissible"><p>Tüm reklamlar temizlendi.</p></div>';
    }

    // --- Active Tab Logic ---
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    ?>
    
    <style>
        .oh-ads-wrapper { background: #1e1e1e; color: #ccc; padding: 30px; border-radius: 12px; margin-top: 20px; border: 1px solid #333; max-width: 1200px; }
        .oh-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ff4757; padding-bottom: 20px; margin-bottom: 30px; }
        .oh-header h1 { color: #fff; margin:0; display:flex; gap:10px; align-items:center; }
        .oh-actions { display:flex; gap:10px; }
        .oh-btn { padding: 8px 16px; border-radius: 4px; border:none; cursor:pointer; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:5px;}
        .oh-btn-secondary { background: #333; color: #ccc; border: 1px solid #444; }
        .oh-btn-danger { background: #e74c3c; color: #fff; }
        
        /* Tabs */
        .oh-tabs { display: flex; gap: 5px; margin-bottom: 20px; border-bottom: 1px solid #333; }
        .oh-tab { padding: 10px 20px; background: #2d2d2d; color: #888; text-decoration: none; border-radius: 8px 8px 0 0; border: 1px solid #333; border-bottom: none; transition: all 0.2s;}
        .oh-tab:hover { background: #333; color: #fff; }
        .oh-tab.active { background: #ff4757; color: #fff; font-weight: bold; }
        .oh-tab .dashicons { margin-right: 5px; }

        .oh-textarea { width: 100%; height: 100px; background: #111; color: #0f0; border: 1px solid #333; font-family: monospace; padding:10px; }
        .oh-card { background: #2d2d2d; border: 1px solid #444; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .oh-card h3 { margin-top:0; color:#fff; border-bottom:1px solid #444; padding-bottom:10px; margin-bottom:15px; }
        .oh-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        
        #wpcontent { background: #111111; }
    </style>

    <div class="wrap">
        <div class="oh-ads-wrapper">
             <div class="oh-header">
                <h1><span class="dashicons dashicons-megaphone"></span> Reklam Yönetimi</h1>
                <div class="oh-actions">
                    <form method="post" action="" style="display:inline;">
                        <?php wp_nonce_field('fill_demo_ads_action', 'fill_demo_nonce_field'); ?>
                        <input type="hidden" name="fill_demo_trigger" value="1">
                        <button class="oh-btn oh-btn-secondary"><span class="dashicons dashicons-download"></span> Demo Doldur</button>
                    </form>
                    <form method="post" action="" style="display:inline;" onsubmit="return confirm('Tüm reklamlar silinecek?');">
                        <?php wp_nonce_field('remove_all_ads_action', 'remove_all_nonce_field'); ?>
                        <input type="hidden" name="remove_all_ads_trigger" value="1">
                        <button class="oh-btn oh-btn-danger"><span class="dashicons dashicons-trash"></span> Temizle</button>
                    </form>
                </div>
            </div>

            <!-- Tabs -->
            <div class="oh-tabs">
                <?php foreach($platforms as $slug => $info): ?>
                    <a href="?page=oyunhaber-ads&tab=<?php echo $slug; ?>" class="oh-tab <?php echo ($active_tab === $slug) ? 'active' : ''; ?>">
                        <span class="dashicons <?php echo $info['icon']; ?>"></span> <?php echo $info['name']; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <form method="post" action="options.php">
                <?php 
                settings_fields('oyunhaber_ads_group'); 
                $suffix = ($active_tab === 'general') ? '' : '_' . $active_tab;
                
                // Active Info
                $current_info = $platforms[$active_tab];
                ?>
                
                <div style="margin-bottom: 20px; padding: 15px; background: <?php echo $current_info['color']; ?>22; border-left: 4px solid <?php echo $current_info['color']; ?>; border-radius: 4px;">
                    <p style="margin:0; color: #fff;">
                        <strong><?php echo $current_info['name']; ?></strong> için reklam ayarlarını düzenliyorsunuz. 
                        <?php if($active_tab !== 'general'): ?>
                            Bu alanları boş bırakırsanız "Genel" reklamlar gösterilir.
                        <?php endif; ?>
                    </p>
                </div>

                <div class="oh-grid">
                    <?php foreach($locations as $loc_slug => $loc_name): ?>
                        <div class="oh-card">
                            <h3><?php echo $loc_name; ?></h3>
                            <textarea name="oyunhaber_ad_<?php echo $loc_slug . $suffix; ?>" class="oh-textarea"><?php echo esc_textarea(get_option('oyunhaber_ad_' . $loc_slug . $suffix)); ?></textarea>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="oh-save-bar">
                    <?php submit_button('Ayarları Kaydet', 'primary oh-btn-primary', 'submit', false); ?>
                </div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Helper: Detect Current Platform
 */
function oyunhaber_get_current_platform_slug() {
    // 1. Single Post Check
    if ( is_single() ) {
        // Check Platform Term
        $terms = get_the_terms(get_the_ID(), 'platform');
        if ( $terms && ! is_wp_error($terms) ) {
            return $terms[0]->slug; // e.g., 'playstation'
        }
    }
    
    // 2. Taxonomy Archive Check
    if ( is_tax('platform') ) {
        $term = get_queried_object();
        return $term->slug;
    }
    
    // 3. Category Archive ? (Use prompt request)
    // If user creates a Category named 'PlayStation' ? Unlikely but possible.
    // For now we rely on the defined platforms.
    
    return false;
}

/**
 * Display Ad Helper Function
 */
function oyunhaber_display_ad($location) {
    // Determine suffix
    $suffix = '';
    $platform_slug = oyunhaber_get_current_platform_slug();
    
    // If we found a platform context, check if a specific ad exists
    if ( $platform_slug ) {
        // Valid defined platforms only to avoid random garbage
        $valid_platforms = ['pc', 'playstation', 'xbox', 'nintendo', 'mobil'];
        if ( in_array($platform_slug, $valid_platforms) ) {
            $specific_option = 'oyunhaber_ad_' . $location . '_' . $platform_slug;
            $specific_content = get_option($specific_option);
            
            if ( ! empty($specific_content) ) {
                $suffix = '_' . $platform_slug;
            }
        }
    }
    
    $final_option = 'oyunhaber_ad_' . $location . $suffix;
    $ad_code = get_option($final_option);

    if ( ! empty($ad_code) ) {
        $content = stripslashes($ad_code);

        // Placeholder Check
        if ( strpos($content, 'placehold.co') !== false || strpos($content, 'premium-ad-placeholder') !== false ) {
            // Keep premium look but don't override content if it's already html
            if ( strpos($content, 'premium-ad-placeholder') === false ) {
                 $content = '<div class="premium-ad-placeholder">
                    <span class="ad-icon dashicons dashicons-megaphone"></span>
                    <span class="ad-text">Sponsorlu Alan</span>
                </div>';
            }
        }

        echo '<div class="oyunhaber-ad-container ad-' . esc_attr($location) . '">';
        echo '<div class="ad-label">Sponsorlu Bağlantı</div>';
        echo '<div class="ad-content">' . $content . '</div>';
        echo '</div>';
    }
}

/**
 * Display In-Feed Ad Card (Wrapper for special card styling but uses generic system)
 */
function oyunhaber_display_in_feed_ad() {
    // Reuse logic to find content
    $suffix = '';
    $platform_slug = oyunhaber_get_current_platform_slug();
     if ( $platform_slug ) {
        $valid_platforms = ['pc', 'playstation', 'xbox', 'nintendo', 'mobil'];
        if ( in_array($platform_slug, $valid_platforms) ) {
            $specific_option = 'oyunhaber_ad_in_feed_' . $platform_slug;
            $specific_content = get_option($specific_option);
            if ( ! empty($specific_content) ) $suffix = '_' . $platform_slug;
        }
    }
    
    $ad_code = get_option('oyunhaber_ad_in_feed' . $suffix);

    if ( ! empty($ad_code) ) {
        $content = stripslashes($ad_code);
        
        $wrapper_class = 'latest-card ad-card-in-feed';
        $wrapper_style = 'justify-content:center; align-items:center; min-height:300px;';
        
        $is_placeholder = (strpos($content, 'premium-ad-placeholder') !== false || strpos($content, 'placehold.co') !== false);

        if ( $is_placeholder ) {
            echo '<div class="' . $wrapper_class . '" style="' . $wrapper_style . ' background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">';
                echo '<div style="text-align:center;">';
                    // Extract icon if present or use default
                    preg_match('/dashicons-([a-z-]+)/', $content, $matches);
                    $icon_class = isset($matches[0]) ? $matches[0] : 'dashicons-megaphone';
                    
                    echo '<span class="dashicons ' . $icon_class . '" style="font-size: 30px; width: 30px; height: 30px; color: var(--accent-color); opacity: 0.5; margin-bottom: 10px;"></span>';
                    echo '<div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; opacity: 0.7; color: var(--text-secondary);">Sponsorlu Alan</div>';
                echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="' . $wrapper_class . '" style="' . $wrapper_style . ' background: #1a1a1a; border: 1px solid rgba(255,255,255,0.05);">';
                echo '<div class="ad-label" style="position: absolute; top: 10px; right: 10px; font-size: 9px; color: #666; text-transform: uppercase;">Sponsorlu</div>';
                echo '<div style="width:100%; overflow:hidden; display:flex; justify-content:center;">' . $content . '</div>';
            echo '</div>';
        }
    }
}
