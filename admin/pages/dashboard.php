<?php
// Get statistics from database
try {
    // Total donations
    $stmt = $pdo->query("SELECT COUNT(*) as total, SUM(amount) as sum FROM donations WHERE status = 'confirmed'");
    $donationStats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Total volunteers
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM volunteer_applications WHERE status = 'approved'");
    $volunteerStats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Active projects
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM projects WHERE status = 'active'");
    $projectStats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Pending messages
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contact_messages WHERE status = 'pending'");
    $messageStats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Recent donations
    $stmt = $pdo->query("SELECT * FROM donations WHERE status = 'confirmed' ORDER BY created_at DESC LIMIT 5");
    $recentDonations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent volunteer applications
    $stmt = $pdo->query("SELECT * FROM volunteer_applications ORDER BY created_at DESC LIMIT 5");
    $recentVolunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Veritabanı hatası: " . $e->getMessage();
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
            <div class="text-muted">
                <i class="fas fa-calendar-alt me-2"></i><?php echo date('d.m.Y H:i'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3><?php echo number_format($donationStats['total'] ?? 0); ?></h3>
            <p class="text-muted">Toplam Bağış</p>
            <small class="text-success">
                <i class="fas fa-turkish-lira-sign"></i> 
                <?php echo number_format($donationStats['sum'] ?? 0, 2); ?>
            </small>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h3><?php echo number_format($volunteerStats['total'] ?? 0); ?></h3>
            <p class="text-muted">Aktif Gönüllü</p>
            <small class="text-info">
                <i class="fas fa-arrow-up"></i> Bu ay +12
            </small>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h3><?php echo number_format($projectStats['total'] ?? 0); ?></h3>
            <p class="text-muted">Aktif Proje</p>
            <small class="text-warning">
                <i class="fas fa-clock"></i> 3 yakında bitiyor
            </small>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card danger">
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h3><?php echo number_format($messageStats['total'] ?? 0); ?></h3>
            <p class="text-muted">Bekleyen Mesaj</p>
            <small class="text-danger">
                <i class="fas fa-bell"></i> Hemen cevaplayın
            </small>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Bağış Trendi</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="donationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Proje Durumu</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="projectsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Son Bağışlar</h5>
                <a href="?page=donations" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Bağışçı</th>
                                <th>Miktar</th>
                                <th>Tarih</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentDonations)): ?>
                                <?php foreach ($recentDonations as $donation): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <?php echo strtoupper(substr($donation['donor_name'] ?? 'A', 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($donation['donor_name'] ?? 'Anonim'); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($donation['donor_email'] ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            ₺<?php echo number_format($donation['amount'], 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('d.m.Y', strtotime($donation['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Onaylandı</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                        Henüz bağış bulunmuyor
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Son Gönüllü Başvuruları</h5>
                <a href="?page=volunteers" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Pozisyon</th>
                                <th>Tarih</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentVolunteers)): ?>
                                <?php foreach ($recentVolunteers as $volunteer): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-info text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <?php echo strtoupper(substr($volunteer['full_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($volunteer['full_name']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($volunteer['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($volunteer['position']); ?></td>
                                    <td>
                                        <small><?php echo date('d.m.Y', strtotime($volunteer['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = $volunteer['status'] === 'approved' ? 'success' : 
                                                      ($volunteer['status'] === 'rejected' ? 'danger' : 'warning');
                                        $statusText = $volunteer['status'] === 'approved' ? 'Onaylandı' : 
                                                     ($volunteer['status'] === 'rejected' ? 'Reddedildi' : 'Bekliyor');
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                        Henüz başvuru bulunmuyor
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
