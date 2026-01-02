# ORBI Teknik Defter (Technical Documentation)

## 1. Proje Genel Yapısı
**Tema:** GameNews (Custom WordPress Theme)
**Dizin:** `/wp-content/themes/gamenews/`
**Temel Teknolojiler:** WordPress Core, PHP 8.x, Vanilla CSS (CSS3 Variables), jQuery (Minimal).

## 2. Dosya Hiyerarşisi
- **style.css**: Ana stil dosyası. CSS değişkenleri (`:root`) ile renk yönetimi ve `media queries` ile responsive yapı burada bulunur.
- **functions.php**: Temanın beyni. Özellik tanımları, güvenlik önlemleri, stil/script yüklemeleri ve özel fonksiyonlar.
- **header.php**: Site üst kısmı. Navigasyon, Logo, Meta etiketleri ve Dinamik Platform Renkleri (`oyunhaber_dynamic_platform_colors`).
- **footer.php**: Site alt kısmı. Widget alanları ve kapanış scriptleri.
- **inc/**: Temanın modüler parçaları.
    - `activity-log.php`: Admin paneli loglama sistemi.
    - `custom-post-types.php`: Özel yazı türleri (Video vb.) tanımları.
    - `moderator-role.php`: Moderatör yetki tanımları.
    - `ads-manager.php`: Reklam yönetimi.

## 3. Özelleştirilmiş Fonksiyonlar

### A. Dinamik Platform Renkleri
Sistem, kullanıcının bulunduğu kategoriye (Platform) göre sitenin ana rengini otomatik değiştirir.
- **Dosya:** `functions.php` -> `oyunhaber_dynamic_platform_colors()`
- **Mekanizma:** `is_tax('platform')` kontrolü yapılır. İlgili `slug` (pc, xbox vb.) alınır ve önceden tanımlı HEX kodları CSS değişkeni (`--accent-color`) olarak sayfaya basılır.

### B. Güvenlik Kısıtlamaları
- **Admin Bar:** `show_admin_bar(false)` ile Yönetici ve Editör harici herkese gizlenmiştir.
- **Panel Erişimi:** `/wp-admin/` yoluna girmeye çalışan yetkisiz kullanıcılar (`!current_user_can('manage_options')`) `home_url()` adresine yönlendirilir.

### C. Menü Yapısı
- **Masaüstü:** Hover ile açılan "Dropdown" menüler (`.sub-menu`). `header.php` içinde PHP döngüsü ile oluşturulur.
- **Mobil:** "App-like" yatay kaydırmalı menü ve alt barda (overlay) açılan arama/menü butonları.

## 4. CSS Standartları ve Tasarım Dili
- **Renk Paleti:**
    - Zemin: `#121212` (Birincil), `#1e1e1e` (İkincil)
    - Yazı: `#e0e0e0` (Birincil), `#b0b0b0` (İkincil)
- **Komponentler:**
    - `Glassmorphism`: `backdrop-filter: blur(10px)` kullanımı.
    - `Card`: Yuvarlatılmış köşeler (`border-radius: 12px`) ve derin gölgeler (`box-shadow`).
    - `Butonlar`: `border-radius: 30px` ile hap şeklinde modern butonlar.

## 5. Veritabanı ve Taksonomi
- **Taxonomy:** `platform` (PC, Xbox, PlayStation, Nintendo, Mobil, Genel).
- **Post Types:** `post` (Standart Haber/İnceleme), `video` (İleride eklenecek video içerikleri).

## 6. Bakım ve Güncelleme Notları
- **Yeni Platform Ekleme:** Önce WP panelden taksonomiyi ekleyin, sonra `functions.php` içindeki renk ve ikon arraylerine (`oyunhaber_get_platform_color`) yeni slug'ı tanımlayın.
- **Logo Değişimi:** `/assets/images/platforms/` altına platform slug'ı ile aynı isimde `.svg` veya `.png` dosyası atılmalıdır.

---
Bu defter, yazılım ekibinin projeye hızlı adapte olmasını sağlamak için oluşturulmuştur.
