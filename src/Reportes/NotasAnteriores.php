<?php
namespace App\Reportes;

use Cake\Core\Configure;
use Cezpdf;

class NotasAnteriores
{
    private $estudiante;
    private $programa;
    private $notas;
    private $pdf;

    public function __construct($estudiante, $programa, $notas)
    {
        $this->estudiante = $estudiante;
        $this->programa = $programa;
        $this->notas = $notas;
    }

    public function generate()
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->selectFont('Helvetica.afm');
        $this->pdf->ezSetCmMargins(4, 3, 2, 2);

        $this->_pageHeader();
        $this->pdf->ezSetY(680);

        $this->_datosEstudiante();
        $this->_notas();

        $this->pdf->ezStopPageNumbers(1, 1);

        $reportConfig = $this->_getReportConfig();
        $sCodigo = $this->programa->has('programa') ? $this->programa->programa->codigo : '';
        $filename = 'notas_anteriores_' . $this->estudiante->id . '_' . $sCodigo . '_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $this->pdf->ezOutput());
        $sFileName = $reportConfig['webroot'] . $filename;

        $noData = empty($this->notas);

        return ['sFileName' => $sFileName, 'noData' => $noData];
    }

    private function _datosEstudiante()
    {
        $oPdf = $this->pdf;
        $cedula = $this->estudiante->origen . '-' . $this->estudiante->cedula;
        $programa = $this->programa->has('programa') ? $this->programa->programa->codename : '';

        $oPdf->ezText('<b>' . strtoupper($programa) . '</b>', 10, ['justification' => 'center']);
        $oPdf->ezSetY($oPdf->y - 5);

        $data = [[
            'Cedula' => $cedula,
            'Nombres' => $this->estudiante->nombres,
            'Apellidos' => $this->estudiante->apellidos,
            'Expediente' => $this->estudiante->expediente,
        ]];

        $cols = [
            'Cedula' => ['justification' => 'center', 'width' => 70],
            'Nombres' => ['justification' => 'left', 'width' => 120],
            'Apellidos' => ['justification' => 'left', 'width' => 130],
            'Expediente' => ['justification' => 'center', 'width' => 180],
        ];

        $config = [
            'showHeadings' => 1,
            'fontSize' => 8,
            'showLines' => 1,
            'shaded' => 0,
            'width' => 500,
            'maxWidth' => 500,
            'xOrientation' => 'centre',
            'outerLineThickness' => 0.5,
            'innerLineThickness' => 0.5,
            'cols' => $cols,
        ];

        $oPdf->ezTable($data, null, '', $config);
    }

    private function _notas()
    {
        $oPdf = $this->pdf;

        $allData = [];
        $i = 1;
        foreach ($this->notas as $nota) {
            $allData[] = [
                'No.' => $i++,
                'Codigo' => $nota->has('asignatura') ? $nota->asignatura->codigo : '',
                'Asignatura' => $nota->has('asignatura') ? $nota->asignatura->nombre : '',
                'CR.' => $nota->has('asignatura') ? $nota->asignatura->creditos : '',
                'Periodo' => $nota->has('periodo') ? $nota->periodo->codigo : '',
                'Seccion' => $nota->seccion ?? '',
                'Nota' => $nota->calificacion ?? '',
                'Responsable' => $nota->responsable ?? '',
            ];
        }

        $cols = [
            'No.' => ['justification' => 'center', 'width' => 25],
            'Codigo' => ['justification' => 'left', 'width' => 55],
            'Asignatura' => ['justification' => 'left', 'width' => 155],
            'CR.' => ['justification' => 'center', 'width' => 30],
            'Periodo' => ['justification' => 'center', 'width' => 45],
            'Seccion' => ['justification' => 'center', 'width' => 40],
            'Nota' => ['justification' => 'center', 'width' => 45],
            'Responsable' => ['justification' => 'left', 'width' => 105],
        ];

        $config = [
            'showHeadings' => 1,
            'fontSize' => 7,
            'titleFontSize' => 9,
            'showLines' => 1,
            'shaded' => 0,
            'width' => 500,
            'maxWidth' => 500,
            'xOrientation' => 'centre',
            'outerLineThickness' => 0.5,
            'innerLineThickness' => 0.5,
            'cols' => $cols,
        ];

        $oPdf->ezSetY($oPdf->y - 15);
        $oPdf->ezTable($allData, null, 'NOTAS ANTERIORES', $config);
    }

    private function _pageHeader()
    {
        $sFullname = Configure::read('Universidad.Title1') . " \u{201C}" . Configure::read('Universidad.Title2') . "\u{201D}";
        $oImage = WWW_ROOT . 'img/site/cintillo.png';

        $oHeader = $this->pdf->openObject();
        $this->pdf->saveState();

        $this->pdf->addPngFromFile($oImage, 30, 740, 540, 30);

        $this->pdf->ezSetY(735);
        $this->pdf->ezText("<b>" . $sFullname . "</b>", 12, ['justification' => 'center']);

        $this->pdf->ezSetY(715);
        $yTitle = $this->pdf->ezText("<b>NOTAS ANTERIORES</b>", 12, ['justification' => 'center']);
        $yBottom = min($yTitle, 715) - 5;

        $this->pdf->addText(050, $yBottom, 10, "<b>" . Configure::read('Universidad.Siglas') . "</b>");
        $this->pdf->addText(490, $yBottom, 8, "<b>R.I.F</b> " . Configure::read('Universidad.RIF'));

        $this->pdf->line(30, $yBottom - 5, 580, $yBottom - 5);

        $this->pdf->line(40, 42, 570, 42);

        $this->pdf->addText(40, 50, 8, 'Generado por: ' . (new \Cake\Http\Session())->read('Auth.User.alias') . '    ' . date('d/m/Y h:i A'));

        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($oHeader, 'all');
    }

    private function _getReportConfig()
    {
        $dir = WWW_ROOT . 'files' . DS . 'reportes';
        $webroot = DS . 'files' . DS . 'reportes' . DS;

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        if (!is_dir($dir) || !is_writable($dir)) {
            $dir = TMP . 'reportes';
            $webroot = DS . 'reportes' . DS . 'download' . DS . '?file=';
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
        }

        return ['path' => $dir, 'webroot' => $webroot];
    }
}
