<?php
/**
 * Breadcrumb Navigation Component
 * SEO için önemli bir navigasyon bileşeni
 */

// Mevcut sayfayı al
$current_page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'home';

// Sayfa başlıklarını tanımla
$page_titles = [
    'home' => 'Ana Sayfa',
    'about' => 'Hakkımızda',
    'projects' => 'Projelerimiz',
    'donate' => 'Bağış Yap',
    'volunteer' => 'Gönüllü Ol',
    'contact' => 'İletişim',
    'faq' => 'Sıkça Sorulan Sorular',
    'press' => 'Basında Biz',
    'documents' => 'Belgelerimiz',
    'team' => 'Yönetim Kurulu',
    '404' => 'Sayfa Bulunamadı',
    '403' => 'Erişim Engellendi',
    '500' => 'Sunucu Hatası'
];

// Protokol ve domain
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];

// Breadcrumb için microdata
$position = 1;
?>

<nav aria-label="breadcrumb" class="breadcrumb-container" style="display: none;">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <!-- Ana Sayfa -->
        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?php echo $protocol . $domain; ?>/" itemprop="item">
                <span itemprop="name">Ana Sayfa</span>
            </a>
            <meta itemprop="position" content="<?php echo $position++; ?>" />
        </li>
        
        <?php if ($current_page !== 'home'): ?>
        <!-- Mevcut Sayfa -->
        <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php echo $page_titles[$current_page] ?? ucfirst($current_page); ?></span>
            <meta itemprop="position" content="<?php echo $position; ?>" />
        </li>
        <?php endif; ?>
    </ol>
</nav>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Ana Sayfa",
      "item": "<?php echo $protocol . $domain; ?>/"
    }
    <?php if ($current_page !== 'home'): ?>
    ,{
      "@type": "ListItem",
      "position": 2,
      "name": "<?php echo $page_titles[$current_page] ?? ucfirst($current_page); ?>",
      "item": "<?php echo $protocol . $domain; ?>/index.php?page=<?php echo $current_page; ?>"
    }
    <?php endif; ?>
  ]
}
</script>

<style>
.breadcrumb-container {
    /* Görünmez yapmak için display: none; ekledik */
    background: rgba(255,255,255,0.8);
    border-radius: 4px;
    padding: 8px 15px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    padding: 0;
    margin: 0;
    list-style: none;
    background: transparent;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-item + .breadcrumb-item {
    padding-left: 8px;
}

.breadcrumb-item + .breadcrumb-item::before {
    display: inline-block;
    padding-right: 8px;
    color: #6c757d;
    content: "/";
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #6c757d;
}
</style> 