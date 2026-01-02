<?php
/**
 * Template Name: Şifremi Unuttum
 *
 * @package OyunHaber
 */

if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/profil' ) ); 
    exit;
}

// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['oyunhaber_forgot_nonce']) && wp_verify_nonce($_POST['oyunhaber_forgot_nonce'], 'oyunhaber_forgot_pass_action') ) {
    
    $login = isset($_POST['user_login']) ? trim($_POST['user_login']) : '';
    
    if ( ! empty( $login ) ) {
        // Standard WordPress Retrieve Password Function
        $errors = retrieve_password( $login );

        if ( is_wp_error( $errors ) ) {
            // Error occurred - stay on page to show error
            wp_safe_redirect( add_query_arg('reset_status', 'error', get_permalink()) );
            exit;
        } else {
            // Success - Redirect to Login Page
            wp_safe_redirect( add_query_arg('auth_msg', 'reset_sent', home_url('/giris-yap/')) );
            exit;
        }
    }
}

get_header(); 
?>

<div class="auth-page-wrapper">
    <div class="auth-card-centered">
        
        <div class="auth-header">
            <h2>Şifremi Unuttum</h2>
            <p>Hesabınıza erişimi kaybettiyseniz endişelenmeyin. Aşağıdaki kutucuğa kayıtlı e-posta adresinizi veya kullanıcı adınızı yazın.</p>
        </div>

        <?php
        // Success/Error Message Display Logic
        $is_sent = isset($_GET['reset_status']) && $_GET['reset_status'] == 'sent';
        $is_error = isset($_GET['reset_status']) && $_GET['reset_status'] == 'error';

        if ( $is_sent ) : 
        ?>
            <!-- SUCCESS STATE: Form Hidden -->
            <div class="auth-result-message" style="text-align: center;">
                <div class="success-icon-large" style="font-size: 64px; color: #10b981; margin-bottom: 20px;">
                    <span class="dashicons dashicons-email-alt" style="font-size: 64px; width: 64px; height: 64px;"></span>
                </div>
                <h3 style="color: #fff; margin-bottom: 15px; font-size: 1.5rem;">Bağlantı Gönderildi!</h3>
                <p style="color: rgba(232, 238, 246, 0.8); line-height: 1.6; margin-bottom: 30px;">
                    E-posta adresinize şifre sıfırlama talimatlarını içeren bir bağlantı gönderdik. Lütfen gelen kutunuzu (ve gerekiyorsa spam klasörünü) kontrol edin.
                </p>
                <div class="auth-links">
                    <a href="<?php echo home_url('/giris-yap/'); ?>" class="btn-auth-submit" style="display: inline-block; text-decoration: none; width: auto; padding: 12px 30px;">Giriş Yap'a Dön</a>
                </div>
            </div>

        <?php else : ?>
            
            <!-- FORM STATE -->
            <?php if ( $is_error ) : ?>
                <div class="msg-box error">Bu bilgilere sahip bir kullanıcı bulunamadı veya bir hata oluştu. Lütfen tekrar deneyin.</div>
            <?php endif; ?>

            <form method="post" class="auth-form" onsubmit="
                var btn = this.querySelector('button[type=submit]');
                btn.disabled = true;
                btn.classList.add('loading');
                btn.innerHTML = 'Gönderiliyor...';
            ">
                <?php wp_nonce_field('oyunhaber_forgot_pass_action', 'oyunhaber_forgot_nonce'); ?>
                
                <div class="form-group">
                    <label for="user_login">Kullanıcı Adı veya E-Posta</label>
                    <input type="text" name="user_login" id="user_login" required placeholder="ornek@email.com">
                </div>

                <button type="submit" class="btn-auth-submit">
                    <span class="btn-text">Şifre Sıfırla</span>
                </button>

                <div class="auth-links">
                    <a href="<?php echo home_url('/giris-yap/'); ?>" class="back-link"><span class="dashicons dashicons-arrow-left-alt2"></span> Giriş Yap'a Dön</a>
                </div>
            </form>

        <?php endif; ?>

    </div>
</div>

<style>
    :root {
        --auth-bg: #0B0F14;
        --auth-accent: #E11D48;
        --auth-text: #E8EEF6;
        --auth-text-muted: rgba(232, 238, 246, 0.6);
        --auth-border: rgba(255,255,255,0.1);
    }

    .auth-page-wrapper {
        background-color: var(--auth-bg);
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .auth-card-centered {
        max-width: 500px;
        width: 100%;
        background: radial-gradient(circle at top, rgba(30,30,30,0.4), var(--auth-bg));
        border: 1px solid var(--auth-border);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        position: relative;
        overflow: hidden;
    }

    /* Ambient Glow */
    .auth-card-centered::before {
        content: '';
        position: absolute;
        top: -100px; left: 50%; width: 300px; height: 300px;
        background: var(--auth-accent);
        transform: translateX(-50%);
        filter: blur(120px);
        opacity: 0.15;
        z-index: 0;
    }

    .auth-header, .auth-form, .msg-box { position: relative; z-index: 1; }

    .auth-header { text-align: center; margin-bottom: 30px; }
    .auth-header h2 { font-size: 2rem; margin-bottom: 10px; color: #fff; font-weight: 800; }
    .auth-header p { font-size: 0.95rem; color: var(--auth-text-muted); line-height: 1.5; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--auth-text-muted); font-size: 0.9rem; }
    .form-group input { 
        width: 100%; box-sizing: border-box; 
        background: rgba(255,255,255,0.05); 
        border: 1px solid var(--auth-border); 
        border-radius: 10px; padding: 12px; 
        color: #fff; font-size: 1rem;
        transition: 0.2s;
    }
    .form-group input:focus { border-color: var(--auth-accent); outline: none; background: rgba(0,0,0,0.3); }

    .btn-auth-submit {
        width: 100%; padding: 12px; font-size: 1rem; font-weight: 700;
        border-radius: 10px; border: none; cursor: pointer;
        background: linear-gradient(135deg, var(--auth-accent), #ef4444);
        color: #fff; margin-bottom: 20px;
        transition: transform 0.2s, opacity 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-auth-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(225,29,72,0.3); }
    
    .btn-auth-submit:disabled, .btn-auth-submit.loading {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .spinner-border {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s linear infinite;
    }
    
    .btn-auth-submit.loading .spinner-border {
        display: inline-block;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .auth-links { text-align: center; }
    .back-link { color: var(--auth-text-muted); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
    .back-link:hover { color: #fff; }

    .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center; font-size: 0.95rem; }
    .error { background: rgba(220, 38, 38, 0.15); color: #fca5a5; border: 1px solid rgba(220, 38, 38, 0.2); }
    .success { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.2); }
</style>

<?php get_footer(); ?>
