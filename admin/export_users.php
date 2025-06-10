<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// PHPSpreadsheet için gerekli dosyalar
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Export format
$format = $_GET['format'] ?? 'excel';
$role = $_GET['role'] ?? '';
$status = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($role) {
    $query .= " AND role = ?";
    $params[] = $role;
}

if ($status) {
    $query .= " AND status = ?";
    $params[] = $status;
}

if ($date_from) {
    $query .= " AND DATE(created_at) >= ?";
    $params[] = $date_from;
}

if ($date_to) {
    $query .= " AND DATE(created_at) <= ?";
    $params[] = $date_to;
}

$query .= " ORDER BY created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($format === 'excel') {
        // Excel export
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Necat Derneği')
            ->setTitle('Kullanıcılar Raporu')
            ->setSubject('Kullanıcılar Listesi')
            ->setDescription('Necat Derneği kullanıcılar raporu');

        // Header row
        $headers = [
            'A1' => 'ID',
            'B1' => 'Kullanıcı Adı',
            'C1' => 'E-posta',
            'D1' => 'Ad Soyad',
            'E1' => 'Rol',
            'F1' => 'Durum',
            'G1' => 'Son Giriş',
            'H1' => 'Oluşturma Tarihi',
            'I1' => 'Güncelleme Tarihi'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }

        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '366092']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Data rows
        $row = 2;
        foreach ($users as $user) {
            // Role translations
            $roleMap = [
                'admin' => 'Yönetici',
                'editor' => 'Editör',
                'moderator' => 'Moderatör'
            ];
            
            // Status translations
            $statusMap = [
                'active' => 'Aktif',
                'inactive' => 'Pasif',
                'suspended' => 'Askıya Alınmış'
            ];

            $sheet->setCellValue('A' . $row, $user['id']);
            $sheet->setCellValue('B' . $row, $user['username']);
            $sheet->setCellValue('C' . $row, $user['email']);
            $sheet->setCellValue('D' . $row, $user['full_name']);
            $sheet->setCellValue('E' . $row, $roleMap[$user['role']] ?? ucfirst($user['role']));
            $sheet->setCellValue('F' . $row, $statusMap[$user['status']] ?? ucfirst($user['status']));
            $sheet->setCellValue('G' . $row, $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Hiç');
            $sheet->setCellValue('H' . $row, date('d.m.Y H:i', strtotime($user['created_at'])));
            $sheet->setCellValue('I' . $row, date('d.m.Y H:i', strtotime($user['updated_at'])));
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Data borders
        if ($row > 2) {
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ];
            $sheet->getStyle('A2:I' . ($row - 1))->applyFromArray($dataStyle);
        }

        // Output
        $filename = 'kullanicilar_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        
    } else {
        // CSV export
        $filename = 'kullanicilar_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($output, [
            'ID',
            'Kullanıcı Adı',
            'E-posta',
            'Ad Soyad',
            'Rol',
            'Durum',
            'Son Giriş',
            'Oluşturma Tarihi',
            'Güncelleme Tarihi'
        ]);

        // Data
        foreach ($users as $user) {
            // Role translations
            $roleMap = [
                'admin' => 'Yönetici',
                'editor' => 'Editör',
                'moderator' => 'Moderatör'
            ];
            
            // Status translations
            $statusMap = [
                'active' => 'Aktif',
                'inactive' => 'Pasif',
                'suspended' => 'Askıya Alınmış'
            ];

            fputcsv($output, [
                $user['id'],
                $user['username'],
                $user['email'],
                $user['full_name'],
                $roleMap[$user['role']] ?? ucfirst($user['role']),
                $statusMap[$user['status']] ?? ucfirst($user['status']),
                $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Hiç',
                date('d.m.Y H:i', strtotime($user['created_at'])),
                date('d.m.Y H:i', strtotime($user['updated_at']))
            ]);
        }

        fclose($output);
    }

} catch (Exception $e) {
    error_log("Export error: " . $e->getMessage());
    header('Location: pages/users.php?error=export_failed');
    exit();
}
?>
