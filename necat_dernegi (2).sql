-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2025 at 06:29 PM
-- Server version: 8.0.42-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `necat_dernegi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `full_name`, `created_at`, `updated_at`, `is_active`, `last_login`) VALUES
(1, 'admin', '$2y$10$iEo2s0nTmeV6cE3JLUalbOQpfRpZ/Upl78q99Vkj4XNOtrwRbG88a', 'admin@necatdernegi.org', 'Sistem Yöneticisi', '2025-06-03 16:09:35', '2025-06-03 16:15:21', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'Admin user logged in', 'user', 1, '127.0.0.1', NULL, '2025-06-03 07:00:00'),
(2, 1, 'create', 'Created new project: Yetim Çocuklara Yardım', 'project', 1, '127.0.0.1', NULL, '2025-06-03 07:15:00'),
(3, 1, 'update', 'Updated project status to active', 'project', 1, '127.0.0.1', NULL, '2025-06-03 07:20:00'),
(4, 1, 'create', 'Created news article: Ramazan Kampanyası', 'news', 1, '127.0.0.1', NULL, '2025-06-03 08:00:00'),
(5, 1, 'export', 'Exported donations report', 'donation', NULL, '127.0.0.1', NULL, '2025-06-03 08:30:00'),
(6, 1, 'view', 'Viewed volunteer applications', 'volunteer', NULL, '127.0.0.1', NULL, '2025-06-03 09:00:00'),
(7, 1, 'update', 'Updated system settings', 'setting', NULL, '127.0.0.1', NULL, '2025-06-03 09:15:00'),
(8, 1, 'delete', 'Deleted spam message', 'message', 5, '127.0.0.1', NULL, '2025-06-03 10:00:00'),
(9, 1, 'system', 'Database backup completed', NULL, NULL, '127.0.0.1', NULL, '2025-06-03 11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `admin_reply`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'Kemal Arslan', 'kemal.arslan@email.com', '+90 555 111 2233', 'Bağış Bilgilendirmesi', 'Merhaba, düzenli bağış yapmak istiyorum. Bu konuda bilgi alabilir miyim?', 'new', NULL, NULL, '2025-02-20 06:15:00', '2025-06-10 18:23:26'),
(2, 'Sibel Korkmaz', 'sibel.korkmaz@email.com', '+90 555 222 3344', 'Proje İşbirliği', 'Firmamız olarak sosyal sorumluluk projelerinizde yer almak istiyoruz.', 'read', NULL, NULL, '2025-02-18 11:30:00', '2025-06-10 18:23:26'),
(3, 'Mustafa Güzel', 'mustafa.guzel@email.com', '+90 555 333 4455', 'Gönüllülük Başvurusu', 'Gönüllü başvuru formunu doldurdum, geri dönüş bekliyorum.', 'replied', NULL, NULL, '2025-02-15 13:45:00', '2025-06-10 18:23:26'),
(4, 'Zeynep Arslan', 'zeynep.arslan@email.com', '555-111-2222', 'Proje Önerisi', 'Merhabalar, mahallemizdeki yaşlılar için bir yardım projesi başlatmak istiyorum. Bu konuda nasıl iş birliği yapabiliriz?', 'new', NULL, NULL, '2025-06-08 06:30:00', '2025-06-10 18:42:10'),
(5, 'Ali Vural', 'ali.vural@email.com', '555-333-4444', 'Bağış Bilgisi', 'Kurumsal bağış yapmak istiyoruz. Hangi projelerinize öncelik vermeliyiz ve prosedürler nelerdir?', 'read', NULL, NULL, '2025-06-07 11:15:00', '2025-06-10 18:42:10'),
(6, 'Fatma Öztürk', 'fatma.ozturk@email.com', '555-555-6666', 'Gönüllülük', 'Emekli öğretmenim. Eğitim projelerinizde nasıl rol alabilirim? Hangi bölgelerde ihtiyaç var?', 'replied', NULL, NULL, '2025-06-06 08:45:00', '2025-06-10 18:42:10'),
(7, 'Mehmet Coşkun', 'mehmet.coskun@email.com', '555-777-8888', 'Medya İş Birliği', 'Yerel bir radyo kanalında çalışıyorum. Faaliyetlerinizi duyurmak için iş birliği yapmak isteriz.', 'new', NULL, NULL, '2025-06-09 13:20:00', '2025-06-10 18:42:10'),
(8, 'Zeynep Arslan', 'zeynep.arslan@email.com', '555-111-2222', 'Proje Önerisi', 'Merhabalar, mahallemizdeki yaşlılar için bir yardım projesi başlatmak istiyorum. Bu konuda nasıl iş birliği yapabiliriz?', 'new', NULL, NULL, '2025-06-08 06:30:00', '2025-06-10 18:42:36'),
(9, 'Ali Vural', 'ali.vural@email.com', '555-333-4444', 'Bağış Bilgisi', 'Kurumsal bağış yapmak istiyoruz. Hangi projelerinize öncelik vermeliyiz ve prosedürler nelerdir?', 'read', NULL, NULL, '2025-06-07 11:15:00', '2025-06-10 18:42:36'),
(10, 'Fatma Öztürk', 'fatma.ozturk@email.com', '555-555-6666', 'Gönüllülük', 'Emekli öğretmenim. Eğitim projelerinizde nasıl rol alabilirim? Hangi bölgelerde ihtiyaç var?', 'replied', NULL, NULL, '2025-06-06 08:45:00', '2025-06-10 18:42:36'),
(11, 'Mehmet Coşkun', 'mehmet.coskun@email.com', '555-777-8888', 'Medya İş Birliği', 'Yerel bir radyo kanalında çalışıyorum. Faaliyetlerinizi duyurmak için iş birliği yapmak isteriz.', 'new', NULL, NULL, '2025-06-09 13:20:00', '2025-06-10 18:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int DEFAULT NULL,
  `file_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int DEFAULT NULL,
  `download_count` int NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `description`, `file_path`, `file_size`, `file_type`, `category`, `year`, `download_count`, `is_public`, `sort_order`, `created_at`) VALUES
