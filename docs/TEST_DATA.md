# Necat Derneği Web Sitesi - Test Verileri

## Test Resimlerinin Hazırlanması

### Gerekli Resimler:
1. **Logo**: 200x100px, PNG formatında
2. **Hero Banner**: 1920x600px, JPG formatında
3. **Proje Resimleri**: 800x600px, JPG formatında (6 adet)
4. **Haber Resimleri**: 800x600px, JPG formatında (5 adet)
5. **Takım Fotoğrafları**: 300x300px, JPG formatında (8 adet)
6. **Galeri Resimleri**: 600x400px, JPG formatında (12 adet)

### Test Resimlerini İndirme:

Aşağıdaki sitelerden ücretsiz resimler indirebilirsiniz:

#### Unsplash.com:
- Charity work: https://unsplash.com/s/photos/charity
- Volunteers: https://unsplash.com/s/photos/volunteers
- Children: https://unsplash.com/s/photos/children-help
- Food donation: https://unsplash.com/s/photos/food-donation

#### Pexels.com:
- Nonprofit: https://www.pexels.com/search/nonprofit/
- Community help: https://www.pexels.com/search/community-help/
- Education: https://www.pexels.com/search/education/

### Mevcut Klasör Yapısı (Güncel):
```
uploads/
├── .htaccess (Güvenlik dosyası)
├── images/
│   ├── about/
│   │   ├── about-main.jpg
│   │   └── about-team.jpg
│   ├── banner_sample.jpg
│   ├── cta/
│   │   └── cta-bg.jpg
│   ├── events/
│   │   └── education_event_1.jpg
│   ├── favicon.ico
│   ├── gallery/
│   │   └── charity_work_1.jpg
│   ├── hero/
│   │   ├── hero-bg.jpg
│   │   ├── hero-image.jpg
│   │   └── test.jpg
│   ├── logo_sample.png
│   ├── projects/
│   │   ├── project-1.jpg
│   │   ├── project-2.jpg
│   │   └── project-3.jpg
│   ├── stats/
│   │   └── stats-bg.jpg
│   ├── testimonials/
│   │   ├── avatar-1.jpg
│   │   ├── avatar-2.jpg
│   │   └── avatar-3.jpg
│   └── volunteers/
│       └── volunteer_activity_1.jpg
├── documents/
│   ├── dernegi_tuzugu.txt
│   └── faaliyet_raporu_2024.txt
└── receipts/
    ├── bagis_makbuzu_001.txt
    ├── bagis_makbuzu_002.txt
    └── receipt_2025-06-22_11-14-31_6857bb67e8d58.png
```

> **NOT**: Bu yapı aktif olarak kullanılmakta ve file manager ile yönetilmektedir. Yeni dosya eklerken mevcut klasör yapısına uygun şekilde ekleme yapınız.
│   ├── annual-report-2023.pdf
│   └── bylaws.pdf
└── receipts/
    ├── donation-receipt-template.pdf
    └── sample-receipt.pdf
```

### Resim Optimizasyonu:

#### Online Araçlar:
- **TinyPNG**: https://tinypng.com/
- **Squoosh**: https://squoosh.app/
- **Compressor.io**: https://compressor.io/

#### Önerilen Boyutlar:
- **Logo**: Maksimum 50KB
- **Hero Banner**: Maksimum 300KB
- **Proje/Haber Resimleri**: Maksimum 200KB
- **Takım Fotoğrafları**: Maksimum 100KB
- **Galeri Resimleri**: Maksimum 150KB

### Placeholder Resim Servisleri:

Test için placeholder resimler kullanabilirsiniz:

#### Lorem Picsum:
```html
<!-- Logo -->
<img src="https://picsum.photos/200/100" alt="Logo">

<!-- Hero Banner -->
<img src="https://picsum.photos/1920/600" alt="Hero">

<!-- Proje Resimleri -->
<img src="https://picsum.photos/800/600?random=1" alt="Proje 1">
<img src="https://picsum.photos/800/600?random=2" alt="Proje 2">

<!-- Takım Fotoğrafları -->
<img src="https://picsum.photos/300/300?random=10" alt="Takım Üyesi">
```

#### Placeholder.com:
```html
<img src="https://via.placeholder.com/800x600/2c5aa0/ffffff?text=Proje+Resmi" alt="Proje">
```

### Test Dökümanları:

#### PDF Dökümanları için:
1. **Lorem Ipsum Generator**: https://www.lipsum.com/
2. **PDF Creator**: Herhangi bir word işlemci program
3. **Sample PDFs**: https://www.adobe.com/support/products/ent/acrobat/aia.html

## Veritabanı Test Verileri

### SQL Dosyası Oluşturma:

```sql
-- Test verileri eklemek için sample_data.sql dosyası oluşturun

-- Projeler
INSERT INTO projects (title, description, goal_amount, current_amount, status, featured, category, start_date, end_date, created_at) VALUES
('Yetim Çocuklara Eğitim Desteği', 'Maddi durumu yetersiz yetim çocukların eğitim ihtiyaçlarını karşılamak için başlatılan proje.', 50000.00, 32500.00, 'active', 1, 'education', '2024-01-01', '2024-12-31', NOW()),
('Kimsesiz Yaşlılara Bakım', 'Yaşlı bakım evindeki kimsesiz yaşlıların ihtiyaçlarının karşılanması projesi.', 30000.00, 18750.00, 'active', 1, 'elderly_care', '2024-02-01', '2024-11-30', NOW()),
('Kış Aylarında Sıcak Yemek', 'Soğuk kış aylarında sokakta yaşayan insanlara sıcak yemek dağıtımı.', 25000.00, 22300.00, 'active', 0, 'food_aid', '2024-11-01', '2025-03-31', NOW());

