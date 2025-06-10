-- Settings table for storing system configuration
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Necat Derneği'),
('site_description', 'İnsani yardım ve sosyal sorumluluk projelerimizle topluma hizmet ediyoruz.'),
('contact_email', 'info@necatdernegi.org'),
('contact_phone', '+90 (212) 123 45 67'),
('contact_address', 'İstanbul, Türkiye'),
('social_facebook', ''),
('social_twitter', ''),
('social_instagram', ''),
('social_youtube', ''),
('smtp_host', ''),
('smtp_port', '587'),
('smtp_username', ''),
('smtp_password', ''),
('smtp_encryption', 'tls'),
('from_email', ''),
('from_name', 'Necat Derneği'),
('maintenance_mode', '0'),
('login_attempts', '5'),
('session_timeout', '3600'),
('password_min_length', '8'),
('require_2fa', '0')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
