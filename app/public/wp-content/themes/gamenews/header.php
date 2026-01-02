<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="container header-container">
			<!-- Brand / Logo -->
			<div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    $logo_path = get_template_directory() . '/assets/images/fonts/logo.svg';
                    if ( file_exists( $logo_path ) ) {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="custom-logo-link">
                            <?php echo file_get_contents( $logo_path ); ?>
                        </a>
                        <?php
                    } else {
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">Orbi</a></h1>
                        <?php
                    }
                }
                ?>
			</div>

            <!-- Desktop Navigation (Hidden on Mobile) -->
			<nav id="site-navigation" class="main-navigation desktop-nav hidden-mobile">
                <ul>
                    <?php
                    // Platformları Ana Menü Olarak Listele (Mevcut Kod)
                    $terms = get_terms( array('taxonomy' => 'platform', 'hide_empty' => false) );

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        $order = array( 'genel', 'mobil', 'pc', 'playstation', 'xbox', 'nintendo' );
                        $sorted_terms = array();
                        $term_map = array();

                        foreach ( $terms as $term ) { $term_map[ $term->slug ] = $term; }
                        foreach ( $order as $slug ) {
                            if ( isset( $term_map[ $slug ] ) ) { $sorted_terms[] = $term_map[ $slug ]; unset( $term_map[ $slug ] ); }
                        }
                        foreach ( $term_map as $term ) { $sorted_terms[] = $term; }

                        foreach ( $sorted_terms as $term ) {
                            $term_link = get_term_link( $term );
                            if ( is_wp_error( $term_link ) ) { continue; }
                            
                             // Check for logo
                            $logo_html = '';
                            $theme_dir = get_template_directory();
                            $logo_path_rel = '/assets/images/platforms/' . $term->slug;
                            
                            if ( file_exists( $theme_dir . $logo_path_rel . '.svg' ) ) {
                                $logo_url = get_template_directory_uri() . $logo_path_rel . '.svg';
                                $logo_html = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $term->name ) . '" class="platform-menu-logo" />';
                            } elseif ( file_exists( $theme_dir . $logo_path_rel . '.png' ) ) {
                                $logo_url = get_template_directory_uri() . $logo_path_rel . '.png';
                                $logo_html = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $term->name ) . '" class="platform-menu-logo" />';
                            }

                            // Sub-menu Data
                            $base_filter_url = get_term_link( $term );
                            $sub_menu_items = array(
                                'Tümü'        => $base_filter_url,
                                'Haberler'    => add_query_arg( 'filter_type', 'news', $base_filter_url ),
                                'İncelemeler' => add_query_arg( 'filter_type', 'reviews', $base_filter_url ),
                                'Kılavuz'     => add_query_arg( 'filter_cat', 'rehberler', $base_filter_url ),
                            );

                            // Platform Extras
                            if ( $term->slug === 'playstation' ) $sub_menu_items['PS Plus'] = add_query_arg( 'filter_tag', 'ps-plus', $base_filter_url );
                            if ( $term->slug === 'xbox' ) $sub_menu_items['Game Pass'] = add_query_arg( 'filter_tag', 'game-pass', $base_filter_url );
                            if ( $term->slug === 'nintendo' ) $sub_menu_items['Özel Oyunlar'] = add_query_arg( 'filter_tag', 'ozel-oyunlar', $base_filter_url );
                            if ( $term->slug === 'mobil' ) $sub_menu_items['Ücretsiz Oyunlar'] = add_query_arg( 'filter_tag', 'ucretsiz-oyunlar', $base_filter_url );

                            $active_class = ( is_tax( 'platform', $term->slug ) || ( is_single() && has_term( $term->term_id, 'platform' ) ) ) ? 'current-platform' : '';

                            echo '<li class="platform-item ' . esc_attr( $active_class ) . ' platform-item-' . esc_attr( $term->slug ) . '">';
                            echo '<a href="' . esc_url( $term_link ) . '" class="platform-menu-link">' . $logo_html . '<span>' . esc_html( $term->name ) . '</span></a>';
                            
                            // Dropdown HTML
                            if ( ! empty( $sub_menu_items ) ) {
                                echo '<ul class="sub-menu">';
                                foreach ( $sub_menu_items as $label => $url ) {
                                    echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
                                }
                                echo '</ul>';
                            }

                            echo '</li>';
                        }
                    }
                    ?>
                </ul>
			</nav>

            <!-- Desktop Actions (Search + Auth) -->
            <div class="header-actions desktop-actions hidden-mobile">
                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>
                
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>" class="btn-login btn-profile"><span class="dashicons dashicons-admin-users" style="margin-right:5px;"></span>Profil</a>
                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn-login btn-logout">Çıkış</a>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url('/giris-yap/') ); ?>" class="btn-login">Giriş</a>
                    <?php if ( get_option( 'users_can_register' ) ) : ?>
                        <a href="<?php echo esc_url( home_url('/kayit-ol/') ); ?>" class="btn-login btn-register">Kayıt Ol</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Mobile Controls (Visible ONLY on Mobile) -->
            <div class="mobile-controls visible-mobile">
                <button id="mobile-search-toggle" class="icon-btn" aria-label="Arama">
                    <span class="dashicons dashicons-search"></span>
                </button>

                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>" class="icon-btn" aria-label="Profil">
                        <span class="dashicons dashicons-admin-users"></span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url('/giris-yap/') ); ?>" class="icon-btn" aria-label="Giriş">
                        <span class="dashicons dashicons-admin-users"></span>
                    </a>
                <?php endif; ?>

                <button id="mobile-menu-toggle" class="icon-btn" aria-label="Menü">
                    <span class="dashicons dashicons-menu-alt3"></span>
                </button>
            </div>
		</div>
        
        <!-- Mobile Search Overlay -->
        <div id="mobile-search-overlay" class="mobile-search-overlay">
            <div class="container">
                <?php get_search_form(); ?>
                <button id="close-search" class="close-btn" aria-label="Kapat"></button>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <nav id="mobile-navigation" class="mobile-drawer">
            <div class="drawer-header">
                <h3>Menü</h3>
                <button id="close-menu" class="close-btn" aria-label="Menüyü Kapat"></button>
            </div>
            <div class="drawer-content">
                <!-- Mobile Categories List -->
                <ul class="mobile-cat-list accordion-menu">
                    <?php
                     if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        foreach ( $sorted_terms as $term ) {
                            $term_link = get_term_link( $term );
                            
                            // 1. Sub-menu Definition (Same as Desktop)
                            $sub_menu_items = array();
                            $base_filter_url = $term_link;

                            $type_haber    = add_query_arg( 'filter_type', 'news', $base_filter_url );
                            $type_inceleme = add_query_arg( 'filter_type', 'reviews', $base_filter_url );
                            $cat_rehber    = add_query_arg( 'filter_cat', 'rehberler', $base_filter_url );

                            $sub_menu_items = array(
                                'Tümü'        => $base_filter_url,
                                'Haberler'    => $type_haber,
                                'İncelemeler' => $type_inceleme,
                                'Kılavuz'     => $cat_rehber,
                            );

                            if ( $term->slug === 'playstation' ) {
                                $sub_menu_items['PS Plus'] = add_query_arg( 'filter_tag', 'ps-plus', $base_filter_url );
                            } elseif ( $term->slug === 'xbox' ) {
                                $sub_menu_items['Game Pass'] = add_query_arg( 'filter_tag', 'game-pass', $base_filter_url );
                            } elseif ( $term->slug === 'nintendo' ) {
                                $sub_menu_items['Özel Oyunlar'] = add_query_arg( 'filter_tag', 'ozel-oyunlar', $base_filter_url );
                            } elseif ( $term->slug === 'mobil' ) {
                                $sub_menu_items['Ücretsiz Oyunlar'] = add_query_arg( 'filter_tag', 'ucretsiz-oyunlar', $base_filter_url );
                            }

                            // 2. Logo Logic
                            $logo_html = '';
                            $theme_dir = get_template_directory();
                            $logo_path_rel = '/assets/images/platforms/' . $term->slug;
                            
                            if ( file_exists( $theme_dir . $logo_path_rel . '.svg' ) ) {
                                $logo_url = get_template_directory_uri() . $logo_path_rel . '.svg';
                                $logo_html = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $term->name ) . '" class="mobile-menu-icon" />';
                            } elseif ( file_exists( $theme_dir . $logo_path_rel . '.png' ) ) {
                                $logo_url = get_template_directory_uri() . $logo_path_rel . '.png';
                                $logo_html = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $term->name ) . '" class="mobile-menu-icon" />';
                            } else {
                                $icon_class = oyunhaber_get_platform_icon( $term->slug );
                                $logo_html = '<span class="dashicons ' . esc_attr( $icon_class ) . ' mobile-menu-icon"></span>';
                            }

                            $active_class = ( is_tax( 'platform', $term->slug ) || ( is_single() && has_term( $term->term_id, 'platform' ) ) ) ? 'active' : '';

                            // 3. Render
                            echo '<li class="mobile-item-' . esc_attr($term->slug) . ' has-submenu">';
                            echo '<div class="mobile-link-wrap">';
                            echo '<a href="' . esc_url( $term_link ) . '" class="main-link ' . esc_attr($active_class) . '">';
                            echo '<span class="icon-wrap">' . $logo_html . '</span>';
                            echo '<span class="link-text">' . esc_html( $term->name ) . '</span>';
                            echo '</a>';
                            
                            // Toggle Button
                            echo '<button class="submenu-toggle" aria-label="Alt Menü"><span class="dashicons dashicons-arrow-down-alt2"></span></button>';
                            echo '</div>';

                            // Submenu List
                            if ( ! empty( $sub_menu_items ) ) {
                                echo '<ul class="mobile-sub-menu hidden">';
                                foreach ( $sub_menu_items as $label => $url ) {
                                    echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
                                }
                                echo '</ul>';
                            }

                            echo '</li>';
                        }
                     }
                    ?>
                </ul>
                
                <div class="drawer-actions">
                     <?php if ( ! is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( home_url('/giris-yap/') ); ?>" class="btn-drawer btn-drawer-login">
                            <span class="dashicons dashicons-admin-users"></span> Giriş Yap
                        </a>
                        <?php if ( get_option( 'users_can_register' ) ) : ?>
                            <a href="<?php echo esc_url( home_url('/kayit-ol/') ); ?>" class="btn-drawer btn-drawer-register">
                                <span class="dashicons dashicons-edit"></span> Kayıt Ol
                            </a>
                        <?php endif; ?>
                     <?php else: ?>
                        <a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>" class="btn-drawer btn-drawer-profile">
                            <span class="dashicons dashicons-id"></span> Profilim
                        </a>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn-drawer btn-drawer-logout">
                            <span class="dashicons dashicons-migrate"></span> Çıkış Yap
                        </a>
                     <?php endif; ?>
                </div>
            </div>
        </nav>
	</header><!-- #masthead -->
    
    <?php if ( function_exists('oyunhaber_display_ad') ) : ?>
        <div class="container">
            <?php oyunhaber_display_ad('header'); ?>
        </div>
    <?php endif; ?>
