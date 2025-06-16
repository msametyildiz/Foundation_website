-- Hero Section ve İstatistik verileri için settings tablosuna eklemeler
-- Bu dosya mevcut necat_dernegi veritabanına uygulanmalıdır

INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `label`, `description`, `sort_order`, `is_required`, `created_at`, `updated_at`) VALUES
('hero_title', 'Umut Olmaya Devam Ediyoruz', 'text', 'anasayfa', 'Ana Başlık', 'Anasayfa hero başlığı', 1, 1, NOW(), NOW()),
('hero_subtitle', 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.', 'textarea', 'anasayfa', 'Alt Başlık', 'Anasayfa hero alt başlığı', 2, 1, NOW(), NOW()),
('stats_projects', '10', 'number', 'istatistik', 'Proje Sayısı', 'Toplam proje sayısı', 1, 0, NOW(), NOW()),
('stats_beneficiaries', '5000', 'number', 'istatistik', 'Yararlanıcı Sayısı', 'Toplam yararlanıcı sayısı', 2, 0, NOW(), NOW()),
('stats_volunteers', '25', 'number', 'istatistik', 'Gönüllü Sayısı', 'Aktif gönüllü sayısı', 3, 0, NOW(), NOW()),
('stats_donations', '500000', 'number', 'istatistik', 'Toplam Bağış', 'Toplam bağış miktarı (TL)', 4, 0, NOW(), NOW());
