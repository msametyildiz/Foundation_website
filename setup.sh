#!/bin/bash

# Necat Derneği Site Setup Script

echo "Necat Derneği Site Kurulum Scripti"
echo "=================================="

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "MySQL kurulu değil. MySQL kurulumu yapılıyor..."
    sudo apt update
    sudo apt install mysql-server -y
    
    echo "MySQL güvenlik ayarları yapılıyor..."
    sudo mysql_secure_installation
fi

# Create database and user
echo "Veritabanı oluşturuluyor..."
read -p "MySQL root şifresi: " -s MYSQL_ROOT_PASS
echo ""

mysql -u root -p$MYSQL_ROOT_PASS << EOF
CREATE DATABASE IF NOT EXISTS necat_dernegi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'necat_user'@'localhost' IDENTIFIED BY 'necat_secure_2024!';
GRANT ALL PRIVILEGES ON necat_dernegi.* TO 'necat_user'@'localhost';
FLUSH PRIVILEGES;
EOF

if [ $? -eq 0 ]; then
    echo "Veritabanı başarıyla oluşturuldu!"
    
    # Update database configuration
    echo "Veritabanı ayarları güncelleniyor..."
    sed -i "s/your_db_user/necat_user/g" /home/muhammed-samet-yildiz/necat_dernegi_site/config/database.php
    sed -i "s/your_db_password/necat_secure_2024!/g" /home/muhammed-samet-yildiz/necat_dernegi_site/config/database.php
    sed -i "s/your_secret_key_here_change_this/$(openssl rand -hex 32)/g" /home/muhammed-samet-yildiz/necat_dernegi_site/config/database.php
    
    # Run database migrations
    echo "Veritabanı tabloları oluşturuluyor..."
    mysql -u necat_user -pnecat_secure_2024! necat_dernegi < /home/muhammed-samet-yildiz/necat_dernegi_site/database/necat_dernegi.sql
    mysql -u necat_user -pnecat_secure_2024! necat_dernegi < /home/muhammed-samet-yildiz/necat_dernegi_site/sql/admin_logs.sql
    
    echo "Kurulum tamamlandı!"
    echo "Site: http://localhost/necat_dernegi_site"
    echo "Admin Panel: http://localhost/necat_dernegi_site/admin"
    echo "Veritabanı Kullanıcısı: necat_user"
    echo "Veritabanı Şifresi: necat_secure_2024!"
else
    echo "Veritabanı oluşturulamadı. Lütfen MySQL ayarlarınızı kontrol edin."
fi
