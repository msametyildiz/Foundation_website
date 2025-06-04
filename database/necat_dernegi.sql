-- Necat Derneği Veritabanı Yapısı
-- MySQL 5.7+ / 8.x uyumlu

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Yönetici kullanıcıları tablosu
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan admin kullanıcı (şifre: admin123)
INSERT INTO `admins` (`username`, `password`, `email`, `full_name`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@necatdernegi.org', 'Sistem Yöneticisi');

-- Bağış türleri tablosu
CREATE TABLE `donation_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan bağış türleri
INSERT INTO `donation_types` (`name`, `description`, `sort_order`) VALUES
('Genel Bağış', 'Derneğin genel faaliyetleri için bağış', 1),
('Afet Yardımı', 'Afet durumlarında acil yardım için bağış', 2),
('Eğitim Desteği', 'Eğitim projelerimiz için bağış', 3),
('Sağlık Yardımı', 'Sağlık hizmetleri için bağış', 4),
('Gıda Yardımı', 'Gıda kolisi ve yemek yardımı için bağış', 5);

-- Bağışlar/Dekontlar tablosu
CREATE TABLE `donations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_name` varchar(100) NOT NULL,
  `donor_email` varchar(100) DEFAULT NULL,
  `donor_phone` varchar(20) DEFAULT NULL,
  `donation_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `receipt_file` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `donation_type_id` (`donation_type_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donation_type_id`) REFERENCES `donation_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- IBAN hesap bilgileri tablosu
CREATE TABLE `iban_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(10) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `iban` varchar(50) NOT NULL,
  `swift` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan IBAN bilgileri
INSERT INTO `iban_accounts` (`currency`, `bank_name`, `account_name`, `account_number`, `iban`, `swift`, `sort_order`) VALUES
('TRY', 'Vakıfbank', 'Necat Derneği', '0015-158007315056349', 'TR45 0001 5001 5800 7315 0563 49', 'TVBATR2A', 1),
('USD', 'Garanti BBVA', 'Necat Derneği', '0062-123456789012', 'TR32 0006 2000 1230 0006 2978 90', 'TGBATRIS', 2),
('EUR', 'İş Bankası', 'Necat Derneği', '0064-987654321098', 'TR56 0006 4000 0011 2345 6789 01', 'ISBKTRIS', 3);

-- SSS tablosu
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan SSS kayıtları
INSERT INTO `faq` (`question`, `answer`, `category`, `sort_order`) VALUES
('Nasıl bağış yapabilirim?', 'Bağış yapmak için "Bağış Yap" sayfasını ziyaret edebilir, hesap bilgilerimizi kullanarak bağış yapabilir ve dekontunuzu yükleyebilirsiniz.', 'Bağış', 1),
('Bağışımın takibi nasıl yapılır?', 'Dekont yüklediğinizde size bir referans numarası verilir. Bu numara ile bağışınızın durumunu takip edebilirsiniz.', 'Bağış', 2),
('Gönüllü olmak için nasıl başvururum?', '"Gönüllü Ol" sayfasından başvuru formunu doldurarak bizimle iletişime geçebilirsiniz.', 'Gönüllülük', 3),
('Hangi projelerde çalışıyorsunuz?', 'Eğitim, sağlık, afet yardımı ve sosyal sorumluluk alanlarında çeşitli projelerimiz bulunmaktadır. Detaylar için "Projelerimiz" sayfasını ziyaret edebilirsiniz.', 'Projeler', 4),
('Faaliyet raporlarınızı nereden görebilirim?', 'Yıllık faaliyet raporlarımızı "Belgelerimiz" sayfasından indirebilirsiniz.', 'Genel', 5);

-- Slider tablosu
CREATE TABLE `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `link_text` varchar(50) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan slider kayıtları
INSERT INTO `slider` (`image`, `title`, `description`, `link`, `link_text`, `sort_order`) VALUES
('uploads/slider/slider1.jpg', 'Yardım Eli Uzatın', 'Muhtaç ailelere ulaşan yardımlarınızla umut olmaya devam ediyoruz.', 'index.php?page=donate', 'Bağış Yap', 1),
('uploads/slider/slider2.jpg', 'Eğitim Projelerimiz', 'Çocukların geleceği için eğitim projelerimize destek verin.', 'index.php?page=projects', 'Projeleri Gör', 2),
('uploads/slider/slider3.jpg', 'Gönüllü Olun', 'Birlikte daha güçlüyüz. Gönüllü ekibimize katılın.', 'index.php?page=volunteer', 'Gönüllü Ol', 3);

-- Projeler tablosu
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `target_amount` decimal(10,2) DEFAULT NULL,
  `collected_amount` decimal(10,2) DEFAULT 0.00,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planning','active','completed','paused') NOT NULL DEFAULT 'planning',
  `category` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `beneficiaries` int(11) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `category` (`category`),
  KEY `is_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan projeler
INSERT INTO `projects` (`title`, `slug`, `short_description`, `description`, `image`, `target_amount`, `start_date`, `status`, `category`, `location`, `beneficiaries`, `is_featured`, `sort_order`) VALUES
('Kış Yardımı Projesi', 'kis-yardimi-projesi', 'Kış aylarında muhtaç ailelere gıda ve giyim yardımı', 'Kış aylarında zorlu koşullarda yaşayan ailelere sıcak giyim ve gıda yardımı ulaştırıyoruz. Bu proje kapsamında 500 aileye ulaşmayı hedefliyoruz.', 'uploads/projects/kis-yardimi.jpg', 100000.00, '2024-12-01', 'active', 'Sosyal Yardım', 'İstanbul', 500, 1, 1),
('Eğitim Burs Projesi', 'egitim-burs-projesi', 'Başarılı öğrencilere eğitim bursu desteği', 'Maddi imkansızlıklar nedeniyle eğitimini sürdüremeyen başarılı öğrencilere burs desteği sağlıyoruz.', 'uploads/projects/egitim-bursu.jpg', 150000.00, '2024-09-01', 'active', 'Eğitim', 'Türkiye Geneli', 100, 1, 2),
('Sağlık Tarama Projesi', 'saglik-tarama-projesi', 'Kırsal bölgelerde ücretsiz sağlık taraması', 'Sağlık hizmetlerine erişimi zor olan kırsal bölgelerde ücretsiz sağlık taraması ve muayene hizmeti veriyoruz.', 'uploads/projects/saglik-tarama.jpg', 80000.00, '2024-06-01', 'completed', 'Sağlık', 'Anadolu', 1000, 0, 3);

-- Takım üyeleri tablosu
CREATE TABLE `team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `category` enum('yonetim','danisma','genel') NOT NULL DEFAULT 'genel',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan takım üyeleri
INSERT INTO `team_members` (`name`, `position`, `bio`, `image`, `category`, `sort_order`) VALUES
('Ahmet Yılmaz', 'Yönetim Kurulu Başkanı', '20 yıllık iş deneyimi ile sosyal sorumluluk projelerinde aktif rol almaktadır.', 'uploads/team/ahmet-yilmaz.jpg', 'yonetim', 1),
('Fatma Demir', 'Genel Koordinatör', 'Sosyal hizmet alanında uzman olup, proje yönetimi konusunda deneyimlidir.', 'uploads/team/fatma-demir.jpg', 'yonetim', 2),
('Mehmet Kaya', 'Mali İşler Sorumlusu', 'Muhasebe ve finans alanında 15 yıllık deneyime sahiptir.', 'uploads/team/mehmet-kaya.jpg', 'yonetim', 3);

-- Belgeler tablosu
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_type` varchar(20) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `year` (`year`),
  KEY `is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan belgeler
INSERT INTO `documents` (`title`, `description`, `file_path`, `category`, `year`, `sort_order`) VALUES
('2023 Faaliyet Raporu', '2023 yılı faaliyetlerimizin detaylı raporu', 'uploads/documents/faaliyet-raporu-2023.pdf', 'Faaliyet Raporu', 2023, 1),
('2023 Mali Rapor', '2023 yılı gelir-gider tablosu ve mali durum raporu', 'uploads/documents/mali-rapor-2023.pdf', 'Mali Rapor', 2023, 2),
('Dernek Tüzüğü', 'Necat Derneği kuruluş tüzüğü', 'uploads/documents/dernek-tuzugu.pdf', 'Tüzük', NULL, 3),
('2022 Faaliyet Raporu', '2022 yılı faaliyetlerimizin detaylı raporu', 'uploads/documents/faaliyet-raporu-2022.pdf', 'Faaliyet Raporu', 2022, 4);

-- İletişim mesajları tablosu
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `admin_reply` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gönüllü başvuruları tablosu
CREATE TABLE `volunteer_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `age` int(3) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','reviewed','approved','rejected') NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site ayarları tablosu
CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','number','boolean','image','email') NOT NULL DEFAULT 'text',
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan site ayarları
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `description`) VALUES
('site_title', 'Necat Derneği', 'text', 'genel', 'Site başlığı'),
('site_description', 'Yardım eli uzatan, umut dağıtan bir toplum için birlikte çalışıyoruz.', 'textarea', 'genel', 'Site açıklaması'),
('contact_email', 'info@necatdernegi.org', 'email', 'iletisim', 'İletişim e-posta adresi'),
('contact_phone', '+90 212 123 45 67', 'text', 'iletisim', 'İletişim telefonu'),
('contact_address', 'Örnek Mahalle, Örnek Sokak No:1, İstanbul', 'textarea', 'iletisim', 'Adres bilgisi'),
('facebook_url', '#', 'text', 'sosyal', 'Facebook sayfası'),
('twitter_url', '#', 'text', 'sosyal', 'Twitter hesabı'),
('instagram_url', '#', 'text', 'sosyal', 'Instagram hesabı'),
('linkedin_url', '#', 'text', 'sosyal', 'LinkedIn sayfası');

COMMIT;
