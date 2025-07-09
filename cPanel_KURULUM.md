# Necat Derneği Web Sitesi - cPanel Kurulum Kılavuzu (Güncel)

Bu belge, Necat Derneği web sitesinin cPanel ortamına kurulumu ve sorun giderme işlemleri için gerekli adımları içerir.

## İçindekiler

1. [Ön Gereksinimler](#ön-gereksinimler)
2. [Dosya Yükleme](#dosya-yükleme)
3. [Veritabanı Kurulumu](#veritabanı-kurulumu)
4. [Yapılandırma Ayarları](#yapılandırma-ayarları)
5. [İzin Ayarları](#izin-ayarları)
6. [Sorun Giderme](#sorun-giderme)
7. [Bilinen Sorunlar ve Çözümleri](#bilinen-sorunlar-ve-çözümleri)

## Ön Gereksinimler

- PHP 7.4 veya üzeri (PHP 8.1 önerilir)
- MySQL 5.7 veya üzeri
- cPanel erişim bilgileri
- FTP erişim bilgileri (tercihen)
- SSL sertifikası (HTTPS için)

## Dosya Yükleme

### 1. FTP ile Yükleme (Önerilen)

1. FTP istemcisi (FileZilla vb.) kullanarak cPanel hesabınıza bağlanın
2. Tüm proje dosyalarını `public_html` dizinine yükleyin
   - Büyük dosyaları (ör. `necat.zip`) yüklemek yerine sıkıştırılmış halde site üzerinde açabilirsiniz

### 2. cPanel Dosya Yöneticisi ile Yükleme

1. cPanel kontrol paneline giriş yapın
2. "Dosya Yöneticisi" aracını açın
3. `public_html` dizinine gidin
4. "Yükle" seçeneği ile ZIP dosyasını yükleyin
5. ZIP dosyasını site üzerinde açın

### 3. Önemli Dosyaların Kontrolü

Aşağıdaki dosyaların varlığını ve doğru konumda olduğunu kontrol edin:

1. `scripts/cpanel_fix.php` - Sorun giderme aracı
2. `scripts/cpanel_setup.sh` - İzin ayarlama betiği
3. `scripts/cpanel_compatibility.js` - JavaScript uyumluluk düzeltmeleri
4. `assets/vendor/jquery/jquery.min.js` - jQuery kütüphanesi
5. `assets/vendor/bootstrap/bootstrap.bundle.min.js` - Bootstrap JS kütüphanesi

## Veritabanı Kurulumu

1. cPanel kontrol panelinden "MySQL Veritabanları" aracına gidin
2. Yeni bir veritabanı oluşturun (ör. `necatder_necat`)
3. Yeni bir veritabanı kullanıcısı oluşturun (ör. `necatder_admin`) ve güçlü bir şifre belirleyin
4. Kullanıcıya veritabanı üzerinde tüm izinleri verin
5. phpMyAdmin aracını açın veya cPanel'deki "MySQL Veritabanları" > "phpMyAdmin" seçeneğini tıklayın
6. Oluşturduğunuz veritabanını seçin
7. "İçe Aktar" sekmesini tıklayın
8. "Dosya seçin" ile `necat_dernegi.sql` dosyasını yükleyin
9. "Git" butonuna tıklayarak veritabanı şemasını içe aktarın

## Yapılandırma Ayarları

### 1. Veritabanı Bağlantı Ayarları

`config/database.php` dosyasını düzenleyin:

```php
// cPanel/Üretim ortamı ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'necatder_necat');  // Oluşturduğunuz veritabanı adı
define('DB_USER', 'necatder_admin');  // Oluşturduğunuz kullanıcı adı
define('DB_PASS', 'şifreniz');        // Belirlediğiniz şifre
define('DB_CHARSET', 'utf8mb4');
```

### 2. Site URL Ayarları

`config/database.php` dosyasında SITE_URL değerini güncelleyin:

```php
define('SITE_URL', 'https://www.necatdernegi.org'); // Kendi domain adınız
```

### 3. E-posta Ayarları

cPanel'deki e-posta ayarlarını kullanmak için:

1. cPanel kontrol panelinden "E-posta Hesapları" oluşturun (ör. info@necatdernegi.org)
2. Veritabanında SMTP ayarlarını güncelleyin:

```sql
UPDATE settings SET setting_value = 'localhost' WHERE setting_key = 'smtp_host';
UPDATE settings SET setting_value = 'info@necatdernegi.org' WHERE setting_key = 'smtp_username';
UPDATE settings SET setting_value = 'e-posta-şifreniz' WHERE setting_key = 'smtp_password';
UPDATE settings SET setting_value = 'info@necatdernegi.org' WHERE setting_key = 'smtp_from_email';
```

Alternatif olarak, sorun giderme aracını çalıştırarak otomatik olarak ayarları güncelleyebilirsiniz:

```bash
php scripts/cpanel_fix.php
```

## İzin Ayarları

Yazma izni gerektiren klasörlerin izinlerini ayarlamak için kurulum betiğini çalıştırın:

```bash
bash scripts/cpanel_setup.sh
```

Bu betik aşağıdaki işlemleri yapacaktır:
- Gerekli dizinleri oluşturur
- Dizin izinlerini ayarlar (755 - dizinler, 644 - dosyalar)
- Kritik dosyalar için koruyucu .htaccess dosyaları oluşturur
- Yazılabilir dizinlerde dizin listelemesini engeller

Manuel olarak yapılması gereken izin ayarları:

- `uploads/` dizini: 755
- `logs/` dizini: 755
- `cache/` dizini: 755
- `temp/` dizini: 755

## Sorun Giderme

### Sorun Giderme Aracı

Site ile ilgili sorunları tespit etmek ve çözmek için sorun giderme aracını çalıştırın:

```bash
php scripts/cpanel_fix.php
```

Bu araç aşağıdaki kontrolleri yapar:
- Veritabanı bağlantısı
- Tablo erişimi
- İstatistik verileri
- E-posta ayarları
- JavaScript dosyaları
- Sayfa yapıları
- Footer görünümü

### Yaygın Sorunlar

#### 1. 500 Internal Server Error

- `.htaccess` dosyasını kontrol edin, bazı direktifler sunucunuzda desteklenmiyor olabilir
- PHP sürümünü kontrol edin: cPanel > "Select PHP Version"
- Hata günlüklerini kontrol edin: cPanel > "Error Log" veya `logs/` klasörü

#### 2. Veritabanı Bağlantı Hatası

- Veritabanı kullanıcı adı ve şifrenin doğru olduğundan emin olun
- Veritabanı host adresinin doğru olduğunu kontrol edin (genellikle `localhost`)
- Kullanıcının veritabanına erişim izni olduğunu kontrol edin

#### 3. E-posta Gönderim Sorunları

- SMTP ayarlarınızı kontrol edin
- cPanel'de e-posta hesabının doğru yapılandırıldığından emin olun
- `includes/EmailService.php` dosyasında PHP hatası olup olmadığını kontrol edin

#### 4. Dosya Yükleme Sorunları

- `uploads/` dizinine yazma izninin olduğundan emin olun
- `php.ini` dosyasındaki yükleme limitleri kontrol edin (file_uploads, upload_max_filesize, post_max_size)
- Dosya sahipliği ve grup izinlerini kontrol edin

## Bilinen Sorunlar ve Çözümleri

### 1. İstatistikler Görünmüyor

Eğer ana sayfada istatistikler (Tamamlanan Proje, Gönüllülerimiz, Yardım Edilen Aile) görünmüyorsa:

1. Sorun giderme aracını çalıştırın:
   ```bash
   php scripts/cpanel_fix.php
   ```

2. Veya veritabanında manuel olarak istatistik değerlerini güncelleyin:
   ```sql
   INSERT INTO settings (setting_key, setting_value) VALUES ('stats_projects', '10');
   INSERT INTO settings (setting_key, setting_value) VALUES ('stats_volunteers', '25');
   INSERT INTO settings (setting_key, setting_value) VALUES ('stats_beneficiaries', '5000');
   ```

### 2. Form Gönderim Sorunları

Eğer formlar çalışmıyorsa:

1. `scripts/cpanel_compatibility.js` dosyasının header'a eklendiğinden emin olun
2. Tarayıcı konsolunda JavaScript hatalarını kontrol edin
3. AJAX isteklerinin doğru URL'ye gittiğinden emin olun

### 3. Accordion (FAQ) Çalışmıyor

Eğer FAQ sayfasında soruların cevapları açılmıyorsa:

1. Bootstrap JS dosyasının doğru yüklendiğinden emin olun
2. `scripts/cpanel_compatibility.js` dosyasının header'a eklendiğinden emin olun
3. Tarayıcı konsolunda JavaScript hatalarını kontrol edin

### 4. Contact Sayfası Görünmüyor

Eğer contact sayfası görünmüyorsa:

1. `pages/contact.php` dosyasının var olduğunu kontrol edin
2. `.htaccess` dosyasındaki yönlendirmeleri kontrol edin
3. PHP hatalarını kontrol edin: cPanel > "Error Log"

### 5. Kopyalama İşlevi Çalışmıyor

Eğer hesap bilgilerini kopyalama işlevi çalışmıyorsa:

1. HTTPS kullanıp kullanmadığınızı kontrol edin (Clipboard API sadece HTTPS üzerinde çalışır)
2. `scripts/cpanel_compatibility.js` dosyasının header'a eklendiğinden emin olun
3. Tarayıcı konsolunda JavaScript hatalarını kontrol edin

### 6. Footer Görünmüyor

Eğer footer kısmı tam olarak görünmüyorsa:

1. CSS dosyalarının doğru yüklendiğinden emin olun
2. `scripts/cpanel_compatibility.js` dosyasının header'a eklendiğinden emin olun
3. Tarayıcı konsolunda JavaScript hatalarını kontrol edin

## Son Kontroller

Site yayına alındıktan sonra aşağıdaki kontrolleri yapın:

1. Ana sayfadaki istatistiklerin doğru görüntülendiğini kontrol edin
2. Tüm formların çalıştığını test edin
3. FAQ sayfasındaki accordion'ların açılıp kapandığını kontrol edin
4. Donate sayfasındaki kopyalama işlevinin çalıştığını test edin
5. Footer'ın tam olarak görüntülendiğini kontrol edin

---

Bu kurulum kılavuzu, Necat Derneği web sitesinin cPanel ortamında sorunsuz çalışması için gerekli adımları içermektedir. Site yapılandırması veya sorun giderme konusunda yardıma ihtiyacınız olursa lütfen yönetici ile iletişime geçiniz. 