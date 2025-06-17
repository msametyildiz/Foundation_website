-- Projects Hero Settings Table
CREATE TABLE IF NOT EXISTS `projects_hero_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hero_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT 'Projelerimiz',
  `hero_subtitle` text COLLATE utf8mb4_unicode_ci,
  `show_stats` tinyint(1) DEFAULT 1,
  `stats_title_1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Aktif Proje',
  `stats_title_2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Tamamlanan',
  `stats_title_3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Kişiye Ulaştık',
  `stats_title_4` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Toplam Proje',
  `custom_stat_1` int DEFAULT NULL,
  `custom_stat_2` int DEFAULT NULL,
  `custom_stat_3` int DEFAULT NULL,
  `custom_stat_4` int DEFAULT NULL,
  `use_custom_stats` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `projects_hero_settings` 
(`hero_title`, `hero_subtitle`, `show_stats`, `stats_title_1`, `stats_title_2`, `stats_title_3`, `stats_title_4`, `is_active`) 
VALUES 
('Projelerimiz', 
 'Toplumun farklı kesimlerine ulaşarak hayırlı işler yapıyor, birlikte daha güzel bir dünya inşa ediyoruz.', 
 1, 
 'Aktif Proje', 
 'Tamamlanan', 
 'Kişiye Ulaştık', 
 'Toplam Proje', 
 1);
