<?php
namespace App\Reportes;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cezpdf;

class CulminacionPrograma
{
    private $estudiante;
    private $programa;
    private $pdf;

    public function __construct($estudiante, $programa)
    {
        $this->estudiante = $estudiante;
        $this->programa = $programa;
    }

    public function generate()
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->selectFont('Helvetica.afm');
        $this->pdf->ezSetCmMargins(3.6, 2.5, 3, 3);

        $this->_pageHeader();
        $this->pdf->ezStartPageNumbers(540, 50, 6, 'right', '', 1);

        $this->_culminacion();

        $this->pdf->ezStopPageNumbers(1, 1);

        $reportConfig = $this->_getReportConfig();
        $filename = 'culminacion_' . $this->estudiante->cedula . '_' . $this->programa->programa->codigo . '_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $this->pdf->ezOutput());
        $sFileName = $reportConfig['webroot'] . $filename;

        return ['sFileName' => $sFileName, 'noData' => false];
    }

    private function _culminacion()
    {
        $oPdf = $this->pdf;
        $cedula = $this->estudiante->origen . '-' . $this->estudiante->cedula;
        $nombre = $this->estudiante->apellidos . ', ' . $this->estudiante->nombres;
        $sCarrera = $this->programa->programa->credencial ?? $this->programa->programa->codename;

        $firmasTable = TableRegistry::getTableLocator()->get('Firmas');
        $firmaDirector = $firmasTable->find()->where(['Firmas.codigo' => 'DIRECTORCE'])->first();

        $sDatos = $firmaDirector ? $firmaDirector->datos : '';
        $sFirma = $firmaDirector ? $firmaDirector->texto : '';
        $sLugar = $firmaDirector ? $firmaDirector->lugar : 'Higuerote';

        $titulo = 'CONSTANCIA DE CULMINACION';
        $nWidth = $oPdf->getTextWidth(12, $titulo);
        $nPos = 306 - ($nWidth / 2);
        $oPdf->addText($nPos, 620, 12, "<b>$titulo</b>");

        $oPdf->ezSetDy(-120);
        $sText = "Quien suscribe, $sDatos hace constar por medio de la presente que el ciudadano ";
        $sText .= "<b>$nombre</b>, titular de la cédula de identidad N.º <b>$cedula</b>, culminó ";
        $sText .= "satisfactoriamente los estudios de <b>$sCarrera</b>, ";
        $sText .= "y en estos momentos se encuentra en la espera del Acto Académico.";

        $aOptions = [
            'spacing' => 1.5,
            'justification' => 'full',
        ];
        $oPdf->ezText($sText, 10, $aOptions);

        $sText = "Constancia que se expide a petición de la parte interesada en <b>$sLugar el ";
        $sText .= date('d') . " de " . $this->_mesEnEspanol(date('m')) . " de " . date('Y') . "</b>";
        $oPdf->ezSetDy(-50);
        $oPdf->ezText($sText, 10, $aOptions);

        $oPdf->ezSetDy(-50);
        $oPdf->ezText('Atentamente,', 10, ['justification' => 'center']);
        $oPdf->ezSetDy(-30);
        $oPdf->ezText($sFirma, 10, ['spacing' => 1.5, 'justification' => 'center']);
    }

    private function _pageHeader()
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
