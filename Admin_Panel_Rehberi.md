# 👑 ORBI ADMİN PANEL REHBERİ (V2.0 - DETAYLI)

Bu rehber, site yöneticilerinin ve editörlerin içerik girerken, siteyi düzenlerken uyması gereken teknik ve estetik standartları açıklar.

## 1. MÜKEMMEL İÇERİK GİRİŞ REHBERİ

Yeni bir haber veya inceleme eklerken şu adımları takip edin:

### 📸 Görsel Hazırlığı (En Önemli Kısım)
- **Kapak Görseli:** 1920x1080 px (veya 16:9 oranında en az 1200x675 px).
- **Format:** WebP önerilir (Boyut tasarrufu için). Kaydederken "Kalite: 75" idealdir.
- **Dosya İsmi:** `starfield-inceleme.jpg` gibi temiz ve küçük harfli isimler kullanın.

### ✍️ Yazı Ayarları
1.  **Başlık:** Maksimum 65 karakter (Google arama sonuçları için).
2.  **Özet (Spot):** Yazının başında kalın (Bold) harflerle kısa bir giriş yapın.
3.  **Kategori:** Haberin türünü doğru seçin (Dosya Konusu, İnceleme, Güncel Haber).
4.  **Platform (KRİTİK):** Metin editörünün sağ tarafındaki "Platform" kutusundan doğru platformu seçin. **Eğer seçmezseniz sitenin rengi standart kırmızı kalır.**

---

## 2. ANA SAYFA VE SLIDER YÖNETİMİ

Ana sayfa slider'ı sitenin ilk izlenimidir.

- **Sayı:** Slider'da en fazla 5-6 içerik tutun. Fazlası yükleme hızını düşürür.
- **Seçim:** "Slider'da Göster" seçeneği (eğer temaya entegre ise) veya ilgili kategoriyi kullanarak slider içeriğini belirleyin.
- **Slider Görseli:** Yazı içindeki görsellerden farklı olarak, slider görseli temiz ve yazısız olmalıdır (Sistem başlığı otomatik üzerine basar).

---

## 3. ÜYE VE ROL YÖNETİMİ

Kullanıcılar sekmesinden yeni üyeler oluşturabilir veya yetki verebilirsiniz.

- **Roller:**
    - **Administrator:** Sınırsız yetki. Sadece ana sahiplerde olmalı.
    - **Editor:** Yazı yazabilir, silebilir, görselleri yönetebilir.
    - **Moderator:** Sadece yorumları yönetir, yazı silemez.
    - **Subscriber:** Standart üye. Sadece profilini düzenleyebilir.

---

## 4. TEMA VE BİLEŞEN (WIDGET) AYARLARI

###  Footer (Site Altı) Düzenleme
**Görünüm > Bileşenler** yolunu izleyin:
- **Footer 1 (Hakkımızda):** Kısa bir tanıtım ve vizyon cümlesi.
- **Footer 2 (Site Haritası):** Menü üzerinden önemli linkler.
- **Footer 3 (Sosyal Medya):** Instagram, YouTube, X linklerinizi güncelleyin.

### 🎨 Renk Değişimi
Platform taksonomisi üzerinden renkleri değiştirebilirsiniz:
**Yazılar > Platformlar** sekmesine gidin, platformu düzenleyin ve varsa renk alanını güncelleyin (Teknik olarak `functions.php` içindeki array daha önceliklidir).

---

## 5. KRİTİK BAKIM NOTLARI
1.  **Gereksiz Eklenti:** Performans için gereksiz eklentileri aktif etmeyin.
2.  **Önbellek (Cache):** Sitede yaptığınız değişiklikler görünmüyorsa, varsa önbellek eklentisinden "Clear Cache" yapın.
3.  **Resim Kütüphanesi:** Artık kullanılmayan çok eski görselleri silerek sunucu alanından tasarruf edin.

---
**Destek:** Büyük bir teknik arıza durumunda `Teknik Defter` dosyasındaki dosya hiyerarşisini kontrol ederek sorunun kaynağını bulabilirsiniz.
