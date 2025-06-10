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
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$query = "SELECT n.*, u.username as author_name FROM news n 
          LEFT JOIN users u ON n.author_id = u.id 
          WHERE 1=1";
$params = [];

if ($category) {
    $query .= " AND n.category = ?";
    $params[] = $category;
}

if ($status) {
    $query .= " AND n.status = ?";
    $params[] = $status;
}

if ($date_from) {
    $query .= " AND DATE(n.created_at) >= ?";
    $params[] = $date_from;
}

if ($date_to) {
    $query .= " AND DATE(n.created_at) <= ?";
    $params[] = $date_to;
}

$query .= " ORDER BY n.created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($format === 'excel') {
        // Excel export
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Necat Derneği')
            ->setTitle('Haberler Raporu')
            ->setSubject('Haberler Listesi')
            ->setDescription('Necat Derneği haberler raporu');

        // Header row
        $headers = [
            'A1' => 'ID',
            'B1' => 'Başlık',
            'C1' => 'Kategori',
            'D1' => 'Durum',
            'E1' => 'Öne Çıkan',
            'F1' => 'Yazar',
            'G1' => 'Görüntülenme',
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
        foreach ($news as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['title']);
            $sheet->setCellValue('C' . $row, ucfirst($item['category']));
            $sheet->setCellValue('D' . $row, $item['status'] === 'published' ? 'Yayınlandı' : 'Taslak');
            $sheet->setCellValue('E' . $row, $item['featured'] ? 'Evet' : 'Hayır');
            $sheet->setCellValue('F' . $row, $item['author_name'] ?? 'Bilinmiyor');
            $sheet->setCellValue('G' . $row, $item['views']);
            $sheet->setCellValue('H' . $row, date('d.m.Y H:i', strtotime($item['created_at'])));
            $sheet->setCellValue('I' . $row, date('d.m.Y H:i', strtotime($item['updated_at'])));
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
        $filename = 'haberler_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        
    } else {
        // CSV export
        $filename = 'haberler_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($output, [
            'ID',
            'Başlık',
            'Kategori',
            'Durum',
            'Öne Çıkan',
            'Yazar',
            'Görüntülenme',
            'Oluşturma Tarihi',
            'Güncelleme Tarihi'
        ]);

        // Data
        foreach ($news as $item) {
            fputcsv($output, [
                $item['id'],
                $item['title'],
                ucfirst($item['category']),
                $item['status'] === 'published' ? 'Yayınlandı' : 'Taslak',
                $item['featured'] ? 'Evet' : 'Hayır',
                $item['author_name'] ?? 'Bilinmiyor',
                $item['views'],
                date('d.m.Y H:i', strtotime($item['created_at'])),
                date('d.m.Y H:i', strtotime($item['updated_at']))
            ]);
        }

        fclose($output);
    }

} catch (Exception $e) {
    error_log("Export error: " . $e->getMessage());
    header('Location: pages/news.php?error=export_failed');
    exit();
}
?>
