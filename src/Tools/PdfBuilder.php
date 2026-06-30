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

    public function __construct($user = null)
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->selectFont('Helvetica.afm');
        $this->pdf->ezSetCmMargins(4, 3, 2, 2);
        $this->pdf->ezStartPageNumbers(540,50,8,'','',1); 
        if ($user) 
        {
            $this->user = $user;
        } else {
            $session = new \Cake\Http\Session();
            $this->user = $session->read('Auth.User.alias');
        }
    }

    public function generateSimpleReport($data, $title = 'REPORTE')
    {
        $this->pageHeader($title);

        //$this->pdf->ezText("\n", 10);
        $this->pdf->ezSetY(680);

        $this->pdf->ezTable($data, null, '', $this->aConfig);
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
        $nWidthArea = $this->pdf->ez['pageWidth'];

        $oHeader = $this->pdf->openObject();
        $this->pdf->saveState();

        $this->pdf->addPngFromFile($oImage, 30, 740, 540, 30);

        $this->pdf->ezSetY(735);
        $this->pdf->ezText("<b>".$sFullname."</b>", 12, ['justification' => 'center']);

        $this->pdf->ezSetY(715);
        $this->pdf->ezText("<b>".$sTitle."</b>", 12, ['justification' => 'center']);

        $this->pdf->ezSetY(695);
        $this->pdf->addText(050, 695, 10, "<b>".Configure::read('Universidad.Siglas')."</b>");
        $this->pdf->addText(490, 695, 8, "<b>R.I.F</b> " . Configure::read('Universidad.RIF'));
        $this->pdf->ezSetY(690);
              
        $this->pdf->line(30, 690, 580, 690);

        $this->pdf->addText(40, 50, 8, 'Generado por: ' . $this->user . '    ' . date('d/m/Y h:i A'));
        $this->pdf->line(40, 42, 570, 42);

        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($oHeader, 'all');
    }
}
