-- İletişim Bilgileri Kartları için yeni tablo
CREATE TABLE `contact_info_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `icon` varchar(50) NOT NULL,
  `button_text` varchar(50) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `button_type` enum('link', 'tel', 'email', 'external') DEFAULT 'link',
  `color` varchar(20) DEFAULT '#4ea674',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mevcut iletişim kartlarını ekle
INSERT INTO `contact_info_cards` (`title`, `content`, `icon`, `button_text`, `button_url`, `button_type`, `sort_order`) VALUES
('Adresimiz', 'Fevzipaşa Mahallesi Rüzgarlı Caddesi Plevne Sokak No:14/1 Ulus Altındağ Ankara', 'fas fa-map-marker-alt', 'Yol Tarifi', 'https://maps.google.com/?q=Fevzipaşa Mahallesi Rüzgarlı Caddesi Plevne Sokak No:14/1 Ulus Altındağ Ankara', 'external', 1),
('Telefon', '<strong>Genel:</strong> +90 312 311 65 25<br><strong>Acil:</strong> +90 312 311 65 25', 'fas fa-phone', 'Ara', '+903123116525', 'tel', 2),
('E-posta', 'info@necatdernegi.org', 'fas fa-envelope', 'Mail Gönder', 'mailto:info@necatdernegi.org', 'email', 3),
('Çalışma Saatleri', 'Pazartesi - Cuma: 09:00 - 18:00<br>Cumartesi: 09:00 - 14:00<br>Pazar: Kapalı', 'fas fa-clock', NULL, NULL, 'link', 4);

-- Ek iletişim kartları eklenebilir
INSERT INTO `contact_info_cards` (`title`, `content`, `icon`, `button_text`, `button_url`, `button_type`, `sort_order`) VALUES
('Faks', '+90 312 311 65 26', 'fas fa-fax', NULL, NULL, 'link', 5),
('WhatsApp', '+90 532 123 45 67', 'fab fa-whatsapp', 'WhatsApp', 'https://wa.me/905321234567', 'external', 6);
