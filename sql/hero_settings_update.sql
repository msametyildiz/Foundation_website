-- Hero Section ve İstatistik Ayarları
-- Bu dosya home.php sayfasının Hero Section'ını dinamik hale getirmek için gerekli ayarları ekler

-- Mevcut hero_title ayarını güncelle veya ekle
INSERT INTO settings (setting_key, setting_value, setting_type, category, label, description, sort_order, is_required, created_at, updated_at) 
VALUES ('hero_title', 'Umut Olmaya Devam Ediyoruz', 'text', 'anasayfa', 'Ana Başlık', 'Anasayfa hero başlığı', 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    setting_value = VALUES(setting_value),
    updated_at = NOW();

-- Mevcut hero_subtitle ayarını güncelle veya ekle
INSERT INTO settings (setting_key, setting_value, setting_type, category, label, description, sort_order, is_required, created_at, updated_at) 
VALUES ('hero_subtitle', 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.', 'textarea', 'anasayfa', 'Alt Başlık', 'Anasayfa hero alt başlığı', 2, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    setting_value = VALUES(setting_value),
    updated_at = NOW();

-- İstatistik ayarları
INSERT INTO settings (setting_key, setting_value, setting_type, category, label, description, sort_order, is_required, created_at, updated_at) 
VALUES ('stats_projects', '10', 'number', 'istatistik', 'Proje Sayısı', 'Toplam proje sayısı', 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    setting_value = VALUES(setting_value),
    updated_at = NOW();

INSERT INTO settings (setting_key, setting_value, setting_type, category, label, description, sort_order, is_required, created_at, updated_at) 
VALUES ('stats_beneficiaries', '5000', 'number', 'istatistik', 'Yararlanıcı Sayısı', 'Toplam yararlanıcı sayısı', 2, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    setting_value = VALUES(setting_value),
    updated_at = NOW();

INSERT INTO settings (setting_key, setting_value, setting_type, category, label, description, sort_order, is_required, created_at, updated_at) 
VALUES ('stats_volunteers', '25', 'number', 'istatistik', 'Gönüllü Sayısı', 'Aktif gönüllü sayısı', 3, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    setting_value = VALUES(setting_value),
    updated_at = NOW();

INSERT INTO settings (setting_key, setting_value, setting_type, category, label, description, sort_order, is_required, created_at, updated_at) 
VALUES ('stats_donations', '500000', 'number', 'istatistik', 'Toplam Bağış', 'Toplam bağış miktarı (TL)', 4, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    setting_value = VALUES(setting_value),
    updated_at = NOW();
