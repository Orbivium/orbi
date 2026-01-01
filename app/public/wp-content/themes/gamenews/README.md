# ORBI - MODERATÖR VE YÖNETİCİ REHBERİ

Bu rehber, **Orbi** web sitesinin yönetimi, içerik girişi, moderasyon süreçleri ve teknik detayları hakkında moderatörler ve yöneticiler için hazırlanmış kapsamlı bir kılavuzdur.

---

## 1. YÖNETİM PANELİ SAYFALARI VE İŞLEVLERİ

Aşağıdaki sayfalar WordPress Admin Paneli (Sol Menü) üzerinde bulunur:

### [1] Aktivite Günlüğü (YENİ)
- **Konum:** `Admin Paneli > Aktivite Günlüğü`
- **İşlev:** Sitedeki son gelişmeleri anlık takip etmenizi sağlar.
- **İçerik Yönetimi Tablosu:** Son eklenen Haber, İnceleme ve Videoları listeler. Yazar, Tür ve Platform filtreleri ile rapor alabilirsiniz.
- **Yorum Yönetimi Tablosu:** Son gelen yorumları listeler. Onay bekleyenleri görebilir, kullanıcı bazlı filtreleme yapabilirsiniz (*"Raporu İndir"* ile Excel çıktısı alabilirsiniz).

### [2] İçerik Yönetimi
- **Konum:** `Admin Paneli > İçerik Yönetimi`
- **İşlev:** Özel içerik türleri dışındaki genel ayarları ve bazı tema özelleştirmelerini barındırabilir.

### [3] Haberler (News)
- **Konum:** `Admin Paneli > Haberler`
- **İşlev:** Sadece "Haber" niteliği taşıyan kısa ve güncel içerikler burada girilir.
- **Dikkat Edilecekler:** "Öne Çıkan Görsel" mutlaka eklenmelidir. Platform seçimi yapılmalıdır.

### [4] İncelemeler (Reviews)
- **Konum:** `Admin Paneli > İncelemeler`
- **İşlev:** Oyun incelemeleri, detaylı analizler burada girilir.
- **Özellik:** Puanlama sistemi, artı/eksi listesi ve teknik detaylar için özel alanlar (meta box) içerir.

### [5] Videolar
- **Konum:** `Admin Paneli > Videolar`
- **İşlev:** Video içerikleri içindir. Kendi sunucunuza yüklenen MP4 dosyaları veya YouTube embed linkleri kullanılabilir.

### [6] E-Spor
- **Konum:** `Admin Paneli > E-Spor`
- **İşlev:** Espor turnuvaları, takım haberleri ve maç sonuçları için kullanılır.

### [7] Slider Ayarları
- **Konum:** `Admin Paneli > Slider Ayarları`
- **İşlev:** Ana sayfadaki büyük manşet alanını yönetir. Buraya eklenen içerikler ana sayfada en üstte büyük olarak görünür.

### [8] Yorumlar
- **Konum:** `Admin Paneli > Yorumlar`
- **İşlev:** Tüm site yorumlarının merkezidir. Onaylama, silme, SPAM işaretleme işlemleri buradan yapılır.

---

## 2. İÇERİK OLUŞTURMA VE DİKKAT EDİLMESİ GEREKENLER

Bir içerik oluştururken aşağıdaki adımları ve kuralları takip ediniz:

### A. BAŞLIK VE METİN
- **Başlık:** İlgi çekici, kısa ve net olmalı (Max 60-70 karakter). Tümü büyük harf **KULLANILMAMALIDIR**.
- **Özet (Excerpt):** İçeriğin kısa bir özeti girilmelidir. Bu alan kartlarda görünür.
- **İçerik:** Paragraflara bölünmüş, okunabilir metinler tercih edin. `H2` ve `H3` başlıkları kullanın.

### B. PLATFORM VE KATEGORİ SEÇİMİ
- **Platform:** İçeriğin hangi platformla ilgili olduğu (PC, PlayStation, Xbox vb.) **MUTLAKA** sağ menüden seçilmelidir. Bu, içeriğin doğru sayfalarda (örn: PlayStation sayfası) görünmesini sağlar.

### C. GÖRSELLER (ÖNEMLİ)
- Tüm görseller yüksek kaliteli (HD) olmalı ancak dosya boyutu optimize edilmelidir (WebP veya sıkıştırılmış JPG).
- **"Öne Çıkan Görsel" (Featured Image)** her yazıda mutlaka olmalıdır.

---

## 3. GÖRSEL BOYUTLARI TABLOSU

Sitedeki tüm alanlar için önerilen görsel boyutları aşağıdaki gibidir. Bu boyutlara uymak tasarımın bozulmamasını ve sitenin hızlı açılmasını sağlar.

| KULLANIM ALANI | TAVSİYE EDİLEN BOYUT (PX) | ORAN | AÇIKLAMA |
| :--- | :--- | :--- | :--- |
| **Slider / Manşet Görseli** | 1920 x 1080 (veya 1600x900) | 16:9 | Ana sayfa en üstteki büyük kayan görseller. Yüksek kalite şart. |
| **İçerik Kartı (Kapak)** | 800 x 450 | 16:9 | Ana sayfa listeleri, arşiv sayfaları ve kategori listelerindeki kutular. |
| **İçerik Detay (Hero)** | 1200 x 675 | 16:9 | Yazının içine girildiğinde en üstte çıkan büyük kapak görseli. |
| **Profil Avatarı** | 500 x 500 | 1:1 | Kare olmalıdır. Sistem otomatik yuvarlar. |
| **Platform Logosu (SVG)** | Vektörel (SVG) | - | Platform menü ikonları için. `/assets/images/platforms/` klasörüne atılır. |
| **Hakkımızda Yan Görsel** | 600 x 800 (Dikey) | 3:4 | Hakkımızda sayfasındaki sağ tarafta duran dikey görsel için idealdir. |
| **Video Kapak (Thumbnail)** | 1280 x 720 | 16:9 | Video içerikleri için yüklenen kapak görseli. |

---

## 4. MODERASYON KURALLARI

### [YORUMLAR]
- **Hakaret/Küfür:** Küfür, hakaret, nefret söylemi içeren yorumlar direkt **silinmelidir (Çöp)**.
- **Spoiler:** Spoiler (sürprizbozan) içeren yorumlar ya düzenlenerek spoiler uyarısı konulmalı ya da onaylanmamalıdır.
- **Spam:** Reklam ve SPAM link içeren yorumlar **"Spam"** olarak işaretlenmelidir (IP engellemesi için).
- **Eleştiri:** Yapıcı eleştiriler (siteye olsa bile) onaylanmalıdır.
- **Kontrol:** "Aktivite Günlüğü" sayfasından toplu kontrol yapabilirsiniz.

### [KULLANICILAR]
- **Kullanıcı Adı:** Uygunsuz (küfürlü, reklam amaçlı) kullanıcı adı alan üyeler engellenmeli veya silinmelidir.
- **Profil Resmi:** Uygunsuz görsel kullananlar uyarılmalı veya görselleri kaldırılmalıdır.

---

## 5. TEKNİK NOTLAR (YAZILIM EKİBİ İÇİN)

- **Tema Klasörü:** `/wp-content/themes/gamenews/`
- **Aktivite Log Dosyası:** `/inc/activity-log.php`
- **CSS Stilleri:** `style.css` (Ana stil dosyası)
- **Platform İkonları:** `/assets/images/platforms/` (İsimlendirme: `playstation.svg`, `xbox.svg` şeklinde olmalı)

---

**SON GÜNCELLEME:** 01.01.2026  
**Orbi Yönetim Ekibi**
