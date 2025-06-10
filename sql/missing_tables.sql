-- Eksik tablolar için SQL

-- News tablosu
CREATE TABLE IF NOT EXISTS `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `type` enum('news','press','announcement') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'news',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `author_id` int DEFAULT NULL,
  `views` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `is_featured` (`is_featured`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings tablosu (site_settings'ten farklı)
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users tablosu (admin_logs foreign key için gerekli)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volunteers tablosu (volunteer.php için)
CREATE TABLE IF NOT EXISTS `volunteers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci,
  `availability` text COLLATE utf8mb4_unicode_ci,
  `motivation` text COLLATE utf8mb4_unicode_ci,
  `experience` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `application_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for news
INSERT INTO `news` (`title`, `slug`, `content`, `excerpt`, `status`, `type`, `is_featured`) VALUES
('Kış Yardımı Kampanyası Başladı', 'kis-yardimi-kampanyasi-basladi', 'Bu kış da muhtaç ailelere ulaşmak için kapsamlı bir yardım kampanyası başlattık...', 'Kış aylarında muhtaç ailelere destek olmak için kampanyamız başladı.', 'published', 'news', 1),
('Eğitim Bursu Başvuruları', 'egitim-bursu-basvurulari', 'Başarılı öğrencilerimiz için eğitim bursu başvuruları açıldı...', 'Eğitim bursu başvuruları için son tarih yaklaşıyor.', 'published', 'news', 0),
('Basın Açıklaması: Afet Yardımları', 'basin-aciklamasi-afet-yardimlari', 'Son yaşanan doğal afet sonrası yardım çalışmalarımız hızla devam ediyor...', 'Afet bölgesindeki çalışmalarımız hakkında basın açıklaması.', 'published', 'press', 1);

-- Sample data for settings  
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('smtp_host', 'smtp.gmail.com'),
('smtp_port', '587'),
('smtp_username', ''),
('smtp_password', ''),
('smtp_secure', 'tls'),
('email_from_name', 'Necat Derneği'),
('email_from_address', 'info@necatdernegi.org');

-- Sample user (admin için)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Sistem Yöneticisi', 'admin@necatdernegi.org', '$2y$10$iEo2s0nTmeV6cE3JLUalbOQpfRpZ/Upl78q99Vkj4XNOtrwRbG88a', 'admin');