-- Haberler
INSERT INTO news (title, content, excerpt, category, status, featured, views, author_id, created_at) VALUES
('Ramazan Ayında İftar Programı Düzenlendi', 'Necat Derneği olarak bu Ramazan ayında da geleneksel iftar programımızı düzenledik...', 'Ramazan ayında düzenlenen iftar programına 200 kişi katıldı.', 'events', 'published', 1, 150, 1, NOW()),
('Yeni Eğitim Merkezi Açıldı', 'Derneğimizin uzun zamandır üzerinde çalıştığı eğitim merkezi nihayet hizmete açıldı...', 'Yeni eğitim merkezi 100 öğrenciye hizmet verecek.', 'education', 'published', 1, 89, 1, NOW()),
('Kış Yardımları Başladı', 'Soğuk kış aylarının başlamasıyla birlikte kış yardım kampanyamızı başlattık...', 'Kış yardımları kapsamında 500 aileye ulaşılacak.', 'campaigns', 'published', 0, 67, 1, NOW());

-- Bağışlar (test)
INSERT INTO donations (donation_type, amount, donor_name, donor_email, donor_phone, message, reference_number, status, created_at) VALUES
('Genel Bağış', 500.00, 'Ahmet Yılmaz', 'ahmet@example.com', '0532 123 45 67', 'Hayırlı işler için kullanın', 'BGS202400001', 'confirmed', NOW()),
('Eğitim Desteği', 250.00, 'Fatma Kaya', 'fatma@example.com', '0543 987 65 43', 'Çocukların eğitimi için', 'BGS202400002', 'confirmed', NOW()),
('Yaşlı Bakımı', 750.00, 'Mehmet Demir', 'mehmet@example.com', '0555 111 22 33', '', 'BGS202400003', 'pending', NOW());

-- Gönüllü Başvuruları (test)
INSERT INTO volunteer_applications (full_name, email, phone, birth_date, address, position, experience, availability, skills, motivation, status, created_at) VALUES
('Ayşe Şahin', 'ayse@example.com', '0532 444 55 66', '1995-03-15', 'İstanbul', 'Eğitim Koordinatörü', 'Önceden eğitim alanında çalıştım', 'Hafta sonları', 'Organizasyon, İletişim', 'Çocuklara yardım etmek istiyorum', 'approved', NOW()),
('Ali Öz', 'ali@example.com', '0545 777 88 99', '1988-07-22', 'Ankara', 'Sosyal Medya Uzmanı', 'Dijital pazarlama deneyimim var', 'Akşam saatleri', 'Sosyal Medya, Grafik Tasarım', 'Sosyal sorumluluk projeleri yaparak topluma katkıda bulunmak istiyorum', 'pending', NOW());

-- İletişim Mesajları (test)
INSERT INTO contact_messages (name, email, phone, subject, message, status, created_at) VALUES
('Zeynep Arslan', 'zeynep@example.com', '0533 666 77 88', 'Bağış Hakkında', 'Düzenli bağış yapmak istiyorum, nasıl bir yol izlemeliyim?', 'pending', NOW()),
('Hasan Çelik', 'hasan@example.com', '0544 222 33 44', 'Gönüllü Olmak İstiyorum', 'Emekli olduktan sonra gönüllü olarak çalışmak istiyorum.', 'replied', NOW());
```

### Resim Yollarını Güncelleme:

Test resimlerini yükledikten sonra veritabanında image path'lerini güncelleyin:

```sql
-- Projeler için resim yolları
UPDATE projects SET image = 'uploads/images/projects/project-1.jpg' WHERE id = 1;
UPDATE projects SET image = 'uploads/images/projects/project-2.jpg' WHERE id = 2;
UPDATE projects SET image = 'uploads/images/projects/project-3.jpg' WHERE id = 3;

-- Haberler için resim yolları  
UPDATE news SET image = 'uploads/images/news/news-1.jpg' WHERE id = 1;
UPDATE news SET image = 'uploads/images/news/news-2.jpg' WHERE id = 2;
UPDATE news SET image = 'uploads/images/news/news-3.jpg' WHERE id = 3;
```

## Hızlı Test Kurulumu

### 1. Placeholder Resimlerle Test:
```bash
# Proje resimleri için placeholder URL'ler kullanın
# Admin panelinde resim yüklerken bu URL'leri kullanabilirsiniz
```

### 2. Lorem Ipsum İçerik:
Test içerikleri için Lorem Ipsum kullanın ve daha sonra gerçek içeriklerle değiştirin.

### 3. Test Email Adresleri:
Test için geçici email adresleri:
- admin@test.com
- test@example.com
- noreply@tempmail.com

Bu test verileri ile sitenin tüm fonksiyonlarını test edebilir ve gerçek verilerle değiştirebilirsiniz.
