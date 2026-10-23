<?php

/*
 * ---------------------------------------------------------------------------
 * Branded Excel exports.
 *
 * Every export in the admin panel goes through here rather than a bare
 * CSV, so a spreadsheet someone downloads and forwards on still reads as
 * something that came out of this system: the logo, the brand navy and
 * orange, a proper header row, instead of a plain grid of text.
 *
 * Requires phpoffice/phpspreadsheet. Install it once with:
 *     composer require phpoffice/phpspreadsheet
 * ---------------------------------------------------------------------------
 */

namespace App\Services;

use App\Models\Core\Setting;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    private const NAVY   = '161D45';
    private const ORANGE = 'F7AF39';
    private const TINT   = 'FEF7EB';
    private const BORDER = 'D5D6DE';

    /* @param string[] $headers column titles, left to right
       @param iterable $rows each row an array in the same order as $headers */
    public function build(string $title, array $headers, iterable $rows, ?string $subtitle = null): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr(preg_replace('/[^A-Za-z0-9 ]/', '', $title) ?: 'Export', 0, 31));

        $columnCount = count($headers);
        $lastColumn  = Coordinate::stringFromColumnIndex($columnCount);

        // Brand band across the top, logo in the first cell if the file
        // is actually there to embed, company name and report title next
        // to it either way so the sheet still identifies itself without one
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::NAVY);

        $companyName = Setting::get('company_name', 'Duramax Mabati');
        $logoPath    = public_path('images/duramax-mabati-logo-white.png');

        if (is_file($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(26);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(7);
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue('A1', $companyName);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setIndent(2);
        }

        // Title band directly under the logo strip, right aligned so it
        // reads as a caption for the sheet rather than competing with it
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', $title . ($subtitle ? '  ·  ' . $subtitle : '') . '  ·  Generated ' . now()->format('d M Y, H:i'));
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::TINT);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB(self::NAVY);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER)->setIndent(1);

        // Column headers
        $headerRow = 3;
        foreach (array_values($headers) as $i => $label) {
            $column = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue("{$column}{$headerRow}", $label);
        }
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::NAVY);
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);

        // Data, zebra striped for readability the same way the admin
        // panel's own tables are
        $rowNum = $headerRow;
        foreach ($rows as $row) {
            $rowNum++;
            foreach (array_values($row) as $i => $value) {
                $column = Coordinate::stringFromColumnIndex($i + 1);
                $sheet->setCellValue("{$column}{$rowNum}", $value);
            }

            if (($rowNum - $headerRow) % 2 === 0) {
                $sheet->getStyle("A{$rowNum}:{$lastColumn}{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::TINT);
            }
        }

        if ($rowNum > $headerRow) {
            $sheet->getStyle("A{$headerRow}:{$lastColumn}{$rowNum}")
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB(self::BORDER);
        }

        foreach (range(1, $columnCount) as $i) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        $sheet->freezePane('A' . ($headerRow + 1));

        return $spreadsheet;
    }

    public function stream(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, str_ends_with($filename, '.xlsx') ? $filename : $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
