<?php
require_once 'config/database.php';

// Basit veritabanı yönetim arayüzü
$action = $_GET['action'] ?? 'tables';
$table = $_GET['table'] ?? '';

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Yöneticisi - Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-database text-primary"></i> Veritabanı Yöneticisi</h1>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fas fa-home"></i> Ana Sayfaya Dön
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-table"></i> Tablolar</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php
                            try {
                                $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                                foreach ($tables as $tableName) {
                                    $activeClass = ($table === $tableName) ? 'active' : '';
                                    echo "<a href='?action=browse&table={$tableName}' class='list-group-item list-group-item-action {$activeClass}'>";
                                    echo "<i class='fas fa-table me-2'></i>{$tableName}";
                                    echo "</a>";
                                }
                            } catch (Exception $e) {
                                echo "<div class='alert alert-danger m-2'>Hata: " . $e->getMessage() . "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6><i class="fas fa-info-circle"></i> Veritabanı Bilgileri</h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>Sunucu:</strong> <?php echo DB_HOST; ?><br>
                            <strong>Veritabanı:</strong> <?php echo DB_NAME; ?><br>
                            <strong>Kullanıcı:</strong> <?php echo DB_USER; ?><br>
                            <strong>Charset:</strong> <?php echo DB_CHARSET; ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <?php if ($action === 'tables'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-list"></i> Tablo Listesi</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tablo Adı</th>
                                            <th>Kayıt Sayısı</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                                            foreach ($tables as $tableName) {
                                                $count = $pdo->query("SELECT COUNT(*) FROM `{$tableName}`")->fetchColumn();
                                                echo "<tr>";
                                                echo "<td><strong>{$tableName}</strong></td>";
                                                echo "<td><span class='badge bg-primary'>{$count}</span></td>";
                                                echo "<td>";
                                                echo "<a href='?action=browse&table={$tableName}' class='btn btn-sm btn-outline-primary me-1'>";
                                                echo "<i class='fas fa-eye'></i> Gözat";
                                                echo "</a>";
                                                echo "<a href='?action=structure&table={$tableName}' class='btn btn-sm btn-outline-info'>";
                                                echo "<i class='fas fa-cog'></i> Yapı";
                                                echo "</a>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } catch (Exception $e) {
                                            echo "<tr><td colspan='3' class='text-danger'>Hata: " . $e->getMessage() . "</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'browse' && $table): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-table"></i> Tablo: <?php echo htmlspecialchars($table); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT * FROM `{$table}` LIMIT 50");
                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if (!empty($data)) {
                                        echo "<table class='table table-sm table-bordered'>";
                                        echo "<thead class='table-dark'><tr>";
                                        foreach (array_keys($data[0]) as $column) {
                                            echo "<th>" . htmlspecialchars($column) . "</th>";
                                        }
                                        echo "</tr></thead><tbody>";
                                        
                                        foreach ($data as $row) {
                                            echo "<tr>";
                                            foreach ($row as $value) {
                                                $displayValue = (strlen($value) > 50) ? substr($value, 0, 50) . '...' : $value;
                                                echo "<td>" . htmlspecialchars($displayValue) . "</td>";
                                            }
                                            echo "</tr>";
                                        }
                                        echo "</tbody></table>";
                                        
                                        if (count($data) === 50) {
                                            echo "<div class='alert alert-info'>İlk 50 kayıt gösteriliyor.</div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-warning'>Bu tabloda henüz kayıt bulunmuyor.</div>";
                                    }
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger'>Hata: " . $e->getMessage() . "</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'structure' && $table): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-cog"></i> Tablo Yapısı: <?php echo htmlspecialchars($table); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                try {
                                    $stmt = $pdo->query("DESCRIBE `{$table}`");
                                    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    echo "<table class='table table-bordered'>";
                                    echo "<thead class='table-dark'>";
                                    echo "<tr><th>Sütun</th><th>Tür</th><th>Null</th><th>Anahtar</th><th>Varsayılan</th><th>Ekstra</th></tr>";
                                    echo "</thead><tbody>";
                                    
                                    foreach ($structure as $column) {
                                        echo "<tr>";
                                        echo "<td><strong>" . htmlspecialchars($column['Field']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
                                        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
                                        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
                                        echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
                                        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger'>Hata: " . $e->getMessage() . "</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
