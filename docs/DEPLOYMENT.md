# Necat Derneği Web Sitesi - cPanel Deployment Kılavuzu

## Gereksinimler

### Hosting Gereksinimleri
- **PHP Sürümü**: 7.4 veya üzeri (önerilen: PHP 8.0+)
- **MySQL Sürümü**: 5.7 veya üzeri (önerilen: MySQL 8.0+)
- **Disk Alanı**: Minimum 500MB (önerilen: 1GB+)
- **Bandwidth**: Aylık 10GB+ (site trafiğine göre)

### PHP Modülleri
Aşağıdaki PHP modüllerinin aktif olması gerekir:
- `mysqli` veya `pdo_mysql`
- `gd` (resim işleme için)
- `mbstring` (Unicode desteği için)
- `openssl` (güvenlik için)
- `curl` (dış API çağrıları için)
- `zip` (dosya sıkıştırma için)

## Deployment Adımları

### 1. Dosyaları Yükleme

#### cPanel File Manager ile:
1. cPanel'e giriş yapın
2. **File Manager**'ı açın
3. `public_html` klasörüne gidin
4. Tüm proje dosyalarını yükleyin
5. Dosya yapısı şu şekilde olmalı:
   ```
   public_html/
   ├── index.php
   ├── admin/
   ├── ajax/
   ├── assets/
   ├── config/
   ├── includes/
   ├── pages/
   ├── uploads/
   ├── sql/
   └── .htaccess
   ```

#### FTP ile:
1. FTP programını (FileZilla vb.) kullanarak hosting'e bağlanın
2. Local'deki tüm dosyaları `public_html` klasörüne upload edin

### 2. Veritabanı Kurulumu

#### MySQL Veritabanı Oluşturma:
1. cPanel > **MySQL Databases**'e gidin
2. Yeni veritabanı oluşturun: `necatdernegi_db`
3. Yeni MySQL kullanıcısı oluşturun: `necatdernegi_user`
4. Güvenli şifre belirleyin
5. Kullanıcıyı veritabanına ekleyin (ALL PRIVILEGES verin)

#### SQL Dosyalarını İçe Aktarma:
1. cPanel > **phpMyAdmin**'e gidin
2. Oluşturduğunuz veritabanını seçin
3. **Import** sekmesine gidin
4. Sırasıyla şu dosyaları import edin:
   - `sql/necat_dernegi.sql` (ana tablo yapısı)
   - `sql/settings.sql` (ayarlar tablosu)
   - `sql/admin_logs.sql` (log tablosu)

### 3. Konfigürasyon

#### Database Bağlantısı:
`config/database.php` dosyasını açın ve hosting bilgilerinizle güncelleyin:

```php
<?php
$host = 'localhost'; // Genellikle localhost
$dbname = 'hosting_kullaniciadi_necatdernegi_db';
$username = 'hosting_kullaniciadi_necatdernegi_user';
$password = 'gercek_sifreniz';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
```

### 4. Klasör İzinleri

Aşağıdaki klasörlere yazma izni verin (chmod 755 veya 777):
```bash
chmod 755 uploads/
chmod 755 uploads/images/
chmod 755 uploads/documents/
chmod 755 uploads/receipts/
```

cPanel File Manager'da:
1. Klasöre sağ tıklayın
2. **Permissions**'u seçin
3. **Owner: Read, Write, Execute** seçin
4. **Group: Read, Execute** seçin
5. **World: Read, Execute** seçin

### 5. SSL Sertifikası

#### Let's Encrypt (Ücretsiz):
1. cPanel > **SSL/TLS**'e gidin
2. **Let's Encrypt**'i seçin
3. Domain'inizi seçip sertifika oluşturun

#### Hosting Sağlayıcısının SSL'i:
Hosting sağlayıcınızın sunduğu SSL seçeneklerini kullanın

### 6. Email Konfigürasyonu

#### SMTP Ayarları:
1. cPanel > **Email Accounts**'a gidin
2. Email hesabı oluşturun: `noreply@yourdomain.com`
3. Admin panelinden **Ayarlar > Email Ayarları**'na gidin
4. SMTP bilgilerini girin:
   - **SMTP Host**: mail.yourdomain.com
   - **SMTP Port**: 587 (TLS) veya 465 (SSL)
   - **Username**: noreply@yourdomain.com
   - **Password**: email hesabı şifresi

### 7. Admin Kullanıcısı Oluşturma

phpMyAdmin'de aşağıdaki SQL'i çalıştırın:

```sql
INSERT INTO users (username, email, password, full_name, role, status, created_at) 
VALUES ('admin', 'admin@yourdomain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Site Yöneticisi', 'admin', 'active', NOW());
```

