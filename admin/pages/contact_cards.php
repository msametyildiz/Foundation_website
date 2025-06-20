<?php
// Admin yetki kontrolü
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';
require_once '../../includes/functions.php';

$page_title = 'İletişim Kartları Yönetimi';
$action = $_GET['action'] ?? 'list';
$success_message = '';
$error_message = '';

// İşlemler
if ($_POST) {
    try {
        switch ($action) {
            case 'add':
                $title = sanitizeInput($_POST['title']);
                $content = $_POST['content']; // HTML içerebilir
                $icon = sanitizeInput($_POST['icon']);
                $button_text = sanitizeInput($_POST['button_text']);
                $button_url = sanitizeInput($_POST['button_url']);
                $button_type = $_POST['button_type'];
                $color = sanitizeInput($_POST['color']);
                $sort_order = (int)$_POST['sort_order'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO contact_info_cards (title, content, icon, button_text, button_url, button_type, color, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $content, $icon, $button_text, $button_url, $button_type, $color, $sort_order, $is_active]);
                
                $success_message = 'İletişim kartı başarıyla eklendi.';
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $title = sanitizeInput($_POST['title']);
                $content = $_POST['content']; // HTML içerebilir
                $icon = sanitizeInput($_POST['icon']);
                $button_text = sanitizeInput($_POST['button_text']);
                $button_url = sanitizeInput($_POST['button_url']);
                $button_type = $_POST['button_type'];
                $color = sanitizeInput($_POST['color']);
                $sort_order = (int)$_POST['sort_order'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                $stmt = $pdo->prepare("UPDATE contact_info_cards SET title = ?, content = ?, icon = ?, button_text = ?, button_url = ?, button_type = ?, color = ?, sort_order = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$title, $content, $icon, $button_text, $button_url, $button_type, $color, $sort_order, $is_active, $id]);
                
                $success_message = 'İletişim kartı başarıyla güncellendi.';
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                $stmt = $pdo->prepare("DELETE FROM contact_info_cards WHERE id = ?");
                $stmt->execute([$id]);
                
                $success_message = 'İletişim kartı başarıyla silindi.';
                break;
                
            case 'toggle_status':
                $id = (int)$_POST['id'];
                $stmt = $pdo->prepare("UPDATE contact_info_cards SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$id]);
                
                $success_message = 'Kart durumu güncellendi.';
                break;
        }
    } catch (PDOException $e) {
        $error_message = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

// Kartları listele
try {
    $stmt = $pdo->query("SELECT * FROM contact_info_cards ORDER BY sort_order ASC, id ASC");
    $cards = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Kartlar yüklenirken hata oluştu: ' . $e->getMessage();
    $cards = [];
}

// Düzenleme için kart bilgisi al
$edit_card = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM contact_info_cards WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_card = $stmt->fetch();
}

include '../includes/admin_header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-address-card me-2"></i>İletişim Kartları Yönetimi</h1>
            <a href="?page=contact_cards&action=add" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Yeni Kart Ekle
            </a>
        </div>
    </div>
</div>

<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $error_message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
<!-- Kart Ekleme/Düzenleme Formu -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-<?= $action === 'add' ? 'plus' : 'edit' ?> me-2"></i>
                    <?= $action === 'add' ? 'Yeni İletişim Kartı Ekle' : 'İletişim Kartını Düzenle' ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=contact_cards&action=<?= $action ?><?= $edit_card ? '&id=' . $edit_card['id'] : '' ?>">
                    <?php if ($edit_card): ?>
                        <input type="hidden" name="id" value="<?= $edit_card['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Başlık *</label>
                            <input type="text" name="title" class="form-control" 
                                   value="<?= htmlspecialchars($edit_card['title'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">İkon Sınıfı *</label>
                            <input type="text" name="icon" class="form-control" 
                                   value="<?= htmlspecialchars($edit_card['icon'] ?? '') ?>" 
                                   placeholder="fas fa-phone" required>
                            <small class="text-muted">Font Awesome icon sınıfı (örn: fas fa-phone)</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">İçerik *</label>
                            <textarea name="content" class="form-control" rows="3" required><?= htmlspecialchars($edit_card['content'] ?? '') ?></textarea>
                            <small class="text-muted">HTML etiketleri kullanabilirsiniz (&lt;strong&gt;, &lt;br&gt; vb.)</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Buton Metni</label>
                            <input type="text" name="button_text" class="form-control" 
                                   value="<?= htmlspecialchars($edit_card['button_text'] ?? '') ?>" 
                                   placeholder="Ara, Mail Gönder vb.">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Buton URL'si</label>
                            <input type="text" name="button_url" class="form-control" 
                                   value="<?= htmlspecialchars($edit_card['button_url'] ?? '') ?>" 
                                   placeholder="tel:+905321234567">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Buton Tipi</label>
                            <select name="button_type" class="form-select">
                                <option value="link" <?= ($edit_card['button_type'] ?? '') === 'link' ? 'selected' : '' ?>>Normal Link</option>
                                <option value="tel" <?= ($edit_card['button_type'] ?? '') === 'tel' ? 'selected' : '' ?>>Telefon</option>
                                <option value="email" <?= ($edit_card['button_type'] ?? '') === 'email' ? 'selected' : '' ?>>E-posta</option>
                                <option value="external" <?= ($edit_card['button_type'] ?? '') === 'external' ? 'selected' : '' ?>>Harici Link</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Renk</label>
                            <input type="color" name="color" class="form-control form-control-color" 
                                   value="<?= $edit_card['color'] ?? '#4ea674' ?>">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Sıra</label>
                            <input type="number" name="sort_order" class="form-control" 
                                   value="<?= $edit_card['sort_order'] ?? 0 ?>" min="0">
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       <?= ($edit_card['is_active'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            <?= $action === 'add' ? 'Kartı Ekle' : 'Değişiklikleri Kaydet' ?>
                        </button>
                        <a href="?page=contact_cards" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-2"></i>İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Kullanım Rehberi</h5>
            </div>
            <div class="card-body">
                <h6>Buton Tipleri:</h6>
                <ul class="list-unstyled">
                    <li><strong>Telefon:</strong> +905321234567</li>
                    <li><strong>E-posta:</strong> mailto:info@example.com</li>
                    <li><strong>Harici Link:</strong> https://maps.google.com</li>
                    <li><strong>Normal Link:</strong> /sayfa</li>
                </ul>
                
                <h6 class="mt-3">Popüler İkonlar:</h6>
                <ul class="list-unstyled">
                    <li>fas fa-phone - Telefon</li>
                    <li>fas fa-envelope - E-posta</li>
                    <li>fas fa-map-marker-alt - Adres</li>
                    <li>fas fa-clock - Saat</li>
                    <li>fas fa-fax - Faks</li>
                    <li>fab fa-whatsapp - WhatsApp</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Kartlar Listesi -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Mevcut İletişim Kartları</h5>
            </div>
            <div class="card-body">
                <?php if (empty($cards)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-address-card fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Henüz hiç kart eklenmemiş</h5>
                        <p class="text-muted">İlk kartınızı eklemek için yukarıdaki butona tıklayın.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sıra</th>
                                    <th>Başlık</th>
                                    <th>İkon</th>
                                    <th>İçerik</th>
                                    <th>Buton</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cards as $card): ?>
                                    <tr class="<?= $card['is_active'] ? '' : 'table-secondary' ?>">
                                        <td>
                                            <span class="badge bg-primary"><?= $card['sort_order'] ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($card['title']) ?></strong>
                                        </td>
                                        <td>
                                            <i class="<?= htmlspecialchars($card['icon']) ?> fa-lg" 
                                               style="color: <?= htmlspecialchars($card['color']) ?>"></i>
                                        </td>
                                        <td>
                                            <div style="max-width: 200px;">
                                                <?= mb_strlen(strip_tags($card['content'])) > 50 
                                                    ? mb_substr(strip_tags($card['content']), 0, 50) . '...' 
                                                    : strip_tags($card['content']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($card['button_text']): ?>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($card['button_text']) ?><br>
                                                    <code><?= htmlspecialchars($card['button_type']) ?></code>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($card['is_active']): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Pasif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="?page=contact_cards&action=edit&id=<?= $card['id'] ?>" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-<?= $card['is_active'] ? 'warning' : 'success' ?>" 
                                                        onclick="toggleStatus(<?= $card['id'] ?>)">
                                                    <i class="fas fa-<?= $card['is_active'] ? 'eye-slash' : 'eye' ?>"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="deleteCard(<?= $card['id'] ?>, '<?= htmlspecialchars($card['title']) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Hidden forms for AJAX operations -->
<form id="toggleForm" method="POST" style="display: none;">
    <input type="hidden" name="id" id="toggleId">
</form>

<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function toggleStatus(id) {
    if (confirm('Bu kartın durumunu değiştirmek istediğinizden emin misiniz?')) {
        document.getElementById('toggleId').value = id;
        document.getElementById('toggleForm').action = '?page=contact_cards&action=toggle_status';
        document.getElementById('toggleForm').submit();
    }
}

function deleteCard(id, title) {
    if (confirm('"' + title + '" kartını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').action = '?page=contact_cards&action=delete';
        document.getElementById('deleteForm').submit();
    }
}

// Form validation
document.querySelector('form')?.addEventListener('submit', function(e) {
    const title = document.querySelector('input[name="title"]');
    const icon = document.querySelector('input[name="icon"]');
    const content = document.querySelector('textarea[name="content"]');
    
    if (!title?.value.trim() || !icon?.value.trim() || !content?.value.trim()) {
        e.preventDefault();
        alert('Lütfen tüm zorunlu alanları doldurun.');
        return false;
    }
    
    // Icon validation
    if (!icon.value.includes('fa-')) {
        e.preventDefault();
        alert('Lütfen geçerli bir Font Awesome icon sınıfı girin (örn: fas fa-phone)');
        return false;
    }
});
</script>

<?php include '../includes/admin_footer.php'; ?>
