<?php
/**
 * Necat Derneği - İçerik Kataloğu
 * Web sitesi için merkezi içerik yönetimi
 */

// ANA SAYFA İÇİN İÇERİKLER
$homepage_content = [
    'hero_slogan' => 'Elinizi iyiliğe uzatın',
    'hero_description' => 'İslam yardımlaşma dinidir. Kur\'an-ı Kerim\'de "Rabbinin rahmetini onlar mı bölüyorlar? Dünya hayatında insanların geçimlerini aralarında dağıtan biziz. Birini diğerine iş gördürmesi için kimini kiminden zengin kıldık." buyurulmuştur.',
    'mission_preview' => 'İnsanlar birbirlerine muhtaç olarak yaratılmıştır. Bu ihtiyaç yardımlaşmayı zorunlu kılmaktadır. İslamiyet\'in temel taşlarından biri olan yardımlaşma hem maddi hem de manevi boyuttadır.',
    'call_to_action' => 'Birlikte daha güçlüyüz. Gönüllü ekibimize katılın ve muhtaç ailelere umut olun. Her katkı bir hayatı değiştirir.'
];

// HAKKIMIZDA SAYFASI - KURULUŞ İLKELERİ
$founding_principles = [
    'hakki_riayet' => [
        'title' => 'Hakkı Riâyet',
        'description' => 'Her insanın haklarına saygı göstermek ve adaleti gözetmek',
        'icon' => 'fas fa-balance-scale'
    ],
    'emanete_sadakat' => [
        'title' => 'Emanete Sadakat',
        'description' => 'Bize emanet edilen her değeri en iyi şekilde korumak',
        'icon' => 'fas fa-handshake'
    ],
    'ahde_vefa' => [
        'title' => 'Ahde Vefa',
        'description' => 'Verdiğimiz sözlerin arkasında durmak ve güvenilir olmak',
        'icon' => 'fas fa-hand-holding-heart'
    ],
    'insana_hurmet' => [
        'title' => 'İnsana Hürmet',
        'description' => 'Her insana değer vermek ve onurlarını korumak',
        'icon' => 'fas fa-users'
    ],
    'adalet' => [
        'title' => 'Adalet',
        'description' => 'Her durumda adil davranmak ve eşitliği gözetmek',
        'icon' => 'fas fa-gavel'
    ],
    'vicdan' => [
        'title' => 'Vicdan',
        'description' => 'Vicdani değerlere uygun hareket etmek',
        'icon' => 'fas fa-heart'
    ],
    'seffaflik' => [
        'title' => 'Şeffaflık',
        'description' => 'Tüm faaliyetlerimizde açık ve şeffaf olmak',
        'icon' => 'fas fa-eye'
    ],
    'sorumluluk' => [
        'title' => 'Sorumluluk',
        'description' => 'Üstlendiğimiz görevlerde tam sorumluluk almak',
        'icon' => 'fas fa-clipboard-check'
    ],
    'durusttuk' => [
        'title' => 'Dürüstlük',
        'description' => 'Her zaman doğru ve dürüst olmak',
        'icon' => 'fas fa-shield-alt'
    ]
];

// PROJELERİMİZ SAYFASI - FAALİYETLER
$activities = [
    [
        'title' => 'Yetim ve Kimsesiz Aile Yardımları',
        'description' => 'Yetim, yoksul ve kimsesiz ailelere yiyecek, yakacak ve giyecek yardımı',
        'category' => 'aile_yardimi',
        'icon' => 'fas fa-home',
        'color' => 'primary'
    ],
    [
        'title' => 'Eğitim Desteği',
        'description' => 'Okul dönemlerinde kırtasiye yardımı ve yetim ve yoksul öğrencilere burs',
        'category' => 'egitim',
        'icon' => 'fas fa-graduation-cap',
        'color' => 'secondary'
    ],
    [
        'title' => 'Ramazan Organizasyonları',
        'description' => 'Ramazan ayında yoksul ailelere iftar yemeği organizasyonları',
        'category' => 'ramazan',
        'icon' => 'fas fa-moon',
        'color' => 'accent'
    ],
    [
        'title' => 'Evlilik Yardımları',
        'description' => 'Maddi imkansızlıktan evlenemeyen yetim ve yoksullara evlilik yardımları',
        'category' => 'evlilik',
        'icon' => 'fas fa-rings-wedding',
        'color' => 'primary'
    ],
    [
        'title' => 'Sağlık Hizmetleri',
        'description' => 'Adak, akika ve vacip kurban kesimleri ve dağıtımı, hastalara kan temin etmek',
        'category' => 'saglik',
        'icon' => 'fas fa-heartbeat',
        'color' => 'secondary'
    ],
    [
        'title' => 'Sosyal Aktiviteler',
        'description' => 'Yoksul aileler için piknik, sinema ve diğer organizasyonlar, manevi eğitimler',
        'category' => 'sosyal',
        'icon' => 'fas fa-users',
        'color' => 'accent'
    ]
];

