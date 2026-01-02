<?php
/**
 * Contact Messages CPT
 * Stores contact form submissions as posts in Admin.
 * 
 * @package OyunHaber
 */

function oyunhaber_register_contact_messages_cpt() {
    $labels = array(
        'name'               => 'Gelen Kutusu',
        'singular_name'      => 'Mesaj',
        'menu_name'          => 'İletişim / Mesajlar',
        'name_admin_bar'     => 'Mesaj',
        'add_new'            => 'Yeni Ekle',
        'add_new_item'       => 'Yeni Mesaj Ekle',
        'new_item'           => 'Yeni Mesaj',
        'edit_item'          => 'Mesajı Görüntüle',
        'view_item'          => 'Mesajı Gör',
        'all_items'          => 'Gelen Kutusu',
        'search_items'       => 'Mesaj Ara',
        'not_found'          => 'Mesaj bulunamadı.',
        'not_found_in_trash' => 'Çöp kutusunda mesaj yok.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => false,
        'capability_type'    => 'post', // Moderators have edit_posts, so they can access this
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 26, // Below Comments
        'menu_icon'          => 'dashicons-email-alt',
        'supports'           => array( 'title', 'editor', 'custom-fields' ), // Title = Subject, Editor = Message
        'capabilities' => array(
            'create_posts' => 'do_not_allow', // Disable "Add New" button essentially
        ),
        'map_meta_cap' => true, 
    );

    register_post_type( 'contact_message', $args );
}
add_action( 'init', 'oyunhaber_register_contact_messages_cpt' );

/**
 * Update Messages Columns
 */
function oyunhaber_contact_custom_columns($columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Konu',
        'message_sender' => 'Gönderen',
        'message_email' => 'E-Posta',
        'date' => 'Tarih'
    );
    return $new_columns;
}
add_filter('manage_contact_message_posts_columns', 'oyunhaber_contact_custom_columns');

function oyunhaber_contact_custom_columns_data($column, $post_id) {
    switch ($column) {
        case 'message_sender':
            echo get_post_meta($post_id, 'contact_name', true);
            break;
        case 'message_email':
            echo '<a href="mailto:'.get_post_meta($post_id, 'contact_email', true).'">'.get_post_meta($post_id, 'contact_email', true).'</a>';
            break;
    }
}
add_action('manage_contact_message_posts_custom_column', 'oyunhaber_contact_custom_columns_data', 10, 2);

/**
 * Remove "Add New" for Contact Messages if somehow visible
 */
function oyunhaber_disable_new_contact_message() {
    global $submenu;
    unset($submenu['edit.php?post_type=contact_message'][10]); // Removes 'Add New'
}
add_action('admin_menu', 'oyunhaber_disable_new_contact_message');

/**
 * Custom Meta Box for Message Details
 */
function oyunhaber_contact_message_meta_box() {
    add_meta_box(
        'contact_message_details',
        'Mesaj Detayları',
        'oyunhaber_contact_message_meta_box_callback',
        'contact_message',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'oyunhaber_contact_message_meta_box');

function oyunhaber_contact_message_meta_box_callback($post) {
    $sender_name = get_post_meta($post->ID, 'contact_name', true);
    $sender_email = get_post_meta($post->ID, 'contact_email', true);
    $sender_ip = get_post_meta($post->ID, 'contact_ip', true);
    $content = get_post_field( 'post_content', $post->ID );
    ?>
    <style>
        .msg-detail-wrap { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
        .msg-header { background: #f0f0f1; border: 1px solid #c3c4c7; padding: 20px; border-radius: 4px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.04); }
        .msg-row { margin-bottom: 15px; display: flex; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .msg-row:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .msg-label { font-weight: 600; width: 120px; color: #50575e; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .msg-value { font-size: 14px; color: #1d2327; flex: 1; }
        .msg-value a { text-decoration: none; color: #2271b1; }
        .msg-body-box { background: #fff; border: 1px solid #c3c4c7; padding: 30px; border-radius: 4px; font-size: 15px; line-height: 1.6; color: #1d2327; min-height: 200px; box-shadow: 0 1px 1px rgba(0,0,0,0.04); white-space: pre-wrap; }
        
        #postdivrich { display: none; } /* Hide default editor */
        #titlediv { margin-bottom: 20px; }
        #title { background: #fff; border: none; font-size: 24px; box-shadow: none; padding: 0; cursor: default; }
    </style>

    <div class="msg-detail-wrap">
        <div class="msg-header">
            <div class="msg-row">
                <div class="msg-label">Gönderen</div>
                <div class="msg-value"><strong><?php echo esc_html($sender_name); ?></strong></div>
            </div>
            <div class="msg-row">
                <div class="msg-label">E-Posta</div>
                <div class="msg-value">
                    <a href="mailto:<?php echo esc_attr($sender_email); ?>">
                        <span class="dashicons dashicons-email" style="font-size: 16px; margin-right: 5px; vertical-align: text-top;"></span>
                        <?php echo esc_html($sender_email); ?>
                    </a>
                </div>
            </div>
            <div class="msg-row">
                <div class="msg-label">IP Adresi</div>
                <div class="msg-value"><code><?php echo esc_html($sender_ip); ?></code></div>
            </div>
            <div class="msg-row">
                <div class="msg-label">Tarih</div>
                <div class="msg-value"><?php echo get_the_date('j F Y, H:i', $post->ID); ?></div>
            </div>
        </div>

        <h3 style="margin-left:5px; margin-bottom:10px;">Mesaj İçeriği:</h3>
        <div class="msg-body-box">
            <?php echo esc_html($content); ?>
        </div>
        
        <div style="margin-top: 20px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
            <button onclick="navigator.clipboard.writeText('<?php echo esc_js($sender_email); ?>'); alert('Gönderen e-postası kopyalandı:\n<?php echo esc_js($sender_email); ?>');" class="button button-secondary button-hero">
                Gönderen E-postasını Kopyala
            </button>
            <a href="https://webmail.turkticaret.net" target="_blank" class="button button-primary button-hero">
                Yanıtla (Webmail) <span class="dashicons dashicons-external" style="margin-top:4px;"></span>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Remove Default Supports to clean UI
 */
function oyunhaber_clean_contact_ui() {
    remove_post_type_support('contact_message', 'editor'); // We hide content editor and show clean box
}
add_action('init', 'oyunhaber_clean_contact_ui');
