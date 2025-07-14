<?php
/**
 * Sayfa İçi Navigasyon Menüsü
 * Google'da alt başlıklar olarak görünmesi için önemli
 */

// Sayfa tipine göre navigasyon öğelerini tanımla
$navigation_items = [];

switch ($current_page) {
    case 'about':
        $navigation_items = [
            'misyon' => 'Misyonumuz',
            'vizyon' => 'Vizyonumuz',
            'tarihce' => 'Tarihçemiz',
            'ekibimiz' => 'Ekibimiz',
            'degerlerimiz' => 'Değerlerimiz'
        ];
        break;
        
    case 'projects':
        $navigation_items = [
            'aktif' => 'Aktif Projeler',
            'tamamlanan' => 'Tamamlanan Projeler',
            'planlanan' => 'Planlanan Projeler',
            'kategoriler' => 'Proje Kategorileri'
        ];
        break;
        
    case 'donate':
        $navigation_items = [
            'online' => 'Online Bağış',
            'banka' => 'Banka Hesapları',
            'ayni' => 'Ayni Bağış',
            'duzenli' => 'Düzenli Bağış',
            'kurumsal' => 'Kurumsal Bağış'
        ];
        break;
        
    case 'volunteer':
        $navigation_items = [
            'basvuru' => 'Başvuru Formu',
            'alanlar' => 'Çalışma Alanları',
            'etkinlikler' => 'Gönüllü Etkinlikleri',
            'hikayeler' => 'Gönüllü Hikayeleri'
        ];
        break;
        
    case 'contact':
        $navigation_items = [
            'iletisim' => 'İletişim Bilgileri',
            'form' => 'İletişim Formu',
            'adres' => 'Adres ve Konum',
            'sosyal' => 'Sosyal Medya'
        ];
        break;
        
    default:
        $navigation_items = [];
        break;
}

// Eğer navigasyon öğeleri varsa göster
if (!empty($navigation_items)):
?>

<div class="page-navigation" style="display: none;">
    <h4 class="page-navigation-title">Hızlı Erişim</h4>
    <ul class="page-navigation-list">
        <?php foreach ($navigation_items as $anchor => $title): ?>
        <li class="page-navigation-item">
            <a href="#<?php echo $anchor; ?>" class="page-navigation-link">
                <?php echo $title; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>
.page-navigation {
    /* Görünmez yapmak için display: none; ekledik */
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.page-navigation-title {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 15px;
    color: var(--gray-800);
    border-bottom: 1px solid var(--gray-200);
    padding-bottom: 10px;
}

.page-navigation-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-navigation-item {
    margin-bottom: 8px;
}

.page-navigation-link {
    display: block;
    padding: 8px 10px;
    color: var(--primary-color);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.page-navigation-link:hover {
    background: rgba(78, 166, 116, 0.1);
    color: var(--primary-dark);
    padding-left: 15px;
}
</style>

<?php endif; ?> 