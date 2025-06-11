-- Necat Derneği Gerçek Projeler

-- Önce mevcut test verilerini temizle
DELETE FROM projects;

-- Gerçek projeler ekle
INSERT INTO projects (title, description, short_description, target_amount, current_amount, status, is_featured, category, beneficiaries, start_date, end_date, image, created_at) VALUES

-- Aktif Projeler
('Yetim Çocukların Eğitim Desteği', 
'Maddi durumu yetersiz yetim çocukların okul masraflarını karşılayarak eğitim hayatlarını destekliyoruz. Kırtasiye, kitap, okul ücreti ve ulaşım giderlerini kapsayan kapsamlı bir eğitim desteği programı.',
'Yetim çocukların eğitim masraflarını karşılıyoruz',
50000.00, 32500.00, 'active', 1, 'education', 85, '2024-09-01', '2025-06-30', 'project-education.jpg', NOW()),

('Kimsesiz Yaşlılar İçin Sıcak Yuva', 
'Huzurevi ve evde yaşayan kimsesiz yaşlılarımızın temel ihtiyaçlarını karşılıyor, sağlık kontrollerini sağlıyor ve onlara manevi destek veriyoruz. Düzenli ziyaretler ve bakım hizmetleri sunuyoruz.',
'Yaşlılarımıza bakım ve sevgi desteği',
35000.00, 28750.00, 'active', 1, 'elderly_care', 45, '2024-10-01', '2025-03-31', 'project-elderly.jpg', NOW()),

('Kış Aylarında Sıcak Yemek Dağıtımı', 
'Soğuk kış günlerinde sokakta yaşayan insanlara ve muhtaç ailelere sıcak yemek dağıtımı yapıyoruz. Her gün düzenli olarak 3 farklı noktada sıcak çorba ve yemek servisi.',
'Kış günlerinde sıcak yemek desteği',
25000.00, 22300.00, 'active', 1, 'food_aid', 150, '2024-12-01', '2025-03-31', 'project-food.jpg', NOW()),

('Ramazan İftar Sofraları', 
'Ramazan ayında muhtaç aileler ve kimsesiz vatandaşlarımız için iftar sofrası kuruyoruz. Manevi atmosferde birlik ve beraberlik duygusunu yaşatıyoruz.',
'Ramazan ayında iftar organizasyonları',
40000.00, 15200.00, 'active', 0, 'ramadan', 200, '2025-03-01', '2025-04-30', 'project-iftar.jpg', NOW()),

('Evlenecek Gençlere Destek', 
'Maddi imkansızlıklar nedeniyle evlenemeyen genç çiftlere destek oluyoruz. Düğün masrafları, ev eşyası ve temel ihtiyaçlar için yardım sağlıyoruz.',
'Genç çiftlere evlilik desteği',
30000.00, 8500.00, 'active', 0, 'marriage', 25, '2024-11-01', '2025-10-31', 'project-marriage.jpg', NOW()),

-- Tamamlanan Projeler
('2023 Kurban Bağışları ve Dağıtımı', 
'2023 Kurban Bayramında toplanan kurban bağışlarını muhtaç ailelere ulaştırdık. 150 kurban kesimi gerçekleştirilerek binlerce aileye et yardımı yapıldı.',
'Kurban bayramında et yardımı',
45000.00, 47500.00, 'completed', 1, 'qurban', 850, '2023-06-15', '2023-06-30', 'project-qurban.jpg', '2023-06-15 00:00:00'),

('Sel Mağdurlarına Acil Yardım', 
'2023 yılında yaşanan sel felaketinde mağdur olan ailelere acil yardım ulaştırdık. Barınma, gıda ve temizlik malzemeleri ile ilk yardım desteği sağladık.',
'Sel mağdurlarına acil destek',
60000.00, 62300.00, 'completed', 1, 'disaster', 320, '2023-08-10', '2023-10-15', 'project-flood.jpg', '2023-08-10 00:00:00'),

('Okul Öncesi Çocuklar İçin Kırtasiye', 
'2023-2024 eğitim öğretim yılında 200 çocuğa kırtasiye desteği sağladık. Kalem, defter, çanta ve diğer okul malzemeleri dağıtıldı.',
'Çocuklara kırtasiye yardımı',
15000.00, 16750.00, 'completed', 0, 'education', 200, '2023-09-01', '2023-09-30', 'project-stationery.jpg', '2023-09-01 00:00:00'),

('Hasta Nakil Ambulans Hizmeti', 
'Hastaneler arası hasta nakli için ambulans hizmeti sağladık. 6 aylık süre boyunca acil durumlarda ücretsiz ambulans desteği verdik.',
'Hastalara ambulans hizmeti',
25000.00, 25000.00, 'completed', 0, 'health', 180, '2023-04-01', '2023-09-30', 'project-ambulance.jpg', '2023-04-01 00:00:00'),

-- Planlama Aşamasındaki Projeler
('2025 Burs Programı', 
'2025 yılında üniversite öğrencilerine burs desteği sağlayacağımız program. Başarılı ve muhtaç öğrencilere aylık burs ödemesi yapılacak.',
'Üniversite öğrencilerine burs',
80000.00, 5200.00, 'planning', 0, 'education', 40, '2025-02-01', '2026-01-31', 'project-scholarship.jpg', NOW()),

('Yaşlılar İçin Sağlık Tarama', 
'65 yaş üstü vatandaşlarımız için ücretsiz sağlık tarama programı. Kalp, şeker, tansiyon ve genel sağlık kontrolü yapılacak.',
'Yaşlılar için sağlık taraması',
20000.00, 1500.00, 'planning', 0, 'health', 100, '2025-03-15', '2025-06-15', 'project-health.jpg', NOW());
