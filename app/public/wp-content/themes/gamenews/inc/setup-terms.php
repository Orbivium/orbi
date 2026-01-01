<?php
/**
 * Setup Default Terms (Categories & Tags) for Navigation logic
 *
 * @package OyunHaber
 */

function oyunhaber_ensure_default_terms() {
    // 1. Ensure 'Rehberler' Category exists
    if ( ! term_exists( 'rehberler', 'category' ) ) {
        wp_insert_term(
            'Rehberler',
            'category',
            array(
                'slug'        => 'rehberler',
                'description' => 'Oyun rehberleri, ipuçları ve taktikler.'
            )
        );
    }
    
    // 2. Ensure Special Tags exist
    $tags = array(
        'ps-plus'          => 'PS Plus',
        'game-pass'        => 'Game Pass',
        'ozel-oyunlar'     => 'Özel Oyunlar',
        'ucretsiz-oyunlar' => 'Ücretsiz Oyunlar'
    );

    foreach ( $tags as $slug => $name ) {
        if ( ! term_exists( $slug, 'post_tag' ) ) {
            wp_insert_term(
                $name,
                'post_tag',
                array(
                    'slug' => $slug
                )
            );
        }
    }
    // 3. Ensure Content Types exist (Haberler, İncelemeler)
    if ( taxonomy_exists( 'content_type' ) ) {
        $c_types = array(
            'haberler'    => 'Haberler',
            'incelemeler' => 'İncelemeler'
        );
        foreach ( $c_types as $slug => $name ) {
            if ( ! term_exists( $slug, 'content_type' ) ) {
                wp_insert_term(
                    $name,
                    'content_type',
                    array( 'slug' => $slug )
                );
            }
        }
    }
}
add_action( 'admin_init', 'oyunhaber_ensure_default_terms' );
