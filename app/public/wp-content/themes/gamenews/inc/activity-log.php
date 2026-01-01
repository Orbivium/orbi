<?php
/**
 * Activity Log Admin Page
 *
 * Displays two SEPARATE sections for Content and Comments, each with independent filtering and CSV export.
 *
 * @package OyunHaber
 */

// 1. Register the Menu Page
function oyunhaber_activity_log_menu() {
    add_menu_page(
        'Aktivite Günlüğü',    // Page Title
        'Aktivite Günlüğü',    // Menu Title
        'manage_options',      // Capability
        'oyunhaber-activity',  // Slug
        'oyunhaber_render_activity_page', // Callback
        'dashicons-visibility', // Icon
        2                       // Position
    );
}
add_action('admin_menu', 'oyunhaber_activity_log_menu');

// 2. Handle CSV Downloads
function oyunhaber_handle_csv_export() {
    if ( !isset($_GET['page']) || $_GET['page'] != 'oyunhaber-activity' || !isset($_GET['action']) ) {
        return;
    }
    
    if ( ! current_user_can( 'manage_options' ) ) return;

    // --- EXPORT CONTENT (ICERIK) ---
    if ( $_GET['action'] == 'download_content_csv' ) {
        
        $ct_user     = isset($_GET['ct_user']) ? intval($_GET['ct_user']) : 0;
        $ct_id       = isset($_GET['ct_id']) ? intval($_GET['ct_id']) : 0;
        $ct_type     = isset($_GET['ct_type']) ? sanitize_text_field($_GET['ct_type']) : '';
        $ct_platform = isset($_GET['ct_platform']) ? sanitize_text_field($_GET['ct_platform']) : '';

        $filename = 'orbi-icerik-raporu-' . date('Y-m-d') . '.csv';
        
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        $output = fopen( 'php://output', 'w' );
        fputs( $output, "\xEF\xBB\xBF" ); // BOM

        fputcsv( $output, array( 'ID', 'Baslik', 'Tur', 'Kategori', 'Yazar', 'Durum', 'Tarih' ) );

        $args = array(
            'post_type'      => array('news', 'reviews', 'videos', 'esports'),
            'post_status'    => 'any',
            'posts_per_page' => 200, // Export limit
            'orderby'        => 'date',
            'order'          => 'DESC'
        );

        if ($ct_user > 0) $args['author'] = $ct_user;
        if ($ct_id > 0) $args['p'] = $ct_id;
        if (!empty($ct_type)) $args['post_type'] = $ct_type;
        if (!empty($ct_platform)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'platform',
                    'field'    => 'slug',
                    'terms'    => $ct_platform,
                ),
            );
        }

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $pt = get_post_type();
                $pt_label = ucfirst($pt);
                if($pt == 'news') $pt_label = 'Haber';
                if($pt == 'reviews') $pt_label = 'İnceleme';
                if($pt == 'videos') $pt_label = 'Video';

                $cat_list = array();
                $platforms = get_the_terms(get_the_ID(), 'platform');
                if($platforms && !is_wp_error($platforms)) {
                    foreach($platforms as $p) $cat_list[] = $p->name;
                }
                $cat_str = implode(', ', $cat_list);

                fputcsv( $output, array(
                    get_the_ID(),
                    get_the_title(),
                    $pt_label,
                    $cat_str,
                    get_the_author(),
                    get_post_status(),
                    get_the_date('Y-m-d H:i')
                ));
            }
        }
        fclose($output);
        exit;
    }

    // --- EXPORT COMMENTS (YORUM) ---
    if ( $_GET['action'] == 'download_comments_csv' ) {
        
        $cm_user     = isset($_GET['cm_user']) ? intval($_GET['cm_user']) : 0;
        $cm_post_id  = isset($_GET['cm_post_id']) ? intval($_GET['cm_post_id']) : 0;
        $cm_type     = isset($_GET['cm_type']) ? sanitize_text_field($_GET['cm_type']) : '';
        $cm_platform = isset($_GET['cm_platform']) ? sanitize_text_field($_GET['cm_platform']) : '';

        $filename = 'orbi-yorum-raporu-' . date('Y-m-d') . '.csv';
        
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        $output = fopen( 'php://output', 'w' );
        fputs( $output, "\xEF\xBB\xBF" ); // BOM

        fputcsv( $output, array( 'Yazar', 'Email', 'Yorum', 'Icerik Basligi', 'Durum', 'Tarih', 'IP' ) );

        $comment_args = array(
            'number' => 200,
            'status' => 'all'
        );
        
        if ($cm_user > 0) $comment_args['user_id'] = $cm_user;
        if ($cm_post_id > 0) $comment_args['post_id'] = $cm_post_id;
        if (!empty($cm_type)) $comment_args['post_type'] = $cm_type;

        $comments = get_comments($comment_args);
        $count = 0;

        if ($comments) {
            foreach ($comments as $comment) {
                if ($count >= 200) break;

                // Platform Filter Manual
                if (!empty($cm_platform)) {
                    if (!has_term($cm_platform, 'platform', $comment->comment_post_ID)) {
                        continue;
                    }
                }

                $status = ($comment->comment_approved == '1') ? 'Onayli' : (($comment->comment_approved == '0') ? 'Bekliyor' : 'Spam');
                
                fputcsv( $output, array(
                    $comment->comment_author,
                    $comment->comment_author_email,
                    wp_trim_words($comment->comment_content, 20),
                    get_the_title($comment->comment_post_ID),
                    $status,
                    $comment->comment_date,
                    $comment->comment_author_IP
                ));
                $count++;
            }
        }
        fclose($output);
        exit;
    }
}
add_action( 'admin_init', 'oyunhaber_handle_csv_export' );

