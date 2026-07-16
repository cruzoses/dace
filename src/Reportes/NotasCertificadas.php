<?php
namespace App\Reportes;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cezpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class NotasCertificadas
{
    private $estudiante;
    private $programa;
    private $asignaturas;
    private $mallasPorAsignatura;
    private $resultado;
    private $pdf;
    private $qrFile;

    public function __construct($estudiante, $programa, $asignaturas, $mallasPorAsignatura, $resultado)
    {
        $this->estudiante = $estudiante;
        $this->programa = $programa;
        $this->asignaturas = $asignaturas;
        $this->mallasPorAsignatura = $mallasPorAsignatura;
        $this->resultado = $resultado;
        $this->_generateQR();
    }

    private function _generateQR()
    {
        $cedula = $this->estudiante->cedula;
        $this->qrFile = WWW_ROOT . 'files' . DS . 'qr' . DS . $cedula . '.png';

        if (!is_dir(WWW_ROOT . 'files' . DS . 'qr')) {
            @mkdir(WWW_ROOT . 'files' . DS . 'qr', 0775, true);
        }

        if (!file_exists($this->qrFile)) {
            $qrCode = new QrCode($cedula);
            $qrCode->setEncoding(new Encoding('UTF-8'));
            $qrCode->setSize(150);
            $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $result->saveToFile($this->qrFile);
        }
    }

    public function generate()
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->selectFont('Helvetica.afm');
        $this->pdf->ezSetCmMargins(3.6, 2.5, 3, 3);

        $this->_pageHeader('CONSTANCIA DE CERTIFICACION DE NOTAS');
        $this->pdf->ezStartPageNumbers(540, 50, 6, 'right', '', 1);

        $this->_certificacion();
        $this->pdf->ezNewPage();
        $this->_muestranotas();
        $this->_indiceAcademico();
        $this->pdf->ezNewPage();
        $this->_condiciones();

        $this->pdf->ezStopPageNumbers(1, 1);

        $reportConfig = $this->_getReportConfig();
        $filename = 'certificacion_' . $this->estudiante->cedula . '_' . $this->programa->programa->codigo . '_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $this->pdf->ezOutput());
        $sFileName = $reportConfig['webroot'] . $filename;

