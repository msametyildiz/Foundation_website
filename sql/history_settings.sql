-- Tarihçe bölümü için settings tablosuna yeni kayıtlar ekleme

INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `label`, `description`, `sort_order`, `is_required`) VALUES
('history_1995_title', 'Kuruluş', 'text', 'tarihce', '1995 Başlık', 'Kuruluş yılı başlığı', 1, 1),
('history_1995_description', 'Necat Derneği, sosyal yardımlaşma ve toplumsal dayanışma amacıyla kuruldu. İhtiyaç sahiplerine destek olmak misyonuyla yola çıktık.', 'textarea', 'tarihce', '1995 Açıklama', 'Kuruluş yılı açıklaması', 2, 1),

('history_1998_title', 'İlk Projeler', 'text', 'tarihce', '1998 Başlık', '1998 yılı başlığı', 3, 1),
('history_1998_description', 'Eğitim burs programı ve gıda yardımı projelerimizi başlattık. İlk yetim ailelere destek vermeye başladık.', 'textarea', 'tarihce', '1998 Açıklama', '1998 yılı açıklaması', 4, 1),

('history_2005_title', 'Kurumsal Gelişim', 'text', 'tarihce', '2005 Başlık', '2005 yılı başlığı', 5, 1),
('history_2005_description', 'Kurumsallaşma sürecimizi tamamladık. Daha geniş bir organizasyon yapısına kavuştuk ve faaliyet alanlarımızı genişlettik.', 'textarea', 'tarihce', '2005 Açıklama', '2005 yılı açıklaması', 6, 1),

('history_2010_title', 'Teknolojik Dönüşüm', 'text', 'tarihce', '2010 Başlık', '2010 yılı başlığı', 7, 1),
('history_2010_description', 'Dijital platformlarda yer almaya başladık. Online bağış sistemi ve web sitesi ile daha fazla insana ulaşmaya başladık.', 'textarea', 'tarihce', '2010 Açıklama', '2010 yılı açıklaması', 8, 1),

('history_2020_title', 'Pandemi Desteği', 'text', 'tarihce', '2020 Başlık', '2020 yılı başlığı', 9, 1),
('history_2020_description', 'COVID-19 salgını döneminde acil yardım programları hayata geçirdik. Hijyen malzemesi dağıtımı ve gıda desteği sağladık.', 'textarea', 'tarihce', '2020 Açıklama', '2020 yılı açıklaması', 10, 1),

('history_2024_title', 'Büyüme ve Gelişim', 'text', 'tarihce', '2024 Başlık', '2024 yılı başlığı', 11, 1),
('history_2024_description', 'Sağlık, eğitim ve afet yardımı alanlarında projelerimizi genişlettik. Daha fazla gönüllü ve destekçi ile güçlendik.', 'textarea', 'tarihce', '2024 Açıklama', '2024 yılı açıklaması', 12, 1);
