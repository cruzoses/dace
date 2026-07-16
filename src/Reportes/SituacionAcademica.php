<?php
namespace App\Reportes;

use Cake\Core\Configure;
use Cezpdf;

class SituacionAcademica
{
    private $estudiante;
    private $situaciones;
    private $pdf;

    public function __construct($estudiante, $situaciones)
    {
        $this->estudiante = $estudiante;
        $this->situaciones = $situaciones;
    }

    public function generate()
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->selectFont('Helvetica.afm');
        $this->pdf->ezSetCmMargins(4, 3, 2, 2);

        $this->_pageHeader();

        $y = 680;
        $this->pdf->ezSetY($y);

        $this->_datosEstudiante();

        foreach ($this->situaciones as $item) {
            $this->_programa($item);
        }

        $this->pdf->ezStopPageNumbers(1, 1);

        $reportConfig = $this->_getReportConfig();
        $filename = 'situacion_academica_' . $this->estudiante->id . '_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $this->pdf->ezOutput());
        $sFileName = $reportConfig['webroot'] . $filename;

        $noData = empty($this->situaciones);

        return ['sFileName' => $sFileName, 'noData' => $noData];
    }

    private function _datosEstudiante()
    {
        $this->pdf->ezSetY($this->pdf->y - 10);
        $this->pdf->ezText('<b>DATOS DEL ESTUDIANTE</b>', 10, ['justification' => 'center']);
        $this->pdf->ezSetY($this->pdf->y - 5);

        $cedula = $this->estudiante->origen . '-' . $this->estudiante->cedula;
        $data = [[
            'Cedula' => $cedula,
            'Nombres' => $this->estudiante->nombres,
            'Apellidos' => $this->estudiante->apellidos,
            'Expediente' => $this->estudiante->id,
        ]];

        $cols = [
            'Cedula' => ['justification' => 'center', 'width' => 80],
            'Nombres' => ['justification' => 'left', 'width' => 150],
            'Apellidos' => ['justification' => 'left', 'width' => 170],
            'Expediente' => ['justification' => 'center', 'width' => 100],
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

        $this->pdf->ezTable($data, null, '', $config);
    }

    private function _programa($item)
    {
        $prog = $item['programa'];
        $asignaturas = $item['asignaturas'];
        $mallasPorAsignatura = $item['mallasPorAsignatura'];
        $notaMinimaPrograma = (float)$prog->programa->nota_minima;

        $this->pdf->ezSetY($this->pdf->y - 15);
        $this->pdf->ezText('<b>' . strtoupper($prog->programa->codename) . '</b>', 11, ['justification' => 'center']);
        $this->pdf->ezSetY($this->pdf->y - 5);

        $allData = [];
        $i = 1;
        $totalCreditosPrograma = (int)$prog->programa->creditos;
        $totalCreditosAprobados = 0;
        $totalAsignaturasAprobadas = 0;
        $totalAsignaturas = count($asignaturas);
        $iraNumerador = 0;
        $iraDenominador = 0;

        foreach ($asignaturas as $asig) {
            $creditos = $asig->has('asignatura') ? (int)$asig->asignatura->creditos : 0;
            $aprobada = false;
            if (!empty($asig->calificacion)) {
                $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                if ($esCualitativa) {
                    $aprobada = strtoupper($asig->calificacion) === 'A';
                    $notaISA = strtoupper($asig->calificacion) === 'A' ? 20 : 0;
                } else {
                    $notaMinima = $notaMinimaPrograma;
                    if (isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                        $notaMinima = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                    }
                    $aprobada = (float)$asig->calificacion >= $notaMinima;
                    $notaISA = (float)$asig->calificacion;
                }
                if ($aprobada) {
                    $totalCreditosAprobados += $creditos;
                    $totalAsignaturasAprobadas++;
                }
                if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                    $iraNumerador += (int)$asig->acumulado;
                } else {
                    $notaIRA = $esCualitativa ? $notaISA : (float)$asig->calificacion;
                    $iraNumerador += $notaIRA * $creditos;
                }
                $iraDenominador += $creditos;
            }

            $allData[] = [
                'No.' => $i++,
                'Trayecto' => $asig->has('trayecto') ? $asig->trayecto->codigo : '',
                'Creditos' => $creditos,
                'Asignatura' => $asig->has('asignatura') ? $asig->asignatura->nombre : '',
                'Nota' => !empty($asig->calificacion) ? $asig->calificacion : '',
                'Seccion' => !empty($asig->calificacion) ? $asig->seccion : '',
                'Periodo' => !empty($asig->calificacion) && $asig->has('periodo') ? $asig->periodo->lapso : '',
            ];
        }

        $cols = [
            'No.' => ['justification' => 'center', 'width' => 35],
            'Trayecto' => ['justification' => 'center', 'width' => 65],
            'Creditos' => ['justification' => 'center', 'width' => 55],
            'Asignatura' => ['justification' => 'left', 'width' => 190],
            'Nota' => ['justification' => 'center', 'width' => 45],
            'Seccion' => ['justification' => 'center', 'width' => 55],
            'Periodo' => ['justification' => 'center', 'width' => 55],
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

        $this->pdf->ezTable($allData, null, '', $config);

        $this->_resumenAcademico($totalCreditosPrograma, $totalAsignaturas, $totalCreditosAprobados, $totalAsignaturasAprobadas, $iraNumerador, $iraDenominador);
    }

    private function _resumenAcademico($totalCreditos, $totalAsignaturas, $creditosAprobados, $asignaturasAprobadas, $iraNumerador, $iraDenominador)
    {
        $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 2) : 0;
        $porcentaje = $totalCreditos > 0 ? round(($creditosAprobados / $totalCreditos) * 100, 1) : 0;

        $this->pdf->ezSetY($this->pdf->y - 15);
        $this->pdf->ezText('<b>RESUMEN ACADÉMICO</b>', 10, ['justification' => 'center']);
        $this->pdf->ezSetY($this->pdf->y - 5);

        $data = [[
            'Total Creditos' => $totalCreditos,
            'Total Asignaturas' => $totalAsignaturas,
            'Creditos Aprobados' => $creditosAprobados,
            'Asignaturas Aprobadas' => $asignaturasAprobadas,
            'Indice del Proceso' => $ira,
            'Porcentaje Aprobado' => $porcentaje . '%',
        ]];

        $cols = [
            'Total Creditos' => ['justification' => 'center', 'width' => 80],
            'Total Asignaturas' => ['justification' => 'center', 'width' => 80],
            'Creditos Aprobados' => ['justification' => 'center', 'width' => 80],
            'Asignaturas Aprobadas' => ['justification' => 'center', 'width' => 80],
            'Indice del Proceso' => ['justification' => 'center', 'width' => 80],
            'Porcentaje Aprobado' => ['justification' => 'center', 'width' => 100],
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

        $this->pdf->ezTable($data, null, '', $config);
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
        $yTitle = $this->pdf->ezText("<b>SITUACIÓN ACADÉMICA</b>", 12, ['justification' => 'center']);
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