// 3. Render Page Content
function oyunhaber_render_activity_page() {
    // --- Parse Content Filters ---
    $ct_user     = isset($_GET['ct_user']) ? intval($_GET['ct_user']) : 0;
    $ct_id       = isset($_GET['ct_id']) ? intval($_GET['ct_id']) : 0;
    $ct_type     = isset($_GET['ct_type']) ? sanitize_text_field($_GET['ct_type']) : '';
    $ct_platform = isset($_GET['ct_platform']) ? sanitize_text_field($_GET['ct_platform']) : '';

    // --- Parse Comment Filters ---
    $cm_user     = isset($_GET['cm_user']) ? intval($_GET['cm_user']) : 0;
    $cm_post_id  = isset($_GET['cm_post_id']) ? intval($_GET['cm_post_id']) : 0;
    $cm_type     = isset($_GET['cm_type']) ? sanitize_text_field($_GET['cm_type']) : '';
    $cm_platform = isset($_GET['cm_platform']) ? sanitize_text_field($_GET['cm_platform']) : '';

    // Generate Download URLs (preserving current filters for that section)
    $dl_content_url = admin_url('admin.php?page=oyunhaber-activity&action=download_content_csv');
    if($ct_user) $dl_content_url = add_query_arg('ct_user', $ct_user, $dl_content_url);
    if($ct_id) $dl_content_url = add_query_arg('ct_id', $ct_id, $dl_content_url);
    if($ct_type) $dl_content_url = add_query_arg('ct_type', $ct_type, $dl_content_url);
    if($ct_platform) $dl_content_url = add_query_arg('ct_platform', $ct_platform, $dl_content_url);

    $dl_comments_url = admin_url('admin.php?page=oyunhaber-activity&action=download_comments_csv');
    if($cm_user) $dl_comments_url = add_query_arg('cm_user', $cm_user, $dl_comments_url);
    if($cm_post_id) $dl_comments_url = add_query_arg('cm_post_id', $cm_post_id, $dl_comments_url);
    if($cm_type) $dl_comments_url = add_query_arg('cm_type', $cm_type, $dl_comments_url);
    if($cm_platform) $dl_comments_url = add_query_arg('cm_platform', $cm_platform, $dl_comments_url);

    $platforms = get_terms(array('taxonomy' => 'platform', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Site Aktivite Günlüğü</h1>
        <hr class="wp-header-end">

        <!-- ======================= -->
        <!-- SECTION: CONTENT (POSTS) -->
        <!-- ======================= -->
        <div class="postbox" style="margin-top: 20px;">
            <div class="postbox-header" style="justify-content:space-between; padding:10px;">
                <h2 style="margin:0;">İçerik Yönetimi (Haber, İnceleme, Video)</h2>
                <a href="<?php echo esc_url($dl_content_url); ?>" class="button button-secondary">📥 İçerik Raporu İndir (CSV)</a>
            </div>
            
            <div class="inside">
                <!-- Content Filter Form -->
                <form method="get" action="" style="background: #fdfdfd; padding: 10px; border: 1px solid #eee; margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
                    <input type="hidden" name="page" value="oyunhaber-activity">
                    <!-- Keep Comment Filters Hidden so they don't reset when submitting this form -->
                    <input type="hidden" name="cm_user" value="<?php echo $cm_user; ?>">
                    <input type="hidden" name="cm_post_id" value="<?php echo $cm_post_id; ?>">
                    <input type="hidden" name="cm_type" value="<?php echo esc_attr($cm_type); ?>">
                    <input type="hidden" name="cm_platform" value="<?php echo esc_attr($cm_platform); ?>">

                    <div>
                        <label>Yazar:</label><br>
                        <?php wp_dropdown_users(array('show_option_all'=>'Tümü', 'name'=>'ct_user', 'selected'=>$ct_user)); ?>
                    </div>
                    <div>
                        <label>Tür:</label><br>
                        <select name="ct_type">
                            <option value="">Tümü</option>
                            <option value="news" <?php selected($ct_type, 'news'); ?>>Haber</option>
                            <option value="reviews" <?php selected($ct_type, 'reviews'); ?>>İnceleme</option>
                            <option value="videos" <?php selected($ct_type, 'videos'); ?>>Video</option>
                            <option value="esports" <?php selected($ct_type, 'esports'); ?>>Espor</option>
                        </select>
                    </div>
                    <div>
                        <label>Platform:</label><br>
                        <select name="ct_platform">
                            <option value="">Tümü</option>
                            <?php if(!is_wp_error($platforms)) { foreach($platforms as $p) echo "<option value='{$p->slug}' ".selected($ct_platform,$p->slug,false).">{$p->name}</option>"; } ?>
                        </select>
                    </div>
                    <div>
                        <label>ID:</label><br>
                        <input type="number" name="ct_id" value="<?php echo $ct_id ? $ct_id:''; ?>" style="width:70px;">
                    </div>
                    <div>
                        <input type="submit" class="button button-primary" value="Filtrele">
                    </div>
                </form>

                <!-- Content Table -->
                <table class="widefat striped fixed">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Başlık</th>
                            <th>Tür</th>
                            <th>Platform</th>
                            <th>Yazar</th>
                            <th>Durum</th>
                            <th width="120">Tarih</th>
                            <th width="60">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $args = array(
                            'post_type' => array('news','reviews','videos','esports'),
                            'post_status' => 'any',
                            'posts_per_page' => 20,
                            'orderby' => 'date', 
                            'order' => 'DESC'
                        );
                        if ($ct_user > 0) $args['author'] = $ct_user;
                        if ($ct_id > 0) $args['p'] = $ct_id;
                        if (!empty($ct_type)) $args['post_type'] = $ct_type;
                        if (!empty($ct_platform)) {
                            $args['tax_query'] = array(array('taxonomy'=>'platform','field'=>'slug','terms'=>$ct_platform));
                        }

                        $query = new WP_Query($args);
                        if ($query->have_posts()) :
                            while ($query->have_posts()) : $query->the_post();
                                $pt_label = ucfirst(get_post_type());
                                $stat_color = (get_post_status()=='publish') ? '#2ecc71' : '#777';
                        ?>
                        <tr>
                            <td>#<?php the_ID(); ?></td>
                            <td><strong><a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a></strong></td>
                            <td><?php echo $pt_label; ?></td>
                            <td><?php $terms = get_the_terms(get_the_ID(),'platform'); if($terms && !is_wp_error($terms)) { foreach($terms as $t) echo $t->name.' '; } ?></td>
                            <td><?php the_author(); ?></td>
                            <td><span style="background:<?php echo $stat_color; ?>; color:#fff; padding:2px 5px; border-radius:3px; font-size:10px;"><?php echo get_post_status(); ?></span></td>
                            <td><?php echo get_the_date('Y-m-d H:i'); ?></td>
                            <td><a href="<?php echo get_edit_post_link(); ?>">📝</a></td>
                        </tr>
                        <?php endwhile; wp_reset_postdata(); else: echo '<tr><td colspan="8">Kayıt yok.</td></tr>'; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ======================= -->
        <!-- SECTION: COMMENTS -->
        <!-- ======================= -->
        <div class="postbox" style="margin-top: 30px;">
            <div class="postbox-header" style="justify-content:space-between; padding:10px;">
                <h2 style="margin:0;">Yorum Yönetimi</h2>
                <a href="<?php echo esc_url($dl_comments_url); ?>" class="button button-secondary">📥 Yorum Raporu İndir (CSV)</a>
            </div>

            <div class="inside">
                <!-- Comment Filter Form -->
                <form method="get" action="" style="background: #fdfdfd; padding: 10px; border: 1px solid #eee; margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
                    <input type="hidden" name="page" value="oyunhaber-activity">
                    <!-- Keep Content Filters Hidden -->
                    <input type="hidden" name="ct_user" value="<?php echo $ct_user; ?>">
                    <input type="hidden" name="ct_id" value="<?php echo $ct_id; ?>">
                    <input type="hidden" name="ct_type" value="<?php echo esc_attr($ct_type); ?>">
                    <input type="hidden" name="ct_platform" value="<?php echo esc_attr($ct_platform); ?>">

                    <div>
                        <label>Yorumcu:</label><br>
                        <?php wp_dropdown_users(array('show_option_all'=>'Tümü', 'name'=>'cm_user', 'selected'=>$cm_user)); ?>
                    </div>
                    <div>
                        <label>İçerik Türü:</label><br>
                        <select name="cm_type">
                            <option value="">Tümü</option>
                            <option value="news" <?php selected($cm_type, 'news'); ?>>Haber</option>
                            <option value="reviews" <?php selected($cm_type, 'reviews'); ?>>İnceleme</option>
                            <option value="videos" <?php selected($cm_type, 'videos'); ?>>Video</option>
                        </select>
                    </div>
                    <div>
                        <label>Platform:</label><br>
                        <select name="cm_platform">
                            <option value="">Tümü</option>
                            <?php if(!is_wp_error($platforms)) { foreach($platforms as $p) echo "<option value='{$p->slug}' ".selected($cm_platform,$p->slug,false).">{$p->name}</option>"; } ?>
                        </select>
                    </div>
                    <div>
                        <label>İçerik ID:</label><br>
                        <input type="number" name="cm_post_id" value="<?php echo $cm_post_id ? $cm_post_id:''; ?>" style="width:70px;">
                    </div>
                    <div>
                        <input type="submit" class="button button-primary" value="Filtrele">
                    </div>
                </form>

                <!-- Comments Table -->
                <table class="widefat striped fixed">
                    <thead>
                        <tr>
                            <th width="150">Yazar</th>
                            <th>Yorum</th>
                            <th>İlgili İçerik</th>
                            <th width="120">Tarih</th>
                            <th width="100">IP</th>
                            <th width="60">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cm_args = array('number'=>50, 'status'=>'all');
                        if($cm_user) $cm_args['user_id'] = $cm_user;
                        if($cm_post_id) $cm_args['post_id'] = $cm_post_id;
                        if(!empty($cm_type)) $cm_args['post_type'] = $cm_type;

                        $comments = get_comments($cm_args);
                        $view_count = 0;

                        if ($comments) {
                            foreach ($comments as $comment) {
                                if ($view_count >= 20) break;
                                // Manual Platform Check
                                if (!empty($cm_platform)) {
                                    if (!has_term($cm_platform, 'platform', $comment->comment_post_ID)) continue;
                                }

                                $status_badge = '';
                                if($comment->comment_approved=='0') $status_badge = '<span style="color:orange; font-weight:bold;">[?]</span> ';
                                if($comment->comment_approved=='spam') $status_badge = '<span style="color:red; font-weight:bold;">[SPAM]</span> ';
                        ?>
                        <tr>
                            <td>
                                <?php echo get_avatar($comment, 24); ?>
                                <strong><?php echo esc_html($comment->comment_author); ?></strong>
                            </td>
                            <td><?php echo $status_badge; echo wp_trim_words($comment->comment_content, 15); ?></td>
                            <td><a href="<?php echo get_permalink($comment->comment_post_ID); ?>"><?php echo get_the_title($comment->comment_post_ID); ?></a></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($comment->comment_date)); ?></td>
                            <td><?php echo $comment->comment_author_IP; ?></td>
                            <td><a href="<?php echo admin_url('comment.php?action=editcomment&c='.$comment->comment_ID); ?>">⚙️</a></td>
                        </tr>
                        <?php $view_count++; } } 
                        if ($view_count==0) echo '<tr><td colspan="6">Kayıt yok.</td></tr>';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <style>
        .postbox { border: 1px solid #ccd0d4; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
        .postbox-header { background: #fcfcfc; border-bottom: 1px solid #ccd0d4; display: flex; align-items: center; }
        .postbox-header h2 { font-size: 14px; font-weight: 600; }
        .avatar { vertical-align: middle; margin-right: 5px; border-radius: 50%; }
        /* Fix dropdowns in forms */
        select, input[type=number] { height: 28px; line-height: 28px; font-size: 12px; }
    </style>
    <?php
}
