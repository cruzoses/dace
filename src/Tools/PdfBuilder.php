<?php
namespace App\Tools;

use Cake\Core\Configure;
use Cezpdf;

class PdfBuilder
{
    private $pdf;
    private $user;

    private $aConfig = [
        'showHeadings'  => 1,
        'fontSize'      => 8,
        'titleFontSize' => 10,
        'showLines'     => 1,
        'shaded'        => 0,
        'shadeCol'      => 0, //[0.9, 0.9, 0.9],
        'width'         => 500,
        'maxWidth'      => 500,
        'xOrientation'  => 'centre',
        'outerLineThickness' => 0.5,
        'innerLineThickness' => 0.5,
        'cols' => [],
    ];

    public function __construct($orientation = 'portrait')
    {
        $this->pdf = new Cezpdf('LETTER', $orientation);
        $this->pdf->selectFont('Helvetica.afm');
        $this->pdf->ezSetCmMargins(4, 3, 2, 2);
        $xPageNum = $orientation === 'landscape' ? 720 : 540;
        $this->pdf->ezStartPageNumbers($xPageNum, 50, 8, 'right', 'Página {PAGENUM} de {TOTALPAGENUM}', 1); 
        $session = new \Cake\Http\Session();
        $this->user = $session->read('Auth.User.alias');
    }

    public function generateSimpleReport($data, $title = 'REPORTE')
    {
        $this->pageHeader($title);

        $isLandscape = $this->pdf->ez['pageWidth'] > 700;

        $this->pdf->ezSetY($isLandscape ? 490 : 680);

        $config = $this->aConfig;
        if ($isLandscape && empty($config['cols'])) {
            $config['width'] = 700;
            $config['maxWidth'] = 700;
        }

        $this->pdf->ezTable($data, null, '', $config);
        $this->pdf->ezStopPageNumbers(1,1);

        return $this->pdf->ezOutput();
    }

    public function setColumns($columns)
    {
        $this->aConfig['cols'] = $columns;
    }

    public function getUser()
    {
        return $this->user;
    }

    private function pageHeader($sTitle)
    {
        $sFullname = Configure::read('Universidad.Title1') ." \u{201C}" . Configure::read('Universidad.Title2') ."\u{201D}" ;
        $userAlias = isset( $this->user['alias'] ) ? $this->user['alias'] : 'SACE UPTBAL';
        $oImage = WWW_ROOT . 'img/site/cintillo.png';
        $isLandscape = $this->pdf->ez['pageWidth'] > 700;

        $oHeader = $this->pdf->openObject();
        $this->pdf->saveState();

        if ($isLandscape) {
            $this->pdf->addPngFromFile($oImage, 30, 560, 740, 30);

            $this->pdf->ezSetY(555);
            $this->pdf->ezText("<b>".$sFullname."</b>", 12, ['justification' => 'center']);

            $this->pdf->ezSetY(535);
            $this->pdf->ezText("<b>".$sTitle."</b>", 12, ['justification' => 'center']);

            $this->pdf->addText(050, 515, 10, "<b>".Configure::read('Universidad.Siglas')."</b>");
            $this->pdf->addText(690, 515, 8, "<b>R.I.F</b> " . Configure::read('Universidad.RIF'));

            $this->pdf->line(30, 510, 760, 510);

            $this->pdf->line(40, 42, 750, 42);
        } else {
            $this->pdf->addPngFromFile($oImage, 30, 740, 540, 30);

            $this->pdf->ezSetY(735);
            $this->pdf->ezText("<b>".$sFullname."</b>", 12, ['justification' => 'center']);

            $this->pdf->ezSetY(715);
            $this->pdf->ezText("<b>".$sTitle."</b>", 12, ['justification' => 'center']);

            $this->pdf->ezSetY(695);
            $this->pdf->addText(050, 695, 10, "<b>".Configure::read('Universidad.Siglas')."</b>");
            $this->pdf->addText(490, 695, 8, "<b>R.I.F</b> " . Configure::read('Universidad.RIF'));

            $this->pdf->line(30, 690, 580, 690);

            $this->pdf->line(40, 42, 570, 42);
        }

        $this->pdf->addText(40, 50, 8, 'Generado por: ' . $this->user . '    ' . date('d/m/Y h:i A'));

        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($oHeader, 'all');
    }
}
