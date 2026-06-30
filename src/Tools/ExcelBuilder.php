<?php
namespace App\Tools;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExcelBuilder
{
    private $columns = [];
    private $fileName = 'reporte';

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function generateExcel($data, $title = 'REPORTE')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(mb_substr($title, 0, 31));

        $colIndex = 1;
        foreach ($this->columns as $header => $config) {
            $sheet->setCellValueByColumnAndRow($colIndex, 1, $header);
            $sheet->getStyleByColumnAndRow($colIndex, 1)->getFont()->setBold(true);
            $sheet->getStyleByColumnAndRow($colIndex, 1)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyleByColumnAndRow($colIndex, 1)
                ->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $colIndex++;
        }

        $rowIndex = 2;
        foreach ($data as $row) {
            $colIndex = 1;
            foreach ($this->columns as $header => $config) {
                $value = $row[$header] ?? '';
                $cell = $sheet->getCellByColumnAndRow($colIndex, $rowIndex);
                $cell->setValue($value);

                if (isset($config['justification'])) {
                    $sheet->getStyleByColumnAndRow($colIndex, $rowIndex)->getAlignment()
                        ->setHorizontal(
                            $config['justification'] === 'center'
                                ? Alignment::HORIZONTAL_CENTER
                                : ($config['justification'] === 'right'
                                    ? Alignment::HORIZONTAL_RIGHT
                                    : Alignment::HORIZONTAL_LEFT)
                        );
                }

                $colIndex++;
            }
            $rowIndex++;
        }

        foreach (array_keys($this->columns) as $i => $header) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    public function generateCsv($data, $title = 'REPORTE')
    {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, array_keys($this->columns), ';');

        foreach ($data as $row) {
            $line = [];
            foreach ($this->columns as $header => $config) {
                $line[] = $row[$header] ?? '';
            }
            fputcsv($handle, $line, ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    public function getFileName()
    {
        return $this->fileName;
    }
}
