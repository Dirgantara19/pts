<?php
// File: importexport_helper.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function exportToExcel($data, $columns, $filename)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $style_primary = [
        'font' => ['bold' => true],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'top' => ['borderStyle'  => Border::BORDER_THIN],
            'right' => ['borderStyle'  => Border::BORDER_THIN],
            'bottom' => ['borderStyle'  => Border::BORDER_THIN],
            'left' => ['borderStyle'  => Border::BORDER_THIN]
        ]
    ];
    $style_secondary = [
        'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'top' => ['borderStyle'  => Border::BORDER_THIN],
            'right' => ['borderStyle'  => Border::BORDER_THIN],
            'bottom' => ['borderStyle'  => Border::BORDER_THIN],
            'left' => ['borderStyle'  => Border::BORDER_THIN]
        ]
    ];

    $columnIndex = 'A';
    foreach ($columns as $columnHeader) {
        $sheet->setCellValue($columnIndex . '1', $columnHeader);
        $sheet->getStyle($columnIndex . '1')->applyFromArray($style_primary);
        $sheet->getColumnDimension($columnIndex)->setAutoSize(true);


        $columnIndex++;
    }


    $no = 1;
    $numrow = 2;
    if ($data) {
        foreach ($data as $row) {
            $columnIndex = 'A';
            foreach ($row as $columnData) {
                $sheet->setCellValue($columnIndex . $numrow, $columnData);
                if ($columnIndex === 'A') {
                    $sheet->getStyle($columnIndex . $numrow)->applyFromArray($style_primary);
                } else {
                    $sheet->getStyle($columnIndex . $numrow)->applyFromArray($style_secondary);
                }
                $columnIndex++;
            }
            $no++;
            $numrow++;
        }



        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return [
            'writer' => $writer,
            'filename' => $filename,

        ];
    } else {
        return false;
    }
}