Varsayılan şifre: `password` (giriş yaptıktan sonra mutlaka değiştirin!)

### 8. Güvenlik Ayarları

#### .htaccess Kontrolü:
`.htaccess` dosyasının doğru yüklendiğinden emin olun. İçeriği:

```apache
# Security Headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"

# Hide server information
ServerTokens Prod
Header unset Server

# Prevent access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(htaccess|htpasswd|ini|log|sh|inc|bak)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Protect config and includes directories
<DirectoryMatch "^.*(config|includes|sql).*$">
    Order allow,deny
    Deny from all
</DirectoryMatch>

# URL Rewriting
RewriteEngine On

# Redirect to HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Remove www
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

# Pretty URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^/]+)/?$ index.php?page=$1 [L,QSA]

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresDefault "access plus 2 days"
</IfModule>
```

### 9. Son Kontroller

#### Site Testi:
1. Ana sayfaya gidin: `https://yourdomain.com`
2. İletişim formunu test edin
3. Gönüllü başvuru formunu test edin
4. Admin paneline giriş yapın: `https://yourdomain.com/admin`

#### Admin Panel Testi:
1. Dashboard'ın yüklendiğini kontrol edin
2. Her menü öğesinin çalıştığını test edin
3. Email ayarlarını test edin
4. Dosya yükleme işlevini test edin

### 10. Performans Optimizasyonu

#### Cloudflare (Önerilen):
1. Cloudflare hesabı oluşturun
2. Domain'inizi ekleyin
3. DNS kayıtlarını güncelleyin
4. SSL ve caching'i aktifleştirin

#### Image Optimization:
1. Resimleri sıkıştırın (TinyPNG vs.)
2. WebP formatını kullanın
3. Lazy loading uygulayın

### 11. Backup Stratejisi

#### Otomatik Backup:
1. cPanel > **Backups**'a gidin
2. Otomatik backup'ı aktifleştirin
3. Backup sıklığını ayarlayın (günlük önerilen)

#### Manuel Backup:
```bash
# Database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf website_backup_$(date +%Y%m%d).tar.gz public_html/
```

### 12. Monitoring ve Maintenance

#### Log Kontrolü:
- cPanel > **Error Logs**'u düzenli kontrol edin
- Admin panelindeki logları takip edin
- Email delivery'yi izleyin

#### Güncelleme Kontrolleri:
- PHP sürümünü güncel tutun
- Security patch'lerini takip edin
- Dependency'leri güncelleyin

### 13. Troubleshooting

#### Yaygın Sorunlar:

**500 Internal Server Error:**
- `.htaccess` dosyasını kontrol edin
- PHP error log'larına bakın
- File permissions'ları kontrol edin

**Database Connection Error:**
- Database bilgilerini kontrol edin
- MySQL servisinin çalıştığından emin olun
- Hosting sağlayıcısına danışın

**Email Gönderilmiyor:**
- SMTP ayarlarını kontrol edin
- Email limitlerini kontrol edin
- Anti-spam policy'lerini gözden geçirin

**Upload Dosyası Çalışmıyor:**
- `uploads/` klasör izinlerini kontrol edin
- PHP upload limits'lerini kontrol edin
- File size restrictions'ları kontrol edin

### 14. Support ve Dokümantasyon

#### Teknik Destek:
- Hosting sağlayıcınızın support team'ine başvurun
- cPanel dokümantasyonunu inceleyin
- PHP ve MySQL dokümantasyonlarını takip edin

#### Kod Dokümantasyonu:
- `/docs/` klasöründeki teknik dokümantasyonu okuyun
- Kod yorumlarını takip edin
- Database schema'yı inceleyin

---

## Deployment Checklist

- [ ] Hosting gereksinimlerini kontrol ettim
- [ ] Dosyaları public_html'e yükledim
- [ ] Veritabanını oluşturdum
- [ ] SQL dosyalarını import ettim
- [ ] Database konfigürasyonunu güncelledim
- [ ] Klasör izinlerini ayarladım
- [ ] SSL sertifikası aktifleştirdim
- [ ] Email ayarlarını yapılandırdım
- [ ] Admin kullanıcısı oluşturdum
- [ ] .htaccess dosyasını kontrol ettim
- [ ] Site işlevselliğini test ettim
- [ ] Admin panelini test ettim
- [ ] Performans optimizasyonu yaptım
- [ ] Backup stratejisi oluşturdum
- [ ] Monitoring araçlarını kurdum

---

Bu kılavuzu takip ederek Necat Derneği web sitesini başarıyla cPanel hosting'e deploy edebilirsiniz. Herhangi bir sorun yaşarsanız, hosting sağlayıcınızın teknik desteğine başvurun.
