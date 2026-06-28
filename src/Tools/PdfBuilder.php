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
        'shaded'        => 1,
        'shadeCol'      => [0.9, 0.9, 0.9],
        'width'         => 500,
        'maxWidth'      => 500,
        'xOrientation'  => 'centre',
        'outerLineThickness' => 0.5,
        'innerLineThickness' => 0.5,
        'cols'          => [],
    ];

    public function __construct($user = null)
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->ezSetCmMargins(2.8, 1.5, 1.5, 1.5);
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

        $this->pdf->ezText("\n", 10);

        $this->pdf->ezTable($data, null, '', $this->aConfig);

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

    private function pageHeader($title)
    {
        $oImage = WWW_ROOT . 'img/site/cintillo.png';
        $nWidthArea = $this->pdf->ez['pageWidth'];

        $oHeader = $this->pdf->openObject();
        $this->pdf->saveState();

        $this->pdf->addPngFromFile($oImage, 30, 740, 540, 30);

        $userAlias = isset($this->user['alias']) ? $this->user['alias'] : '---';

        $siglas = Configure::read('Universidad.Siglas');
        $this->pdf->addText(40, 722, 12, $siglas);
        $this->pdf->addText(306 - ($this->pdf->getTextWidth(12, $title) / 2), 722, 12, $title);
        $this->pdf->addText(484, 730, 8, 'Fecha: ' . date('d-m-Y'));
        $this->pdf->addText(484, 720, 8, 'Hora: ' . date('h:i a'));

        $this->pdf->line(40, 713, 570, 713);

        $this->pdf->addText(40, 50, 6, 'Generado por: ' . $this->user . '    ' . date('d/m/Y h:i A'));
        $this->pdf->line(40, 42, 570, 42);

        $this->pdf->restoreState();
        $this->pdf->closeObject();
        $this->pdf->addObject($oHeader, 'all');
    }
}
