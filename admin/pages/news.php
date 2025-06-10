<?php
// News management page for admin panel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;
    
    try {
        if ($action === 'update_status') {
            $status = $_POST['status'] ?? '';
            $stmt = $pdo->prepare("UPDATE news SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = "Haber durumu güncellendi.";
        }
        
        if ($action === 'toggle_featured') {
            $stmt = $pdo->prepare("UPDATE news SET is_featured = NOT is_featured WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Öne çıkarma durumu güncellendi.";
        }
        
        if ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Haber silindi.";
        }
        
        if ($action === 'add') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $summary = $_POST['summary'] ?? '';
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 'draft';
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            
            $stmt = $pdo->prepare("INSERT INTO news (title, content, summary, category, status, is_featured, author_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $content, $summary, $category, $status, $is_featured, $_SESSION['admin_id'] ?? 1]);
            $success = "Yeni haber eklendi.";
        }
        
        if ($action === 'edit') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $summary = $_POST['summary'] ?? '';
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 'draft';
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            
            $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, summary = ?, category = ?, status = ?, is_featured = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $content, $summary, $category, $status, $is_featured, $id]);
            $success = "Haber güncellendi.";
        }
        
    } catch (PDOException $e) {
        $error = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Get filters
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';
$page_num = max(1, $_GET['page_num'] ?? 1);
$per_page = 15;
$offset = ($page_num - 1) * $per_page;

// Build query
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(title LIKE ? OR content LIKE ? OR summary LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if (!empty($status)) {
    $where_conditions[] = "status = ?";
    $params[] = $status;
}

if (!empty($category)) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) FROM news {$where_clause}";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// Get news
$sql = "SELECT n.*, CONCAT(a.first_name, ' ', a.last_name) as author_name 
        FROM news n 
        LEFT JOIN admin_users a ON n.author_id = a.id 
        {$where_clause} 
        ORDER BY n.created_at DESC 
        LIMIT {$per_page} OFFSET {$offset}";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get news statistics
$stats_sql = "SELECT 
    COUNT(*) as total_news,
    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_count,
    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_count,
    SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_count,
    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_count
    FROM news";
$stmt = $pdo->query($stats_sql);
$news_stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-newspaper me-2"></i>Haber Yönetimi</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                <i class="fas fa-plus me-2"></i>Yeni Haber Ekle
            </button>
        </div>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <h3><?php echo number_format($news_stats['total_news'] ?? 0); ?></h3>
            <p class="text-muted">Toplam Haber</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-eye"></i>
            </div>
            <h3><?php echo number_format($news_stats['published_count'] ?? 0); ?></h3>
            <p class="text-muted">Yayında</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
            <h3><?php echo number_format($news_stats['draft_count'] ?? 0); ?></h3>
            <p class="text-muted">Taslak</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
            <h3><?php echo number_format($news_stats['featured_count'] ?? 0); ?></h3>
            <p class="text-muted">Öne Çıkan</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="news">
            
            <div class="col-md-4">
                <label class="form-label">Arama</label>
                <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Haber ara...">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Durum</label>
                <select name="status" class="form-select">
                    <option value="">Tümü</option>
                    <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Taslak</option>
                    <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Yayında</option>
                    <option value="archived" <?php echo $status === 'archived' ? 'selected' : ''; ?>>Arşiv</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Tümü</option>
                    <option value="announcement" <?php echo $category === 'announcement' ? 'selected' : ''; ?>>Duyuru</option>
                    <option value="activity" <?php echo $category === 'activity' ? 'selected' : ''; ?>>Faaliyet</option>
                    <option value="event" <?php echo $category === 'event' ? 'selected' : ''; ?>>Etkinlik</option>
                    <option value="press" <?php echo $category === 'press' ? 'selected' : ''; ?>>Basın</option>
                    <option value="success" <?php echo $category === 'success' ? 'selected' : ''; ?>>Başarı</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <a href="?page=news" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Temizle
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- News Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Haberler 
            <span class="badge bg-secondary"><?php echo $total_records; ?></span>
        </h5>
        <div class="d-flex gap-2">
            <button class="btn btn-success btn-sm" onclick="exportData('excel')">
                <i class="fas fa-file-excel me-1"></i>Excel
            </button>
            <button class="btn btn-info btn-sm" onclick="exportData('pdf')">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </button>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Haber</th>
                        <th>Kategori</th>
                        <th>Yazar</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($news)): ?>
                        <?php foreach ($news as $article): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="news-thumb me-3 position-relative">
                                            <?php if ($article['is_featured']): ?>
                                                <span class="badge bg-warning position-absolute" style="top: -5px; right: -5px; z-index: 10;">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            <?php endif; ?>
                                            <img src="<?php echo !empty($article['image']) ? '../uploads/news/' . $article['image'] : '../assets/images/default-news.jpg'; ?>" 
                                                 alt="<?php echo htmlspecialchars($article['title']); ?>" 
                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($article['title']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($article['summary'] ?: $article['content'], 0, 80)); ?>...</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $categories = [
                                        'announcement' => 'Duyuru',
                                        'activity' => 'Faaliyet',
                                        'event' => 'Etkinlik',
                                        'press' => 'Basın',
                                        'success' => 'Başarı'
                                    ];
                                    echo $categories[$article['category']] ?? $article['category'];
                                    ?>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($article['author_name'] ?? 'Bilinmeyen'); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'draft' => 'warning',
                                        'published' => 'success',
                                        'archived' => 'secondary'
                                    ];
                                    $statusText = [
                                        'draft' => 'Taslak',
                                        'published' => 'Yayında',
                                        'archived' => 'Arşiv'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass[$article['status']] ?? 'secondary'; ?>">
                                        <?php echo $statusText[$article['status']] ?? $article['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm" 
                                                onclick="editNews(<?php echo $article['id']; ?>)" 
                                                title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                            <button type="submit" name="action" value="toggle_featured" 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    title="<?php echo $article['is_featured'] ? 'Öne Çıkarmayı Kaldır' : 'Öne Çıkar'; ?>">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="Durum">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                                        <input type="hidden" name="status" value="draft">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-edit text-warning me-2"></i>Taslak
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                                        <input type="hidden" name="status" value="published">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-eye text-success me-2"></i>Yayınla
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                                        <input type="hidden" name="status" value="archived">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-archive text-secondary me-2"></i>Arşivle
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                            <button type="submit" name="action" value="delete" 
                                                    class="btn btn-outline-danger btn-sm btn-delete" 
                                                    title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Henüz haber bulunmuyor
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="card-footer">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php if ($page_num > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=news&page_num=<?php echo $page_num - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&category=<?php echo urlencode($category); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page_num - 2); $i <= min($total_pages, $page_num + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=news&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&category=<?php echo urlencode($category); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page_num < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=news&page_num=<?php echo $page_num + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&category=<?php echo urlencode($category); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Haber Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Haber Başlığı</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="">Seçiniz</option>
                                <option value="announcement">Duyuru</option>
                                <option value="activity">Faaliyet</option>
                                <option value="event">Etkinlik</option>
                                <option value="press">Basın</option>
                                <option value="success">Başarı</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select" required>
                                <option value="draft">Taslak</option>
                                <option value="published">Yayınla</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Özet</label>
                            <textarea name="summary" class="form-control" rows="3" placeholder="Haberin kısa özeti..."></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">İçerik</label>
                            <textarea name="content" class="form-control" rows="8" required placeholder="Haber içeriği..."></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="addIsFeatured">
                                <label class="form-check-label" for="addIsFeatured">
                                    Öne çıkar
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Haber Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editNewsForm">
                <input type="hidden" name="id" id="editNewsId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Haber Başlığı</label>
                            <input type="text" name="title" id="editNewsTitle" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category" id="editNewsCategory" class="form-select" required>
                                <option value="">Seçiniz</option>
                                <option value="announcement">Duyuru</option>
                                <option value="activity">Faaliyet</option>
                                <option value="event">Etkinlik</option>
                                <option value="press">Basın</option>
                                <option value="success">Başarı</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Durum</label>
                            <select name="status" id="editNewsStatus" class="form-select" required>
                                <option value="draft">Taslak</option>
                                <option value="published">Yayında</option>
                                <option value="archived">Arşiv</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Özet</label>
                            <textarea name="summary" id="editNewsSummary" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">İçerik</label>
                            <textarea name="content" id="editNewsContent" class="form-control" rows="8" required></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="editIsFeatured">
                                <label class="form-check-label" for="editIsFeatured">
                                    Öne çıkar
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="action" value="edit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editNews(id) {
    // AJAX call to get news details
    fetch(`../ajax/get_news.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editNewsId').value = data.news.id;
                document.getElementById('editNewsTitle').value = data.news.title;
                document.getElementById('editNewsCategory').value = data.news.category;
                document.getElementById('editNewsStatus').value = data.news.status;
                document.getElementById('editNewsSummary').value = data.news.summary || '';
                document.getElementById('editNewsContent').value = data.news.content;
                document.getElementById('editIsFeatured').checked = data.news.is_featured == 1;
                
                new bootstrap.Modal(document.getElementById('editNewsModal')).show();
            } else {
                alert('Haber bilgileri yüklenirken hata oluştu.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu.');
        });
}

function exportData(format) {
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.set('export', format);
    window.open(`export_news.php?${currentParams.toString()}`, '_blank');
}
</script>