// GÖNÜLLÜ OL SAYFASI - MOTİVASYONEL SORULAR
$volunteer_questions = [
    [
        'question' => 'Hiç yeni ayakkabısı olmadığı için, ayakkabı numarasını dahi bilmeyen bir çocuğu, yeni bir ayakkabı ile sevindirmeye var mısınız?',
        'category' => 'cocuk_yardimi',
        'icon' => 'fas fa-child'
    ],
    [
        'question' => 'Hastanede acil kan bekleyen hastalara umut olmaya, damarlarında akan kan olmaya var mısınız?',
        'category' => 'saglik',
        'icon' => 'fas fa-tint'
    ],
    [
        'question' => 'Gittiğiniz her yere hayat üflemeye var mısınız?',
        'category' => 'genel',
        'icon' => 'fas fa-seedling'
    ],
    [
        'question' => 'Kapıları kimsenin açmadığı, dış dünyanın adeta yuttuğu bu zavallı, talihsiz, kimliksiz, aç ve üşüyen, toplumdan dışlanan insanlarla iftar sofralarında buluşmaya var mısınız?',
        'category' => 'sosyal',
        'icon' => 'fas fa-utensils'
    ],
    [
        'question' => 'Koskoca şehirlerin, beldelerin insafsız kucağında çırpınan annelerin acı dramlarını yok etmeye var mısınız?',
        'category' => 'aile',
        'icon' => 'fas fa-female'
    ],
    [
        'question' => 'Çocukları ile yapayalnız kalmış, çaresizlik içinde, hastalıkların pençesinde yardım bekleyen yetimlere kucak açmaya var mısınız?',
        'category' => 'yetim',
        'icon' => 'fas fa-hands-helping'
    ],
    [
        'question' => 'Riyadan uzak, sadece ama sadece Allah rızası için infak seferberliğine var mısınız?',
        'category' => 'manevi',
        'icon' => 'fas fa-hand-holding-heart'
    ],
    [
        'question' => 'Ölüme hicret etmeye var mısınız?',
        'category' => 'manevi',
        'icon' => 'fas fa-route'
    ],
    [
        'question' => 'Cennete giden yola girmeye var mısınız?',
        'category' => 'manevi',
        'icon' => 'fas fa-star'
    ]
];

// SSS SAYFASI İÇİN SORULAR
$faq_questions = [
    [
        'question' => '"Hayırda yarışalım, cennette buluşalım" var mısınız?',
        'answer' => 'Hayır işlerinde birbirimizle yarışarak, güzel amellerde bulunarak ahirette kavuşmayı hedefliyoruz.',
        'category' => 'hayirseverlik',
        'icon' => 'fas fa-trophy'
    ],
    [
        'question' => 'Allah\'ın terazisine koyup tartacak ameller yapmaya var mısınız?',
        'answer' => 'Yapacağımız her amelin ahirette hesaba katılacağının bilinciyle hareket ediyoruz.',
        'category' => 'amel',
        'icon' => 'fas fa-balance-scale'
    ],
    [
        'question' => 'Ölüme hazırlık yapalım var mısınız?',
        'answer' => 'Bu dünya geçici olduğunun farkında olarak ahiret için hazırlık yapıyoruz.',
        'category' => 'ahiret',
        'icon' => 'fas fa-clock'
    ],
    [
        'question' => 'Hesap gününe hazırlanılım var mısınız?',
        'answer' => 'Kıyamet gününde vereceğimiz hesabın bilincinde olarak yaşıyoruz.',
        'category' => 'ahiret',
        'icon' => 'fas fa-calendar-day'
    ],
    [
        'question' => 'Allah\'a kul olalım var mısınız?',
        'answer' => 'Yaratıcımıza karşı kulluk görevimizi en iyi şekilde yerine getirmeye çalışıyoruz.',
        'category' => 'kulluk',
        'icon' => 'fas fa-praying-hands'
    ],
    [
        'question' => 'Cennet kapısı isimlerinden olan "SADAKA KAPISINDAN" girmeye var mısınız?',
        'answer' => 'Sadaka vererek cennetin kapılarından biri olan sadaka kapısından girmeyi umuyoruz.',
        'category' => 'sadaka',
        'icon' => 'fas fa-door-open'
    ]
];

