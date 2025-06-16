-- Site ayarlarını güncelle
UPDATE site_settings SET setting_value = 'İnsani değerler temelinde, toplumsal dayanışmayı güçlendirerek ihtiyaç sahibi bireylere ve ailelere ulaşmak, onların yaşam kalitesini artırmak ve toplumsal kalkınmaya katkıda bulunmak. İslam''ın yardımlaşma ruhu ile hareket ederek, Allah rızası gözetilerek hizmet vermek.' WHERE setting_key = 'mission';

UPDATE site_settings SET setting_value = 'Yardımlaşmanın ve iyiliğin hâkim olduğu bir toplum inşa etmek. Hiç kimsenin aç, çıplak ve eğitimsiz kalmamasını sağlamak. İslami değerler temelinde, adalet ve merhamet ile dolu bir dünya için çalışmak.' WHERE setting_key = 'vision';

UPDATE site_settings SET setting_value = 'Necat Derneği, 2018 yılında İslami değerler temelinde kurulmuş, yardımlaşma ve dayanışmayı esas alan bir sivil toplum kuruluşudur. "Elinizi iyiliğe uzatın" mottosu ile hareket eden derneğimiz, Kur''an-ı Kerim''de (Zuhruf 43/32) belirtildiği üzere, insanların birbirlerine muhtaç olarak yaratıldığı gerçeğinden hareketle yardımlaşmayı zorunlu görmektedir.' WHERE setting_key = 'about_description';

-- Kuruluş ilkelerimizi ekle
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES 
('founding_principles', 'Hakk''a Riyayet, Emanete Sadakat, Ahde Vefa, İnsana Hürmet, Adalet, Vicdan, Şeffaflık, Sorumluluk, Dürüstlük', 'text', 'genel', 'Kuruluş ilkelerimiz'),
('service_motto', 'Yüreğimizin Uzanabildiği Her Yerde Vazifeliyiz', 'text', 'genel', 'Hizmet anlayışımız mottosu'),
('charity_philosophy', 'Onların bize değil, bizim onlara ihtiyacımız var. İnsanların en iyisi, onlara fayda sağlayanıdır.', 'text', 'genel', 'Yardım felsefemiz');

-- Faaliyet alanlarımızı güncelle
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES 
('activities_list', '1. Yetim, yoksul ve kimsesiz ailelere yiyecek, yakacak ve giyecek yardımı
2. Okul dönemlerinde kırtasiye yardımı
3. Yetim ve yoksul öğrencilere burs
4. Ramazan ayında yoksul ailelere iftar yemeği organizasyonları
5. Maddi imkansızlıktan evlenemeyen yetim ve yoksullara evlilik yardımları
6. Adak, akika ve vacip kurban kesimleri ve dağıtımı
7. Hastalara kan temin etmek
8. Yoksul aileleri için piknik, sinema ve diğer organizasyonlar
9. Yoksul ailelere yönelik manevi eğitimler ve sohbetler
10. İlaç, tıbbi malzeme ve sağlık yardımları', 'textarea', 'genel', 'Faaliyet alanlarımız listesi'),
('volunteer_call', 'Hayırda yarışalım, cennette buluşalım. Allah''ın terazisine koyup tartacak ameller yapmaya, sadaka kapısından girmeye var mısınız?', 'text', 'genel', 'Gönüllü çağrısı');

-- Özel sözler ve alıntılar
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES 
('islamic_quote_1', 'Komşusu aç iken, tok yatan bizden değildir. - Hz. Muhammed (S.A.V)', 'text', 'quotes', 'İslami alıntı 1'),
('islamic_quote_2', 'Sevdiğiniz şeylerden infak etmedikçe asla iyiliğe eremezsiniz. - Kur''an-ı Kerim', 'text', 'quotes', 'İslami alıntı 2'),
('islamic_quote_3', 'Nerede bir dert varsa, deva oraya gider.', 'text', 'quotes', 'Hizmet anlayışı'),
('mevlana_quote', 'Hiç ölmemek istiyorsan iyilik yap. - Mevlana', 'text', 'quotes', 'Mevlana alıntısı');

-- Call to action metinleri
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES 
('volunteer_questions', 'Hiç yeni ayakkabısı olmadığı için, ayakkabı numarasını dahi bilmeyen bir çocuğu sevindirmeye var mısınız? Hastanede kan bekleyen hastalara umut olmaya var mısınız? Kapılarını kimsenin açmadığı, çaresizlik içindeki ailelere kucak açmaya var mısınız?', 'textarea', 'cta', 'Gönüllü soruları'),
('goodness_definition', 'İYİLİK, YÜCELTMEKTİR, DİRİLTMEKTİR, ARINMAKTIR, HUZURDUR. PAHA BİÇİLMEZ BİR GÜZELLİKTİR.', 'text', 'cta', 'İyilik tanımı');
