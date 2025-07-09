#!/bin/bash
# cPanel Setup Script for Necat Derneği Website
# This script configures file permissions and directory structure for cPanel hosting

# Renkli çıktı için
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Necat Derneği cPanel Kurulum Betiği${NC}"
echo "Bu betik, cPanel'de site kurulumu için gerekli ayarlamaları yapacak"
echo "---------------------------------------------------------------"

# Ana dizin kontrolü
if [ ! -f "index.php" ] || [ ! -d "includes" ]; then
  echo -e "${RED}Hata: Bu betik ana dizinde çalıştırılmalıdır.${NC}"
  exit 1
fi

# Dizin yapısı kontrolü ve oluşturma
echo -e "${YELLOW}Dizin yapısı kontrol ediliyor...${NC}"

directories=(
  "cache"
  "logs"
  "uploads"
  "uploads/receipts"
  "backups"
  "temp"
)

for dir in "${directories[@]}"; do
  if [ ! -d "$dir" ]; then
    echo -e "Dizin oluşturuluyor: ${GREEN}$dir${NC}"
    mkdir -p "$dir"
  else
    echo -e "Dizin mevcut: ${GREEN}$dir${NC}"
  fi
done

# İzin ayarları
echo -e "${YELLOW}Dizin izinleri ayarlanıyor...${NC}"

# Yazılabilir dizinler
writable_dirs=(
  "cache"
  "logs"
  "uploads"
  "uploads/receipts"
  "temp"
)

# Yazılabilir dizin izinleri
for dir in "${writable_dirs[@]}"; do
  if [ -d "$dir" ]; then
    echo -e "İzin ayarlanıyor: ${GREEN}$dir${NC} (755)"
    chmod -R 755 "$dir"
    echo -e "İzin ayarlanıyor: ${GREEN}$dir${NC} (web sunucusu yazabilsin)"
    find "$dir" -type d -exec chmod 755 {} \;
    find "$dir" -type f -exec chmod 644 {} \;
  fi
done

# .htaccess oluşturma ve koruma
echo -e "${YELLOW}Dizinler için .htaccess koruma ekleniyor...${NC}"

protected_dirs=(
  "logs"
  "backups"
  "config"
)

for dir in "${protected_dirs[@]}"; do
  if [ -d "$dir" ]; then
    echo -e "Koruma .htaccess oluşturuluyor: ${GREEN}$dir/.htaccess${NC}"
    cat > "$dir/.htaccess" <<EOL
# Dizin erişim engeli
<FilesMatch ".*">
  Order Allow,Deny
  Deny from all
</FilesMatch>

# PHP dosyalarının çalışmasını engelle
<Files ~ "\.(php|phtml|php3|php4|php5|php7|phps)$">
  Order Allow,Deny
  Deny from all
</Files>
EOL
  fi
done

# Uploads güvenliği
echo -e "${YELLOW}Upload klasörü için güvenlik ayarları yapılıyor...${NC}"
if [ -d "uploads" ]; then
  echo -e "Koruma .htaccess oluşturuluyor: ${GREEN}uploads/.htaccess${NC}"
  cat > "uploads/.htaccess" <<EOL
# PHP dosyalarını engelle
<FilesMatch "\.(?i:php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$">
  Order Allow,Deny
  Deny from all
</FilesMatch>

# Sadece resim dosyalarına izin ver
<FilesMatch "\.(?i:gif|jpe?g|png|pdf|webp|ico)$">
  Order Deny,Allow
  Allow from all
</FilesMatch>
EOL
fi

# config klasörü koruma
if [ -d "config" ]; then
  echo -e "${YELLOW}Config dosyaları için izinler ayarlanıyor...${NC}"
  chmod 644 config/*.php
fi

# Ana dizin için PHP işlenmeyen dosyaların korunması
echo -e "${YELLOW}Ana dizin güvenliği sağlanıyor...${NC}"
find . -name "*.json" -type f -exec chmod 644 {} \;
find . -name "*.lock" -type f -exec chmod 644 {} \;
find . -name "*.md" -type f -exec chmod 644 {} \;
find . -name "*.sql" -type f -exec chmod 644 {} \;

# İndex dosyaları için yürütme izinleri
echo -e "${YELLOW}PHP dosyaları için izinler ayarlanıyor...${NC}"
chmod 644 index.php
chmod 644 includes/*.php
chmod 644 pages/*.php

# Tüm yazılabilir klasörlerde boş index.html dosyası oluşturma
echo -e "${YELLOW}Dizin listeleme koruması ekleniyor...${NC}"
for dir in "${writable_dirs[@]}"; do
  if [ -d "$dir" ]; then
    echo -e "index.html oluşturuluyor: ${GREEN}$dir/index.html${NC}"
    echo "<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don't have permission to access this resource.</p></body></html>" > "$dir/index.html"
    chmod 644 "$dir/index.html"
  fi
done

# Yükleme tamamlandı
echo -e "${GREEN}Kurulum tamamlandı!${NC}"
echo "Tüm dizinler ve izinler ayarlandı."
echo "---------------------------------------------------------------"
echo -e "${YELLOW}Lütfen config/database.php dosyasındaki veritabanı ayarlarını kontrol edin.${NC}"
echo "cPanel'de site sorunsuz çalışmalıdır." 