(1, '2023 Faaliyet Raporu', '2023 yılı faaliyetlerimizin detaylı raporu', 'uploads/documents/faaliyet-raporu-2023.pdf', NULL, NULL, 'Faaliyet Raporu', 2023, 0, 1, 1, '2025-06-03 16:09:35'),
(2, '2023 Mali Rapor', '2023 yılı gelir-gider tablosu ve mali durum raporu', 'uploads/documents/mali-rapor-2023.pdf', NULL, NULL, 'Mali Rapor', 2023, 0, 1, 2, '2025-06-03 16:09:35'),
(3, 'Dernek Tüzüğü', 'Necat Derneği kuruluş tüzüğü', 'uploads/documents/dernek-tuzugu.pdf', NULL, NULL, 'Tüzük', NULL, 0, 1, 3, '2025-06-03 16:09:35'),
(4, '2022 Faaliyet Raporu', '2022 yılı faaliyetlerimizin detaylı raporu', 'uploads/documents/faaliyet-raporu-2022.pdf', NULL, NULL, 'Faaliyet Raporu', 2022, 0, 1, 4, '2025-06-03 16:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int NOT NULL,
  `donor_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `donor_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `donation_type_id` int NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `receipt_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_types`
--

CREATE TABLE `donation_types` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donation_types`
--

INSERT INTO `donation_types` (`id`, `name`, `description`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Genel Bağış', 'Derneğin genel faaliyetleri için bağış', 1, 1, '2025-06-03 16:09:35'),
(2, 'Afet Yardımı', 'Afet durumlarında acil yardım için bağış', 1, 2, '2025-06-03 16:09:35'),
(3, 'Eğitim Desteği', 'Eğitim projelerimiz için bağış', 1, 3, '2025-06-03 16:09:35'),
(4, 'Sağlık Yardımı', 'Sağlık hizmetleri için bağış', 1, 4, '2025-06-03 16:09:35'),
(5, 'Gıda Yardımı', 'Gıda kolisi ve yemek yardımı için bağış', 1, 5, '2025-06-03 16:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Nasıl bağış yapabilirim?', 'Bağış yapmak için \"Bağış Yap\" sayfasını ziyaret edebilir, hesap bilgilerimizi kullanarak bağış yapabilir ve dekontunuzu yükleyebilirsiniz.', 'Bağış', 1, 1, '2025-06-03 16:09:35', '2025-06-03 16:09:35'),
(2, 'Bağışımın takibi nasıl yapılır?', 'Dekont yüklediğinizde size bir referans numarası verilir. Bu numara ile bağışınızın durumunu takip edebilirsiniz.', 'Bağış', 2, 1, '2025-06-03 16:09:35', '2025-06-03 16:09:35'),
(3, 'Gönüllü olmak için nasıl başvururum?', '\"Gönüllü Ol\" sayfasından başvuru formunu doldurarak bizimle iletişime geçebilirsiniz.', 'Gönüllülük', 3, 1, '2025-06-03 16:09:35', '2025-06-03 16:09:35'),
(4, 'Hangi projelerde çalışıyorsunuz?', 'Eğitim, sağlık, afet yardımı ve sosyal sorumluluk alanlarında çeşitli projelerimiz bulunmaktadır. Detaylar için \"Projelerimiz\" sayfasını ziyaret edebilirsiniz.', 'Projeler', 4, 1, '2025-06-03 16:09:35', '2025-06-03 16:09:35'),
(5, 'Faaliyet raporlarınızı nereden görebilirim?', 'Yıllık faaliyet raporlarımızı \"Belgelerimiz\" sayfasından indirebilirsiniz.', 'Genel', 5, 1, '2025-06-03 16:09:35', '2025-06-03 16:09:35'),
(6, 'Derneğinize nasıl bağış yapabilirim?', 'Bağış yapmak için web sitemizden \"Bağış Yap\" bölümüne giderek IBAN numaralarımızı görebilir, banka havalesi veya EFT ile bağış yapabilirsiniz. Bağış yaptıktan sonra dekontunuzu sitemize yükleyebilirsiniz.', 'Bağış', 1, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(7, 'Bağışımın takibini nasıl yapabilirim?', 'Bağış yaptıktan sonra size verilen takip numarası ile bağışınızın durumunu web sitemizden sorgulayabilirsiniz. Ayrıca bağışınız onaylandığında e-posta ile bilgilendirilirsiniz.', 'Bağış', 2, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(8, 'Hangi banka hesaplarınız var?', 'Vakıfbank, Garanti BBVA ve İş Bankası\'nda TL, USD ve EUR cinsinden hesaplarımız bulunmaktadır. Güncel IBAN numaralarını \"Bağış Yap\" sayfasından görebilirsiniz.', 'Bağış', 3, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(9, 'Gönüllü olmak için hangi şartları sağlamalıyım?', '18 yaşını doldurmuş, lise mezunu, sosyal sorumluluk bilinci yüksek, ekip çalışmasına uygun bireyler gönüllü başvurusu yapabilir. Özel uzmanlık gerektiren alanlarda ilgili deneyim aranır.', 'Gönüllülük', 4, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(10, 'Gönüllü başvurum ne zaman değerlendirilir?', 'Gönüllü başvurular genellikle 1-2 hafta içinde değerlendirilir. Başvurunuz uygun bulunursa sizi arayarak görüşme randevusu veririz.', 'Gönüllülük', 5, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(11, 'Hangi alanlarda gönüllü ihtiyacınız var?', 'Eğitim, sağlık, sosyal medya, fotoğrafçılık, çeviri, muhasebe, hukuk, psikoloji, teknik destek gibi birçok alanda gönüllü ihtiyacımız bulunmaktadır.', 'Gönüllülük', 6, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(12, 'Projeleriniz hangi bölgelerde yürütülüyor?', 'Projelerimiz Türkiye genelinde ve uluslararası alanda yürütülmektedir. Özellikle Afrika, Suriye ve Balkan ülkelerinde aktif projelerimiz var.', 'Projeler', 7, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(13, 'Proje güncellemelerini nasıl takip edebilirim?', 'Web sitemizin \"Projelerimiz\" bölümünden aktif projelerimizin güncel durumunu takip edebilir, sosyal medya hesaplarımızdan güncel paylaşımları görebilirsiniz.', 'Projeler', 8, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(14, 'Dernek hakkında daha detaylı bilgi nereden alabilirim?', 'Web sitemizin \"Hakkımızda\" bölümünden misyon, vizyon ve faaliyet alanlarımız hakkında detaylı bilgi alabilirsiniz. Ayrıca faaliyet raporlarımızı \"Belgelerimiz\" bölümünden indirebilirsiniz.', 'Genel', 9, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(15, 'İletişime geçmek için hangi kanalları kullanabilirim?', 'Bize telefon, e-posta, web sitesi iletişim formu ve sosyal medya hesaplarımızdan ulaşabilirsiniz. Acil durumlar için 7/24 iletişim hattımız mevcuttur.', 'İletişim', 10, 1, '2025-06-10 18:22:55', '2025-06-10 18:22:55'),
(16, 'Necat Derneği ne zaman kuruldu?', 'Derneğimiz 2018 yılında, toplumsal dayanışmayı güçlendirmek ve ihtiyaç sahiplerine ulaşmak amacıyla kurulmuştur. İnsan onuru temelinde hareket ederek, sosyal adaleti destekleyen projeler geliştiriyoruz.', 'Genel', 1, 1, '2025-06-10 18:38:00', '2025-06-10 18:38:00'),
(17, 'Hangi alanlarda faaliyet gösteriyorsunuz?', 'Eğitim desteği, sağlık hizmetleri, gıda yardımı, afet yönetimi, sosyal projeler ve kadın-çocuk hakları alanlarında çalışmaktayız. Ayrıca toplumsal farkındalık yaratma çalışmaları da yürütüyoruz.', 'Genel', 2, 1, '2025-06-10 18:38:00', '2025-06-10 18:38:00'),
(18, 'Bağış nasıl yapabilirim?', 'Bağış yapmak için web sitemizden \"Bağış Yap\" bölümünü ziyaret edebilir, hesap bilgilerimize bağış yapıp dekontunuzu yükleyebilirsiniz. Tüm bağışlarınız vergi indirimi kapsamındadır.', 'Bağış', 1, 1, '2025-06-10 18:38:00', '2025-06-10 18:38:00'),
(19, 'Bağışımın nasıl kullanıldığını öğrenebilir miyim?', 'Evet, şeffaflık ilkemiz gereği tüm bağışların nasıl kullanıldığını yıllık raporlarımızda detaylı olarak paylaşıyoruz. Ayrıca büyük bağışçılarımızla düzenli iletişim halindeyiz.', 'Bağış', 2, 1, '2025-06-10 18:38:00', '2025-06-10 18:38:00'),
(20, 'Gönüllü olmak için hangi koşulları sağlamalıyım?', '18 yaş üstü, sosyal sorumluluğa duyarlı ve düzenli katılım sağlayabilecek herkesi gönüllü olarak kabul ediyoruz. Özel uzmanlık alanınız varsa bu da projelerimiz için çok değerlidir.', 'Gönüllülük', 1, 1, '2025-06-10 18:38:00', '2025-06-10 18:38:00'),
(21, 'Gönüllü faaliyetlere ne sıklıkta katılmam gerekiyor?', 'Katılım sıklığı tamamen size kalmıştır. Haftalık, aylık veya özel proje bazında katılım sağlayabilirsiniz. Önemli olan süreklilik ve güvenilir olmaktır.', 'Gönüllülük', 2, 1, '2025-06-10 18:38:00', '2025-06-10 18:38:00'),
(22, 'Necat Derneği ne zaman kuruldu?', 'Derneğimiz 2018 yılında, toplumsal dayanışmayı güçlendirmek ve ihtiyaç sahiplerine ulaşmak amacıyla kurulmuştur. İnsan onuru temelinde hareket ederek, sosyal adaleti destekleyen projeler geliştiriyoruz.', 'Genel', 1, 1, '2025-06-10 18:39:37', '2025-06-10 18:39:37'),
(23, 'Hangi alanlarda faaliyet gösteriyorsunuz?', 'Eğitim desteği, sağlık hizmetleri, gıda yardımı, afet yönetimi, sosyal projeler ve kadın-çocuk hakları alanlarında çalışmaktayız. Ayrıca toplumsal farkındalık yaratma çalışmaları da yürütüyoruz.', 'Genel', 2, 1, '2025-06-10 18:39:37', '2025-06-10 18:39:37'),
(24, 'Bağış nasıl yapabilirim?', 'Bağış yapmak için web sitemizden \"Bağış Yap\" bölümünü ziyaret edebilir, hesap bilgilerimize bağış yapıp dekontunuzu yükleyebilirsiniz. Tüm bağışlarınız vergi indirimi kapsamındadır.', 'Bağış', 1, 1, '2025-06-10 18:39:37', '2025-06-10 18:39:37'),
(25, 'Bağışımın nasıl kullanıldığını öğrenebilir miyim?', 'Evet, şeffaflık ilkemiz gereği tüm bağışların nasıl kullanıldığını yıllık raporlarımızda detaylı olarak paylaşıyoruz. Ayrıca büyük bağışçılarımızla düzenli iletişim halindeyiz.', 'Bağış', 2, 1, '2025-06-10 18:39:37', '2025-06-10 18:39:37'),
(26, 'Gönüllü olmak için hangi koşulları sağlamalıyım?', '18 yaş üstü, sosyal sorumluluğa duyarlı ve düzenli katılım sağlayabilecek herkesi gönüllü olarak kabul ediyoruz. Özel uzmanlık alanınız varsa bu da projelerimiz için çok değerlidir.', 'Gönüllülük', 1, 1, '2025-06-10 18:39:37', '2025-06-10 18:39:37'),
(27, 'Gönüllü faaliyetlere ne sıklıkta katılmam gerekiyor?', 'Katılım sıklığı tamamen size kalmıştır. Haftalık, aylık veya özel proje bazında katılım sağlayabilirsiniz. Önemli olan süreklilik ve güvenilir olmaktır.', 'Gönüllülük', 2, 1, '2025-06-10 18:39:37', '2025-06-10 18:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `iban_accounts`
--

CREATE TABLE `iban_accounts` (
  `id` int NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `swift` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `iban_accounts`
--

INSERT INTO `iban_accounts` (`id`, `currency`, `bank_name`, `account_name`, `account_number`, `iban`, `swift`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'TRY', 'Vakıfbank', 'Necat Derneği', '0015-158007315056349', 'TR45 0001 5001 5800 7315 0563 49', 'TVBATR2A', 1, 1, '2025-06-03 16:09:35'),
(2, 'USD', 'Garanti BBVA', 'Necat Derneği', '0062-123456789012', 'TR32 0006 2000 1230 0006 2978 90', 'TGBATRIS', 1, 2, '2025-06-03 16:09:35'),
(3, 'EUR', 'İş Bankası', 'Necat Derneği', '0064-987654321098', 'TR56 0006 4000 0011 2345 6789 01', 'ISBKTRIS', 1, 3, '2025-06-03 16:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `type` enum('news','press','announcement') COLLATE utf8mb4_unicode_ci DEFAULT 'news',
  `is_featured` tinyint(1) DEFAULT '0',
  `author_id` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `content`, `summary`, `category`, `status`, `type`, `is_featured`, `author_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Ramazan Kampanyası Başladı', 'ramazan-kampanyasi-basladi', 'Ramazan ayı boyunca ihtiyaç sahibi ailelere gıda kolisi dağıtıyoruz. Kampanyamıza destek olmak için bağış yapabilirsiniz.', 'Ramazan ayı boyunca gıda kolisi kampanyası', 'announcement', 'published', 'news', 1, NULL, NULL, '2025-06-10 18:03:01', '2025-06-10 18:03:01'),
(2, 'Eğitim Bursu Duyurusu', 'egitim-bursu-duyurusu', 'Başarılı ancak maddi imkansızlıkları olan öğrenciler için eğitim bursu başvuruları başladı.', 'Eğitim bursu başvuruları', 'announcement', 'published', 'news', 1, NULL, NULL, '2025-06-10 18:03:01', '2025-06-10 18:03:01'),
(3, 'Afet Yardım Kampanyası', 'afet-yardim-kampanyasi', 'Son depremde zarar gören bölgeye acil yardım malzemeleri gönderiliyor.', 'Deprem bölgesine yardım', 'activity', 'published', 'press', 0, NULL, NULL, '2025-06-10 18:03:01', '2025-06-10 18:03:01'),
(7, '2025 Kış Yardım Kampanyası Başladı', '2025-kis-yardim-kampanyasi-basladi', 'Bu kış da muhtaç ailelerimizin yanındayız. 2025 Kış Yardım Kampanyamız kapsamında 1000 aileye gıda kolisi, 500 aileye yakacak yardımı ulaştıracağız. Kampanyamıza destek olmak için bağış yapabilir, gönüllü olabilirsiniz.', 'Kış aylarında 1500 aileye yardım eli uzatıyoruz', 'announcement', 'published', 'news', 1, NULL, NULL, '2025-01-15 07:00:00', '2025-06-10 18:20:05'),
(8, 'Suriye Deprem Yardımları Devam Ediyor', 'suriye-deprem-yardimlari-devam-ediyor', 'Suriye deprem bölgesine gönderdiğimiz yardım malzemeleri ihtiyaç sahiplerine ulaştırılmaya devam ediyor. Bugüne kadar 50 tır yardım malzemesi gönderdik.', '50 tır yardım malzemesi Suriye\'ye ulaştırıldı', 'activity', 'published', 'news', 1, NULL, NULL, '2025-02-10 11:30:00', '2025-06-10 18:20:05'),
(9, 'Eğitim Bursu Başvuruları 1 Mart\'ta Sona Eriyor', 'egitim-bursu-basvurulari-1-martta-sona-eriyor', '2025 yılı eğitim burs programımız için başvurular 1 Mart tarihinde sona erecek. Bu yıl 200 öğrenciye burs vermeyi planlıyoruz.', '200 öğrenciye eğitim bursu verilecek', 'announcement', 'published', 'news', 0, NULL, NULL, '2025-02-20 06:15:00', '2025-06-10 18:20:05'),
(10, 'Afrika Su Kuyusu Projesi Tamamlandı', 'afrika-su-kuyusu-projesi-tamamlandi', 'Afrika Çad bölgesinde açtığımız 25 su kuyusu projesi tamamlandı. Artık 5000 kişi temiz suya erişebiliyor.', '5000 kişi temiz suya kavuştu', 'success', 'published', 'press', 1, NULL, NULL, '2025-03-01 13:20:00', '2025-06-10 18:20:05'),
(11, 'Ramadan Iftar Programı Duyurusu', 'ramadan-iftar-programi-duyurusu', 'Ramazan ayı boyunca her gün 500 kişiye iftar yemeği vereceğiz. İftar programımıza katılmak isteyenler kayıt yaptırabilir.', 'Ramazan\'da günlük 500 kişiye iftar', 'event', 'published', 'news', 0, NULL, NULL, '2025-03-15 08:45:00', '2025-06-10 18:20:05'),
(17, '2025 Faaliyet Raporu Yayınlandı', '2025-faaliyet-raporu-yayinlandi', 'Necat Derneği olarak 2024 yılında gerçekleştirdiğimiz tüm faaliyetleri kapsayan yıllık raporumuz yayınlandı. Bu raporda 15.000 aileye ulaştığımız yardımlar, 500 öğrenciye verdiğimiz burslar ve 25 farklı projemizin detayları yer alıyor. Şeffaflık ilkemiz gereği tüm mali durumumuz ve harcama detayları kamuoyu ile paylaşılmıştır.', '2024 yılı faaliyetlerimizi kapsayan detaylı rapor yayınlandı', 'announcement', 'published', 'news', 1, NULL, NULL, '2025-06-10 18:27:13', '2025-06-10 18:27:13'),
(18, 'Deprem Bölgesine Acil Yardım', 'deprem-bolgesine-acil-yardim', 'Son yaşanan deprem felaketi sonrası, etkilenen bölgelere acil yardım malzemesi ulaştırma çalışmalarımız devam ediyor. İlk etapta 5000 adet battaniye, 10000 adet su ve temel gıda malzemeleri bölgeye ulaştırıldı. Çadır kent kurulumu için gerekli malzemeler de hazırlanmış durumda.', 'Deprem bölgesine acil yardım malzemeleri ulaştırıldı', 'activity', 'published', 'press', 1, NULL, NULL, '2025-06-10 18:27:13', '2025-06-10 18:27:13'),
(19, 'Yeni Eğitim Merkezi Açılışı', 'yeni-egitim-merkezi-acilisi', 'Ankara Çankaya ilçesinde açtığımız yeni eğitim merkezimiz öğrencilere hizmet vermeye başladı. Merkezde 200 öğrencinin eğitim görebileceği modern derslikler, kütüphane ve bilgisayar laboratuvarı bulunuyor. Ücretsiz kurslar ve destekleme programları düzenlenecek.', 'Çankaya ilçesinde yeni eğitim merkezi hizmete başladı', 'event', 'published', 'news', 0, NULL, NULL, '2025-06-10 18:27:13', '2025-06-10 18:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` text COLLATE utf8mb4_unicode_ci,
  `target_amount` decimal(10,2) DEFAULT NULL,
  `collected_amount` decimal(10,2) DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planning','active','completed','paused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `beneficiaries` int DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `slug`, `short_description`, `description`, `image`, `gallery`, `target_amount`, `collected_amount`, `start_date`, `end_date`, `status`, `category`, `location`, `beneficiaries`, `is_featured`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Kış Yardımı Projesi', 'kis-yardimi-projesi', 'Kış aylarında muhtaç ailelere gıda ve giyim yardımı', 'Kış aylarında zorlu koşullarda yaşayan ailelere sıcak giyim ve gıda yardımı ulaştırıyoruz. Bu proje kapsamında 500 aileye ulaşmayı hedefliyoruz.', 'uploads/projects/kis-yardimi.jpg', NULL, '100000.00', '75000.00', '2024-12-01', NULL, 'active', 'Sosyal Yardım', 'İstanbul', 500, 1, 1, '2025-06-03 16:09:35', '2025-06-10 18:26:56'),
(2, 'Eğitim Burs Projesi', 'egitim-burs-projesi', 'Başarılı öğrencilere eğitim bursu desteği', 'Maddi imkansızlıklar nedeniyle eğitimini sürdüremeyen başarılı öğrencilere burs desteği sağlıyoruz.', 'uploads/projects/egitim-bursu.jpg', NULL, '150000.00', '0.00', '2024-09-01', NULL, 'active', 'Eğitim', 'Türkiye Geneli', 100, 1, 2, '2025-06-03 16:09:35', '2025-06-03 16:09:35'),
(3, 'Sağlık Tarama Projesi', 'saglik-tarama-projesi', 'Kırsal bölgelerde ücretsiz sağlık taraması', 'Sağlık hizmetlerine erişimi zor olan kırsal bölgelerde ücretsiz sağlık taraması ve muayene hizmeti veriyoruz.', 'uploads/projects/saglik-tarama.jpg', NULL, '80000.00', '50000.00', '2024-06-01', NULL, 'completed', 'Sağlık', 'Anadolu', 1000, 0, 3, '2025-06-03 16:09:35', '2025-06-10 18:26:56'),
(4, 'Kış Yardımı 2025', 'kis-yardimi-2025', 'Kış aylarında ihtiyaç sahibi ailelere gıda ve giyim yardımı', 'Kış mevsiminde zorlu koşullarda yaşayan ailelerimize sıcak giyim ve gıda yardımı ulaştırıyoruz. Bu proje kapsamında 500 aileye ulaşmayı hedefliyoruz.', NULL, NULL, '100000.00', '75000.00', '2024-12-01', NULL, 'active', 'Sosyal Yardım', 'İstanbul', 500, 1, 1, '2025-06-10 18:08:22', '2025-06-10 18:26:56'),
(5, 'Eğitim Bursu 2025', 'egitim-bursu-2025', 'Başarılı öğrencilere eğitim desteği', 'Maddi imkansızlıklar nedeniyle eğitimini sürdüremeyen başarılı öğrencilere burs desteği sağlıyoruz. Bu yıl 100 öğrenciye burs vermeyi hedefliyoruz.', NULL, NULL, '150000.00', '95000.00', '2024-09-01', NULL, 'active', 'Eğitim', 'Türkiye Geneli', 100, 1, 2, '2025-06-10 18:08:22', '2025-06-10 18:26:56'),
(6, 'Sağlık Taraması', 'saglik-taramasi', 'Kırsal bölgelerde ücretsiz sağlık taraması', 'Sağlık hizmetlerine erişimi zor olan kırsal bölgelerde ücretsiz sağlık taraması ve muayene hizmeti veriyoruz.', NULL, NULL, '50000.00', '50000.00', '2024-06-01', NULL, 'completed', 'Sağlık', 'Anadolu', 1000, 0, 3, '2025-06-10 18:08:22', '2025-06-10 18:08:22'),
(10, '2025 Eğitim Bursu Programı', '2025-egitim-bursu-programi', 'Başarılı ancak maddi imkansızlıkları olan öğrencilere eğitim desteği', 'Üniversite ve lise öğrencilerine eğitim bursu sağlayarak geleceğin aydın bireylerini yetiştirmeyi hedefliyoruz. Bu programla 200 öğrenciye yıllık 5000 TL burs desteği sağlanacak.', NULL, NULL, '1000000.00', '95000.00', '2024-09-01', '2025-06-30', 'active', 'Eğitim', 'Türkiye Geneli', 200, 1, 1, '2025-06-10 18:22:25', '2025-06-10 18:26:56'),
(11, 'Afrika Temiz Su Projesi', 'afrika-temiz-su-projesi', 'Afrika\'da temiz suya erişim sorunu yaşayan bölgelere su kuyusu açma', 'Çad, Mali ve Nijer\'de su kıtlığı yaşayan köylerde su kuyuları açarak binlerce insanın temiz suya kavuşmasını sağlıyoruz. Her kuyu ortalama 200 kişiye hizmet veriyor.', NULL, NULL, '750000.00', '680000.00', '2024-06-01', '2025-12-31', 'active', 'Su Projesi', 'Afrika', 5000, 1, 2, '2025-06-10 18:22:25', '2025-06-10 18:22:25'),
(12, 'Suriye Savaş Mağdurları Yardımı', 'suriye-savas-magurlari-yardimi', 'Suriye\'deki savaş mağduru ailelere acil yardım', 'Suriye iç savaşından etkilenen aileler için gıda, giyim, barınma ve tıbbi malzeme yardımı. Sınır kamplarında ve şehir içindeki ailelere ulaşıyoruz.', NULL, NULL, '2000000.00', '1750000.00', '2023-01-01', NULL, 'active', 'Acil Yardım', 'Suriye', 10000, 1, 3, '2025-06-10 18:22:25', '2025-06-10 18:22:25'),
(13, 'Yaşlı Bakım Evi Projesi', 'yasli-bakim-evi-projesi', 'Kimsesiz yaşlılar için bakım evi kurma projesi', 'İstanbul Kartal\'da kuracağımız yaşlı bakım evinde 100 yaşlı kardeşimize kaliteli bakım hizmeti sunacağız. Modern tıbbi donanım ve deneyimli personelle hizmet verilecek.', NULL, NULL, '5000000.00', '1200000.00', '2024-01-01', '2026-01-01', 'active', 'Sosyal Hizmet', 'İstanbul', 100, 0, 4, '2025-06-10 18:22:25', '2025-06-10 18:22:25'),
(14, 'Yetim Çocuk Eğitim Bursu', 'yetim-cocuk-egitim-bursu', 'Yetim çocukların eğitim hayatına destek', 'Yetim çocuklarımızın eğitim hayatlarını sürdürebilmeleri için burs, kırtasiye ve okul ihtiyaçları desteği sağlıyoruz.', NULL, NULL, '600000.00', '95000.00', '2024-09-01', '2025-06-30', 'active', 'Eğitim', 'Türkiye Geneli', 150, 0, 5, '2025-06-10 18:22:25', '2025-06-10 18:26:56'),
(15, 'Gıda Bankası Projesi', 'gida-bankasi-projesi', 'Muhtaç ailelere düzenli gıda desteği', 'İhtiyaç sahibi ailelere aylık gıda kolisi dağıtımı yaparak temel gıda ihtiyaçlarını karşılıyoruz. Her ay 500 aileye ulaşıyoruz.', NULL, NULL, '300000.00', '280000.00', '2024-01-01', '2024-12-31', 'completed', 'Gıda Yardımı', 'İstanbul', 6000, 0, 6, '2025-06-10 18:22:25', '2025-06-10 18:22:25'),
(16, 'Ramazan Gıda Kolisi', 'ramazan-gida-kolisi', 'Ramazan ayında ihtiyaç sahibi ailelere gıda desteği', 'Ramazan ayının bereketini muhtaç ailelerle paylaşmak için gıda kolisi dağıtımı yapıyoruz. Her kolide bir ailenin bir aylık temel gıda ihtiyaçları bulunmaktadır.', NULL, NULL, '200000.00', '150000.00', '2025-03-01', NULL, 'active', 'Gıda Yardımı', 'İstanbul, Ankara, İzmir', 1500, 1, 4, '2025-06-10 18:26:56', '2025-06-10 18:26:56'),
(17, 'Yetim Çocuk Destekleme', 'yetim-cocuk-destekleme', 'Yetim çocukların eğitim ve barınma ihtiyaçları', 'Yetim çocuklarımızın sağlıklı bir şekilde büyümesi ve eğitim alması için kapsamlı destek programı. Barınma, beslenme, eğitim ve psikolojik destek sağlıyoruz.', NULL, NULL, '300000.00', '180000.00', '2024-01-01', NULL, 'active', 'Çocuk Refahı', 'Türkiye Geneli', 250, 1, 5, '2025-06-10 18:26:56', '2025-06-10 18:26:56'),
(18, 'Engelli Vatandaş Destek', 'engelli-vatandas-destek', 'Engelli vatandaşlarımıza teknik destek ve rehabilitasyon', 'Engelli vatandaşlarımızın günlük yaşamlarını kolaylaştıracak teknik cihazlar ve rehabilitasyon hizmetleri sağlıyoruz.', NULL, NULL, '120000.00', '65000.00', '2024-06-01', NULL, 'active', 'Sağlık', 'Ankara, İzmir', 180, 0, 6, '2025-06-10 18:26:56', '2025-06-10 18:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Necat Derneği', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(2, 'site_description', 'Yardım eli uzatan, umut dağıtan bir toplum için birlikte çalışıyoruz.', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(3, 'contact_email', 'info@necatdernegi.org', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(4, 'contact_phone', '+90 312 123 45 67', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(5, 'contact_address', 'Örnek Mahalle, Örnek Sokak No:1, İstanbul', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(6, 'social_facebook', '#', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(7, 'social_twitter', '#', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(8, 'social_instagram', '#', '2025-06-10 18:05:14', '2025-06-10 18:05:14'),
(9, 'social_youtube', '#', '2025-06-10 18:05:14', '2025-06-10 18:05:14');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_type` enum('text','textarea','number','boolean','image','email') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `updated_at`) VALUES
(1, 'site_title', 'Necat Derneği - Yardım Eli Uzatan Gönüller', 'text', 'genel', 'Site başlığı', '2025-06-10 18:26:33'),
(2, 'site_description', 'Yardım eli uzatan, umut dağıtan bir toplum için birlikte çalışıyoruz. Eğitim, sağlık, afet yardımı ve sosyal sorumluluk alanlarında faaliyet gösteriyoruz.', 'textarea', 'genel', 'Site açıklaması', '2025-06-10 18:26:33'),
(3, 'contact_email', 'info@necatdernegi.org', 'email', 'iletisim', 'İletişim e-posta adresi', '2025-06-03 16:09:36'),
(4, 'contact_phone', '+90 312 333 56 78', 'text', 'iletisim', 'İletişim telefonu', '2025-06-10 18:37:50'),
(5, 'contact_address', 'Kızılay Mahallesi, Atatürk Bulvarı No: 125/7, Çankaya/ANKARA', 'textarea', 'iletisim', 'Adres bilgisi', '2025-06-10 18:26:33'),
(6, 'facebook_url', 'https://facebook.com/necatdernegi', 'text', 'sosyal', 'Facebook sayfası', '2025-06-10 18:32:52'),
(7, 'twitter_url', 'https://twitter.com/necatdernegi', 'text', 'sosyal', 'Twitter hesabı', '2025-06-10 18:32:52'),
(8, 'instagram_url', 'https://instagram.com/necatdernegi', 'text', 'sosyal', 'Instagram hesabı', '2025-06-10 18:32:52'),
(9, 'linkedin_url', '#', 'text', 'sosyal', 'LinkedIn sayfası', '2025-06-03 16:09:36'),
(10, 'mission', 'İnsani değerler temelinde, toplumsal dayanışmayı güçlendirerek ihtiyaç sahibi bireylere ve ailelere ulaşmak, onların yaşam kalitesini artırmak ve toplumsal kalkınmaya katkıda bulunmaktır. Eğitim, sağlık, sosyal yardım ve afet yönetimi alanlarında sürdürülebilir projeler geliştirerek, eşit fırsat ve adalet ilkeleri doğrultusunda hizmet vermektir.', 'textarea', 'genel', 'Dernek misyonu', '2025-06-10 18:28:48'),
(11, 'vision', 'Toplumsal dayanışmanın en üst seviyede yaşandığı, hiçbir bireyin temel ihtiyaçlardan mahrum kalmadığı, eğitim ve sağlık hizmetlerine eşit erişimin sağlandığı adil bir toplum inşa etmektir. Ulusal ve uluslararası düzeyde örnek bir sosyal sorumluluk örgütü olarak, sürdürülebilir kalkınma hedeflerine katkıda bulunmaktır.', 'textarea', 'genel', 'Dernek vizyonu', '2025-06-10 18:28:48'),
(12, 'values', 'Şeffaflık, Hesap Verebilirlik, Eşitlik, Adalet, Dayanışma, Güven, İnsan Onuru, Sürdürülebilirlik, Gönüllülük, Dürüstlük', 'text', 'genel', 'Dernek değerleri', '2025-06-10 18:28:48'),
(13, 'foundation_year', '2018', 'text', 'genel', 'Kuruluş yılı', '2025-06-10 18:28:48'),
(14, 'about_description', 'Necat Derneği, 2018 yılında toplumsal sorunlara çözüm üretmek amacıyla kurulmuş, kar amacı gütmeyen bir sivil toplum kuruluşudur. Derneğimiz, özellikle dezavantajlı grupların yaşam kalitesini artırmayı, eğitim olanaklarını geliştirmeyi ve acil durumlarda hızlı müdahale sağlamayı hedeflemektedir.', 'textarea', 'genel', 'Hakkımızda açıklaması', '2025-06-10 18:28:48'),
(15, 'emergency_phone', '+90 555 911 4567', 'text', 'iletisim', 'Acil durum telefon numarası', '2025-06-10 18:32:52'),
(16, 'contact_fax', '+90 312 444 56 79', 'text', 'iletisim', 'Faks numarası', '2025-06-10 18:32:52'),
(17, 'youtube_url', 'https://youtube.com/@necatdernegi', 'text', 'sosyal', 'YouTube kanalı', '2025-06-10 18:32:52');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_text` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `image`, `title`, `description`, `link`, `link_text`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'assets/images/hero/hero1.jpg', 'Umut Olmaya Devam Ediyoruz', 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.', 'index.php?page=donate', 'Bağış Yap', 1, 1, '2025-06-03 16:09:35'),
(2, 'assets/images/hero/hero2.jpg', 'Eğitim Geleceğin Temelidir', 'Çocukların parlak bir geleceğe sahip olması için eğitim projelerimize destek verin. Her çocuk eğitim hakkına sahiptir.', 'index.php?page=projects', 'Projeleri Gör', 2, 1, '2025-06-03 16:09:35'),
(3, 'assets/images/hero/hero3.jpg', 'Birlikte Daha Güçlüyüz', 'Gönüllü ekibimize katılın ve topluma katkıda bulunun. Birlikte yaratabileceğimiz değişimin bir parçası olun.', 'index.php?page=volunteer', 'Gönüllü Ol', 3, 1, '2025-06-03 16:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('yonetim','danisma','genel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'genel',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `bio`, `image`, `email`, `phone`, `linkedin`, `twitter`, `category`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Dr. Ahmet Yılmaz', 'Yönetim Kurulu Başkanı', 'İktisadi ve İdari Bilimler Fakültesi mezunu. 20 yıllık iş hayatında sosyal sorumluluk projelerinde aktif rol almış, birçok STK yönetiminde görev yapmıştır.', 'uploads/team/ahmet-yilmaz.jpg', NULL, NULL, NULL, NULL, 'yonetim', 1, 1, '2025-06-03 16:09:35'),
(2, 'Fatma Demir', 'Genel Koordinatör', 'Sosyal Hizmet Uzmanı. 15 yıllık deneyimi ile proje yönetimi ve sosyal hizmet alanında uzmanlaşmıştır. Kadın ve çocuk hakları konularında aktivist.', 'uploads/team/fatma-demir.jpg', NULL, NULL, NULL, NULL, 'yonetim', 2, 1, '2025-06-03 16:09:35'),
(3, 'Mehmet Kaya', 'Mali İşler Sorumlusu', 'Serbest Muhasebeci Mali Müşavir (SMMM). 18 yıllık muhasebe ve finans deneyimi ile derneğin mali işlerini şeffaf bir şekilde yönetmektedir.', 'uploads/team/mehmet-kaya.jpg', NULL, NULL, NULL, NULL, 'yonetim', 3, 1, '2025-06-03 16:09:35'),
(4, 'Dr. Mehmet Ali Korkmaz', 'Yönetim Kurulu Başkanı', '25 yıllık iş hayatında edindiği deneyimlerle sosyal sorumluluk projelerinin önderliğini yapıyor. İstanbul Üniversitesi İşletme mezunu.', NULL, 'mehmet.korkmaz@necatdernegi.org', NULL, NULL, NULL, 'yonetim', 1, 1, '2025-06-10 18:23:26'),
(5, 'Ayşe Nur Demir', 'Genel Koordinatör', 'Sosyal hizmet uzmanı olarak 15 yıldır bu alanda çalışıyor. Proje yönetimi ve sahada uygulama konularında uzman.', NULL, 'ayse.demir@necatdernegi.org', NULL, NULL, NULL, 'yonetim', 2, 1, '2025-06-10 18:23:26'),
(6, 'Hasan Yılmaz', 'Mali İşler Müdürü', 'SMMM unvanlı mali işler uzmanı. 20 yıllık muhasebe ve mali danışmanlık deneyimi.', NULL, 'hasan.yilmaz@necatdernegi.org', NULL, NULL, NULL, 'yonetim', 3, 1, '2025-06-10 18:23:26'),
(7, 'Fatma Öztürk', 'Proje Koordinatörü', 'Uluslararası ilişkiler mezunu. Afrika ve Ortadoğu projelerinin koordinasyonunu yapıyor.', NULL, 'fatma.ozturk@necatdernegi.org', NULL, NULL, NULL, 'yonetim', 4, 1, '2025-06-10 18:23:26'),
(8, 'Ali Kemal Şahin', 'Halkla İlişkiler Uzmanı', 'İletişim fakültesi mezunu. Medya ilişkileri ve sosyal medya yönetimi alanında uzman.', NULL, 'ali.sahin@necatdernegi.org', NULL, NULL, NULL, 'genel', 5, 1, '2025-06-10 18:23:26'),
(9, 'Dr. Zeynep Kaya', 'Sağlık Danışmanı', 'Dahiliye uzmanı doktor. Sağlık projelerinin planlama ve uygulama süreçlerinde danışmanlık yapıyor.', NULL, 'zeynep.kaya@necatdernegi.org', NULL, NULL, NULL, 'danisma', 6, 1, '2025-06-10 18:23:26'),
(10, 'Ayşe Özkan', 'Proje Koordinatörü', 'Sosyoloji mezunu. Eğitim ve sosyal projeler konusunda 12 yıllık deneyimi bulunmaktadır. Sahada aktif çalışmalar yürütmektedir.', NULL, NULL, NULL, NULL, NULL, 'yonetim', 4, 1, '2025-06-10 18:27:50'),
(11, 'Hasan Çelik', 'Gönüllü Koordinatörü', 'İletişim Fakültesi mezunu. Gönüllü yönetimi ve organizasyon konularında uzmanlaşmıştır. 200+ gönüllünün koordinasyonunu sağlamaktadır.', NULL, NULL, NULL, NULL, NULL, 'yonetim', 5, 1, '2025-06-10 18:27:50'),
(12, 'Zeynep Arslan', 'Halkla İlişkiler Uzmanı', 'Halkla İlişkiler ve Tanıtım mezunu. Derneğin tanıtım faaliyetleri ve basın ilişkilerini yönetmektedir.', NULL, NULL, NULL, NULL, NULL, 'yonetim', 6, 1, '2025-06-10 18:27:50');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_applications`
--

CREATE TABLE `volunteer_applications` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int DEFAULT NULL,
  `profession` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` text COLLATE utf8mb4_unicode_ci,
  `availability` text COLLATE utf8mb4_unicode_ci,
  `interests` text COLLATE utf8mb4_unicode_ci,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('new','reviewed','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `volunteer_applications`
--

INSERT INTO `volunteer_applications` (`id`, `name`, `email`, `phone`, `age`, `profession`, `experience`, `availability`, `interests`, `message`, `status`, `admin_notes`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'Ahmet Yılmaz', 'ahmet@example.com', '555-123-4567', 28, 'Öğretmen', NULL, NULL, NULL, 'Eğitim projelerinde gönüllü olarak çalışmak istiyorum.', 'approved', NULL, NULL, '2025-06-10 18:09:08', '2025-06-10 18:09:08'),
(2, 'Fatma Kaya', 'fatma@example.com', '555-234-5678', 35, 'Hemşire', NULL, NULL, NULL, 'Sağlık alanında deneyimim var, yardım etmek istiyorum.', 'approved', NULL, NULL, '2025-06-10 18:09:08', '2025-06-10 18:09:08'),
(3, 'Mehmet Demir', 'mehmet@example.com', '555-345-6789', 42, 'Mühendis', NULL, NULL, NULL, 'Sosyal sorumluluk projelerinde yer almak istiyorum.', 'new', NULL, NULL, '2025-06-10 18:09:08', '2025-06-10 18:09:08'),
(4, 'Ahmet Çelik', 'ahmet.celik@email.com', '+90 555 123 4567', 28, 'Öğretmen', 'İlkokul öğretmeni olarak çalışıyorum', 'Hafta sonları ve akşam saatleri', 'Eğitim projeleri', 'Eğitim alanında gönüllü olmak istiyorum', 'approved', NULL, NULL, '2025-01-15 07:30:00', '2025-06-10 18:23:26'),
(5, 'Merve Kaya', 'merve.kaya@email.com', '+90 555 234 5678', 25, 'Hemşire', '3 yıl hastane deneyimi', 'Esnek çalışma saatleri', 'Sağlık projeleri', 'Sağlık alanında deneyimimi paylaşmak istiyorum', 'approved', NULL, NULL, '2025-01-20 11:15:00', '2025-06-10 18:23:26'),
(6, 'Emre Demir', 'emre.demir@email.com', '+90 555 345 6789', 32, 'Mühendis', 'Yazılım geliştirme 8 yıl', 'Hafta sonları', 'Teknoloji projeleri', 'Web sitesi ve teknik konularda yardım edebilirim', 'new', NULL, NULL, '2025-02-01 06:45:00', '2025-06-10 18:23:26'),
(7, 'Selin Özkan', 'selin.ozkan@email.com', '+90 555 456 7890', 30, 'Psikolog', 'Çocuk psikolojisi uzmanı', 'Haftanın 2-3 günü', 'Çocuk ve aile projeleri', 'Travma yaşamış çocuklarla çalışmak istiyorum', 'reviewed', NULL, NULL, '2025-02-10 13:20:00', '2025-06-10 18:23:26'),
(8, 'Burak Yıldız', 'burak.yildiz@email.com', '+90 555 567 8901', 26, 'Fotoğrafçı', 'Freelance fotoğrafçı', 'Esnek', 'Medya ve dokümantasyon', 'Projelerin fotoğraf ve video çekimlerini yapabilirim', 'approved', NULL, NULL, '2025-02-15 08:30:00', '2025-06-10 18:23:26'),
(9, 'Elif Yıldız', 'elif.yildiz@email.com', '555-987-6543', 26, 'Öğretmen', 'İlkokul öğretmeni olarak 5 yıl deneyimim var', 'Hafta sonları', 'Eğitim projeleri, çocuk gelişimi', 'Çocukların eğitimine katkıda bulunmak istiyorum', 'approved', NULL, NULL, '2025-05-15 07:30:00', '2025-06-10 18:40:04'),
(10, 'Mustafa Özdemir', 'mustafa.ozdemir@email.com', '555-456-7890', 34, 'Doktor', 'Pratisyen hekim, 8 yıl deneyim', 'Esnek', 'Sağlık taramaları, acil yardım', 'Sağlık alanında gönüllü hizmet vermek istiyorum', 'approved', NULL, NULL, '2025-05-20 11:15:00', '2025-06-10 18:40:04'),
(11, 'Ayşe Kara', 'ayse.kara@email.com', '555-321-9876', 29, 'Psikolog', 'Klinik psikolog, travma uzmanı', 'Hafta içi akşamları', 'Psikolojik destek, danışmanlık', 'Zorlu durumlarla karşılaşan insanlara destek olmak istiyorum', 'new', NULL, NULL, '2025-06-01 13:45:00', '2025-06-10 18:40:04'),
(12, 'Hakan Şahin', 'hakan.sahin@email.com', '555-654-3210', 41, 'Mimar', 'Yapı denetimi ve proje yönetimi', 'Hafta sonları', 'İnşaat projeleri, teknik destek', 'Altyapı projelerinde teknik destek sağlamak istiyorum', 'reviewed', NULL, NULL, '2025-06-05 08:20:00', '2025-06-10 18:40:04'),
(13, 'Elif Yıldız', 'elif.yildiz@email.com', '555-987-6543', 26, 'Öğretmen', 'İlkokul öğretmeni olarak 5 yıl deneyimim var', 'Hafta sonları', 'Eğitim projeleri, çocuk gelişimi', 'Çocukların eğitimine katkıda bulunmak istiyorum', 'approved', NULL, NULL, '2025-05-15 07:30:00', '2025-06-10 18:41:47'),
(14, 'Mustafa Özdemir', 'mustafa.ozdemir@email.com', '555-456-7890', 34, 'Doktor', 'Pratisyen hekim, 8 yıl deneyim', 'Esnek', 'Sağlık taramaları, acil yardım', 'Sağlık alanında gönüllü hizmet vermek istiyorum', 'approved', NULL, NULL, '2025-05-20 11:15:00', '2025-06-10 18:41:47'),
(15, 'Ayşe Kara', 'ayse.kara@email.com', '555-321-9876', 29, 'Psikolog', 'Klinik psikolog, travma uzmanı', 'Hafta içi akşamları', 'Psikolojik destek, danışmanlık', 'Zorlu durumlarla karşılaşan insanlara destek olmak istiyorum', 'new', NULL, NULL, '2025-06-01 13:45:00', '2025-06-10 18:41:47'),
(16, 'Hakan Şahin', 'hakan.sahin@email.com', '555-654-3210', 41, 'Mimar', 'Yapı denetimi ve proje yönetimi', 'Hafta sonları', 'İnşaat projeleri, teknik destek', 'Altyapı projelerinde teknik destek sağlamak istiyorum', 'reviewed', NULL, NULL, '2025-06-05 08:20:00', '2025-06-10 18:41:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `year` (`year`),
  ADD KEY `is_public` (`is_public`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donation_type_id` (`donation_type_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `donation_types`
--
ALTER TABLE `donation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `iban_accounts`
--
ALTER TABLE `iban_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `status` (`status`),
  ADD KEY `type` (`type`),
  ADD KEY `is_featured` (`is_featured`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `status` (`status`),
  ADD KEY `category` (`category`),
  ADD KEY `is_featured` (`is_featured`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_types`
--
ALTER TABLE `donation_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `iban_accounts`
--
ALTER TABLE `iban_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donation_type_id`) REFERENCES `donation_types` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