        return ['sFileName' => $sFileName, 'noData' => false];
    }

    private function _certificacion()
    {
        $oPdf = $this->pdf;
        $cedula = $this->estudiante->origen . '-' . $this->estudiante->cedula;
        $nombre = $this->estudiante->apellidos . ', ' . $this->estudiante->nombres;

        $firmasTable = TableRegistry::getTableLocator()->get('Firmas');
        $firmaRector = $firmasTable->find()->where(['Firmas.codigo' => 'RECTOR'])->first();

        $sDatos = $firmaRector ? $firmaRector->datos : '';
        $sFirma = $firmaRector ? $firmaRector->texto : '';
        $sCarrera = $this->programa->programa->credencial ?? $this->programa->programa->codename;

        $titulo = 'CONSTANCIA DE CERTIFICACION DE NOTAS';
        $nWidth = $oPdf->getTextWidth(12, $titulo);
        $nPos = 306 - ($nWidth / 2);
        $oPdf->addText($nPos, 640, 12, "<b>$titulo</b>");

        if (file_exists($this->qrFile)) {
            $oPdf->addPngFromFile($this->qrFile, 30, 670, 50, 50);
            $oPdf->addPngFromFile($this->qrFile, 500, 670, 50, 50);
        }

        $oPdf->ezSetDy(-90);
        $sText = "         Quien suscribe, $sDatos certifica que el (la) ciudadano (a) ";
        $sText .= "<b>$nombre</b>, titular de la cédula de identidad N.º <b>$cedula</b>, cuyo registro ";
        $sText .= "académico consta que obtuvo las calificaciones anexas en el presente documento ";

        $sText .= "en la carrera de: \n";
        $sText .= "<b>$sCarrera</b>.";

        $nWidthArea = $oPdf->ez['pageWidth'] - $oPdf->ez['rightMargin'] - $oPdf->ez['leftMargin'];
        $aOptions = [
            'width' => $nWidthArea,
            'fontSize' => 10,
            'spacing' => 1.5,
            'justification' => 'full',
        ];
        $oPdf->ezText($sText, 10, $aOptions);

        $sLugar = $firmasTable->find()->where(['Firmas.codigo' => 'RECTOR'])->first()->lugar ?? 'Higuerote';
        $sText = "Constancia que se expide a petición de la parte interesada en <b>$sLugar el ";
        $sText .= date('d') . " de " . $this->_mesEnEspanol(date('m')) . " de " . date('Y') . "</b> ";

        $aOptionsDate = [
            'justification' => 'full',
            'width' => $nWidthArea,
            'fontSize' => 10,
            'spacing' => 1.5,
        ];
        $oPdf->ezSetDy(-30);
        $oPdf->ezText($sText, 10, $aOptionsDate);

        $oPdf->ezSetDy(-50);
        $oPdf->ezText('Atentamente,', 10, ['justification' => 'center']);
        $oPdf->ezSetDy(-30);
        $oPdf->ezText($sFirma, 10, ['spacing' => 1.5, 'justification' => 'center']);

        $oPdf->ezSetDy(-30);
        $sTextoInferior = "La estudiante cursó estudios en el Instituto Universitario de Barlovento transformado en la Universidad ";
        $sTextoInferior .= "Politécnica Territorial de Barlovento Argelia Laya, mediante Decreto No.7.568 de fecha 16-07-2010 publicada ";
        $sTextoInferior .= "en la Gaceta Oficial de la República Bolivariana de Venezuela Extraordinario No. 5.987 de fecha 16-07-2010 ";
        $oPdf->ezText($sTextoInferior, 10, ['spacing' => 1.5, 'justification' => 'full', 'width' => $nWidthArea]);
    }

    private function _muestranotas()
    {
        $oPdf = $this->pdf;
        $cedula = $this->estudiante->origen . '-' . $this->estudiante->cedula;
        $nombre = $this->estudiante->apellidos . ', ' . $this->estudiante->nombres;
        $sCarrera = $this->programa->programa->credencial ?? $this->programa->programa->codename;
        $notaMinimaPrograma = (float)$this->programa->programa->nota_minima;

        $titulo = 'CONSTANCIA DE CERTIFICACION DE NOTAS';
        $nWidth = $oPdf->getTextWidth(10, $titulo);
        $nPos = 306 - ($nWidth / 2);
        $oPdf->addText($nPos, 710, 10, "<b>$titulo</b>");
        $oPdf->addText(100, 690, 8, "<b>CEDULA</b>");
        $oPdf->addText(150, 690, 8, "<b>NOMBRES Y APELLIDOS</b>");
        $oPdf->addText(100, 680, 8, "<b>$cedula</b>");
        $oPdf->addText(150, 680, 8, "<b>$nombre</b>");
        $oPdf->addText(40, 660, 8, "<b>CARRERA: $sCarrera</b>");

        $aData = [];
        $nCont = 0;
        foreach ($this->asignaturas as $asig) {
            if (empty($asig->calificacion)) {
                continue;
            }
            $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
            $nota = $asig->calificacion;
            $letras = '';
            if ($esCualitativa) {
                $letras = strtoupper($nota) === 'A' ? 'APROBADO' : 'REPROBADO';
            } else {
                $notaNum = (float)$nota;
                $letras = $this->_notaEnLetras($notaNum);
            }

            $aData[$nCont] = [
                'trayecto' => $asig->has('trayecto') ? $asig->trayecto->codigo : '',
                'codigo' => $asig->has('asignatura') ? $asig->asignatura->codigo : '',
                'asignatura' => $asig->has('asignatura') ? $asig->asignatura->nombre : '',
                'creditos' => $asig->has('asignatura') ? $asig->asignatura->creditos : '',
                'nota' => $esCualitativa ? $nota : round($notaNum),
                'letras' => $letras,
                'cursada' => $asig->cursada ?? 1,
                'periodo' => $asig->has('periodo') ? $asig->periodo->nombre : '',
                'lapso' => $asig->has('periodo') ? $asig->periodo->lapso : '',
            ];
            $nCont++;
        }

        $aColumns = [
            'trayecto' => '<b>No.</b>',
            'codigo' => '<b>Código</b>',
            'asignatura' => '<b>Unidad Curricular</b>',
            'creditos' => '<b>CR</b>',
            'nota' => '<b>Nota</b>',
            'letras' => '<b>Letras</b>',
            'cursada' => '<b>Cur</b>',
            'periodo' => '<b>Período</b>',
            'lapso' => '<b>Lapso</b>',
        ];

        $aConfig = [
            'showHeadings' => 1,
            'fontSize' => 6,
            'titleFontSize' => 10,
            'showLines' => 0,
            'shaded' => 0,
            'width' => 580,
            'maxWidth' => 580,
            'xOrientation' => 'center',
            'outerLineThickness' => 0.5,
            'innerLineThickness' => 0.5,
            'cols' => [
                'trayecto' => ['justification' => 'center', 'width' => 22],
                'codigo' => ['justification' => 'left', 'width' => 60],
                'asignatura' => ['justification' => 'left', 'width' => 160],
                'creditos' => ['justification' => 'center', 'width' => 25],
                'nota' => ['justification' => 'center', 'width' => 30],
                'letras' => ['justification' => 'left', 'width' => 50],
                'cursada' => ['justification' => 'center', 'width' => 23],
                'periodo' => ['justification' => 'left', 'width' => 50],
                'lapso' => ['justification' => 'left', 'width' => 110],
            ],
        ];

        $oPdf->ezSetDy(-40);
        $oPdf->ezTable($aData, $aColumns, '', $aConfig);
    }

    private function _indiceAcademico()
    {
        $oPdf = $this->pdf;
        $r = $this->resultado;

        $totalCreditos = (int)$this->programa->programa->creditos;
        $eficiencia = $r['ira'] > 0 ? round($r['ira'] * 100 / 20, 2) : 0;

        $aTitles = [
            0 => "<b>Total\n Créditos</b>",
            1 => "<b>Total\n Asignaturas</b>",
            2 => "<b>Créditos\n Aprobados</b>",
            3 => "<b>Asignaturas\n Aprobadas</b>",
            4 => "<b>Indice\n del Proceso</b>",
            5 => "<b>Eficiencia\n del Proceso</b>",
            6 => "<b>Porcentaje\n Aprobado</b>",
        ];

        $aResult[] = [
            0 => $totalCreditos,
            1 => $r['totalAsignaturas'],
            2 => $r['totalCreditosAprobados'],
            3 => $r['totalAsignaturasAprobadas'],
            4 => number_format($r['ira'], 4),
            5 => number_format($eficiencia, 2) . '%',
            6 => number_format($r['porcentajeAprobado'], 2) . '%',
        ];

        $aConfig = [
            'showHeadings' => 1,
            'fontSize' => 8,
            'titleFontSize' => 8,
            'showLines' => 0,
            'shaded' => 0,
            'width' => 490,
            'maxWidth' => 490,
            'xOrientation' => 'center',
            'outerLineThickness' => 0.5,
            'innerLineThickness' => 0.5,
            'cols' => [
                0 => ['justification' => 'center', 'width' => 70],
                1 => ['justification' => 'center', 'width' => 70],
                2 => ['justification' => 'center', 'width' => 70],
                3 => ['justification' => 'center', 'width' => 70],
                4 => ['justification' => 'center', 'width' => 70],
                5 => ['justification' => 'center', 'width' => 70],
                6 => ['justification' => 'center', 'width' => 70],
            ],
        ];

        $oPdf->ezSetDy(-20);
        $oPdf->ezTable($aResult, $aTitles, '<b>RESUMEN ACADEMICO</b>', $aConfig);
    }

    private function _condiciones()
    {
        $oPdf = $this->pdf;
        $cedula = $this->estudiante->origen . '-' . $this->estudiante->cedula;
        $nombre = $this->estudiante->apellidos . ', ' . $this->estudiante->nombres;
        $sCarrera = $this->programa->programa->credencial ?? $this->programa->programa->codename;
        $notaMinima = $this->programa->programa->nota_minima;

        $firmasTable = TableRegistry::getTableLocator()->get('Firmas');
        $firmaSecretaria = $firmasTable->find()->where(['Firmas.codigo' => 'SECRETARIA'])->first();
        $firmaDirector = $firmasTable->find()->where(['Firmas.codigo' => 'DIRECTORCE'])->first();

        $sFirma2 = $firmaSecretaria ? $firmaSecretaria->texto : '';
        $sFirma3 = $firmaDirector ? $firmaDirector->texto : '';

        $titulo = 'CONSTANCIA DE CERTIFICACION DE NOTAS';
        $nWidth = $oPdf->getTextWidth(12, $titulo);
        $nPos = 306 - ($nWidth / 2);
        $oPdf->addText($nPos, 660, 12, "<b>$titulo</b>");

        if (file_exists($this->qrFile)) {
            $oPdf->addPngFromFile($this->qrFile, 30, 690, 40, 40);
            $oPdf->addPngFromFile($this->qrFile, 510, 690, 40, 40);
        }

        $oPdf->addText(40, 630, 10, "<b>CEDULA</b>");
        $oPdf->addText(100, 630, 10, "<b>NOMBRES Y APELLIDOS</b>");
        $oPdf->addText(40, 620, 10, "<b>$cedula</b>");
        $oPdf->addText(100, 620, 10, "<b>$nombre</b>");
        $oPdf->addText(40, 610, 10, "<b>CARRERA: $sCarrera</b>");

        $oPdf->addText(40, 580, 10, "<b>NORMAS:</b>");
        $oPdf->addText(40, 570, 10, "A) La escala de calificaciones es del 1 al 20.");
        $oPdf->addText(40, 560, 10, "B) La calificación mínima para aprobar de $notaMinima puntos.");
        $oPdf->addText(40, 550, 10, "C) Esta constancia sólo muestra la última nota obtenida en cada asignatura.");

        $oPdf->addText(40, 530, 10, "<b>NOTA:</b>");
        $oPdf->addText(40, 520, 10, "CUALQUIER DISCREPANCIA ENCONTRADA ENTRE ESTE DOCUMENTO Y LAS ACTAS ORIGINALES, SE");
        $oPdf->addText(40, 510, 10, "TOMARA COMO VALIDA LA NOTA QUE INDIQUE EL ACTA");

        $oPdf->addText(40, 490, 10, "<b>OBSERVACIONES:</b>");
        $oPdf->addText(40, 480, 10, "<b>No</b>: Semestre / Trayecto. <b>CR</b>: Unidades de Créditos. <b>Cur</b>: Veces Cursadas.");

        $sLugar = $firmasTable->find()->where(['Firmas.codigo' => 'RECTOR'])->first()->lugar ?? 'Higuerote';
        $sText = "Constancia que se expide a petición de la parte interesada en <b>$sLugar el ";
        $sText .= date('d') . " de " . $this->_mesEnEspanol(date('m')) . " de " . date('Y') . "</b>";
        $oPdf->addText(40, 460, 10, $sText);

        $nWidth = $oPdf->getTextWidth(10, "Atentamente,");
        $nPos = 306 - ($nWidth / 2);
        $oPdf->addText($nPos, 400, 10, "Atentamente,");

        $aFirmas = [
            ['secretaria' => $sFirma2, 'coordinador' => $sFirma3],
        ];
        $aColumnas = ['secretaria' => '', 'coordinador' => ''];
        $aConfig = [
            'showHeadings' => 1,
            'fontSize' => 8,
            'titleFontSize' => 10,
            'rowGap' => 2,
            'showLines' => 0,
            'shaded' => 0,
            'width' => 500,
            'maxWidth' => 500,
            'xOrientation' => 'center',
            'outerLineThickness' => 0.5,
            'innerLineThickness' => 0.5,
            'cols' => [
                'secretaria' => ['justification' => 'center'],
                'coordinador' => ['justification' => 'center'],
            ],
        ];
        $oPdf->ezSetDy(-340);
        $oPdf->ezTable($aFirmas, $aColumnas, '', $aConfig);
    }

    private function _pageHeader($titulo)
    {
        $sFullname = Configure::read('Universidad.Title1') . " \u{201C}" . Configure::read('Universidad.Title2') . "\u{201D}";
        $oImage = WWW_ROOT . 'img/site/cintillo.png';

        $oHeader = $this->pdf->openObject();
        $this->pdf->saveState();

        $this->pdf->addPngFromFile($oImage, 30, 740, 540, 30);
        $this->pdf->setLineStyle(2, 'round');
        $this->pdf->line(30, 730, 570, 730);

        $this->pdf->addText(40, 50, 6, 'Este documento fué generado por el usuario: ' . (new \Cake\Http\Session())->read('Auth.User.alias'));
        $this->pdf->line(30, 40, 570, 40);
        $this->pdf->line(570, 730, 570, 40);

        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($oHeader, 'all');
    }

    private function _notaEnLetras($nota)
    {
        $notas = [
        20 => 'VEINTE', 19 => 'DIECINUEVE', 18 => 'DIECIOCHO', 17 => 'DIECISIETE',
        16 => 'DIECISEIS', 15 => 'QUINCE', 14 => 'CATORCE', 13 => 'TRECE',
        12 => 'DOCE', 11 => 'ONCE', 10 => 'DIEZ', 9 => 'NUEVE',
        8 => 'OCHO', 7 => 'SIETE', 6 => 'SEIS', 5 => 'CINCO',
        4 => 'CUATRO', 3 => 'TRES', 2 => 'DOS', 1 => 'UNO', 0 => 'CERO',
    ];
    return $notas[$nota] ?? '';
    }

    private function _mesEnEspanol($mes)
    {
        $meses = [
        '01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril',
        '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto',
        '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre',
    ];
    return $meses[$mes] ?? '';
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
