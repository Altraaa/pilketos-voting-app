<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Facades\Log;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithCustomStartCell
{
    protected $users;

    public function __construct()
    {
        // Ambil data user dengan debugging
        try {
            $this->users = User::select('unique_code', 'password')->get();
            
            // Logging untuk debugging
            Log::info('UsersExport: Jumlah user yang diambil: ' . $this->users->count());
            
            if ($this->users->count() === 0) {
                Log::warning('UsersExport: Tidak ada user yang ditemukan di database');
            } else {
                Log::info('UsersExport: Contoh data pertama: ' . json_encode($this->users->first()));
            }
        } catch (\Exception $e) {
            Log::error('UsersExport: Error mengambil data user: ' . $e->getMessage());
            $this->users = collect([]);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Unique Code',
            'Password',
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->unique_code,
            $user->password,
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A5'; // Data dimulai dari baris 5
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Add title (baris 1)
                $sheet->mergeCells('A1:B1');
                $sheet->setCellValue('A1', 'Data Login User');
                
                // Add export date (baris 2)
                $sheet->mergeCells('A2:B2');
                $sheet->setCellValue('A2', 'Diunduh pada: ' . now()->format('d F Y H:i:s'));
                
                // Add data count (baris 3)
                $sheet->mergeCells('A3:B3');
                $sheet->setCellValue('A3', 'Total Data: ' . $this->users->count() . ' User');
                
                // Add security note (baris 4)
                $sheet->mergeCells('A4:B4');
                $sheet->setCellValue('A4', '⚠️ Dokumen ini bersifat rahasia. Jangan bagikan kepada pihak yang tidak berwenang.');
                
                // Style for title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '1F2937'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Style for export date and data count
                $sheet->getStyle('A2:A3')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'color' => ['rgb' => '6B7280'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Style for security note
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'EF4444'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Add border to info section
                $sheet->getStyle('A1:B4')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);
                
                // Style for header row (baris 5)
                $sheet->getStyle('A5:B5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(20);
                $sheet->getColumnDimension('B')->setWidth(20);
                
                // Check if we have data
                if ($this->users->count() > 0) {
                    // Style for all data rows (dimulai dari baris 6)
                    $startRow = 6;
                    $endRow = $startRow + $this->users->count() - 1;
                    
                    $sheet->getStyle('A' . $startRow . ':B' . $endRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => 'E5E7EB'],
                            ],
                        ],
                    ]);
                    
                    // Freeze header row
                    $sheet->freezePane('A6');
                } else {
                    // Show no data message (baris 6)
                    $sheet->mergeCells('A6:B6');
                    $sheet->setCellValue('A6', 'TIDAK ADA DATA USER YANG DITEMUKAN');
                    
                    $sheet->getStyle('A6')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'EF4444'],
                            'size' => 14,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }
            },
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [];
    }
}