// DEĞERLER VE MESAJLAR
$values_messages = [
    'iyilik' => [
        'title' => 'İyilik',
        'message' => 'İYİLİK, YÜCELMEKTİR, DİRİLMEKTİR, ARINMAKTIR, HUZURDUR, PAHA BİÇİLMEZ BİR GÜZELLİKTİR.',
        'icon' => 'fas fa-heart'
    ],
    'mevlana_quote' => [
        'title' => 'Mevlana\'dan',
        'message' => '"Hiç ölmemek istiyorsan iyilik yap." diyor Mevlana',
        'icon' => 'fas fa-quote-left'
    ],
    'hand_quote' => [
        'title' => 'Davet',
        'message' => '"Elginizi iyilik için uzatmaya var mısınız?"',
        'icon' => 'fas fa-hand-holding-heart'
    ]
];

// GENEL MİSYON METNİ
$mission_text = 'Necat Derneği olarak yapacağımız hizmet çalışmalarında, bütün güzellikler ve doğrular Allah\'a ait; yapılan hatalar Müslürlar şahsımıza ve nefsimize aittir.

HAYIRLI İŞLER KAZANIR';

// ABOUT SAYFASI EK İÇERİKLER
$about_additional = [
    'vision' => 'Toplumun her kesimine ulaşarak, yardımlaşma ve dayanışma kültürünü yaygınlaştırmak',
    'motto' => 'Birlikte güçlüyüz, birlikte değişiyoruz',
    'established' => '2020',
    'location' => 'İstanbul, Türkiye'
];

/**
 * Sayfa içeriğini getirir
 * @param string $page Sayfa adı
 * @return array Sayfa içeriği
 */
function getContentForPage($page) {
    global $homepage_content, $founding_principles, $activities, $volunteer_questions, 
           $faq_questions, $values_messages, $mission_text, $about_additional;
    
    switch($page) {
        case 'home':
            return $homepage_content;
        case 'about':
            return [
                'principles' => $founding_principles, 
                'mission' => $mission_text,
                'additional' => $about_additional
            ];
        case 'projects':
            return $activities;
        case 'volunteer':
            return $volunteer_questions;
        case 'faq':
            return $faq_questions;
        case 'values':
            return $values_messages;
        default:
            return [];
    }
}

/**
 * Kategori rengi getirir
 * @param string $category Kategori adı
 * @return string CSS class
 */
function getCategoryColor($category) {
    $colors = [
        'aile_yardimi' => 'primary',
        'egitim' => 'secondary',
        'ramazan' => 'accent',
        'evlilik' => 'primary',
        'saglik' => 'secondary',
        'sosyal' => 'accent',
        'manevi' => 'primary',
        'hayirseverlik' => 'secondary',
        'amel' => 'accent',
        'ahiret' => 'primary',
        'kulluk' => 'secondary',
        'sadaka' => 'accent'
    ];
    
    return $colors[$category] ?? 'primary';
}

/**
 * Rastgele motivasyon sorusu getirir
 * @return array Soru bilgileri
 */
function getRandomMotivationQuestion() {
    global $volunteer_questions;
    $random_index = array_rand($volunteer_questions);
    return $volunteer_questions[$random_index];
}

/**
 * Kategoriye göre aktiviteleri filtreler
 * @param string $category Kategori adı
 * @return array Filtrelenmiş aktiviteler
 */
function getActivitiesByCategory($category = null) {
    global $activities;
    
    if (!$category) {
        return $activities;
    }
    
    return array_filter($activities, function($activity) use ($category) {
        return $activity['category'] === $category;
    });
}
?>
