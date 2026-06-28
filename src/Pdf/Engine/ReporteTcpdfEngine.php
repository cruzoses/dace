<?php
namespace App\Pdf\Engine;

use CakePdf\Pdf\Engine\AbstractPdfEngine;
use TCPDF;

class ReporteTcpdfEngine extends AbstractPdfEngine
{
    public function output()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $pdfConfig = $this->_Pdf;
        $orientation = $pdfConfig->orientation();
        $pageSize = $pdfConfig->pageSize();
        $html = $pdfConfig->html();
        $margin = $pdfConfig->margin();

        $headerImage = $this->getConfig('headerImage');
        $uniName = $this->getConfig('uniName') ?: '';
        $reportTitle = $this->getConfig('reportTitle') ?: '';
        $userName = $this->getConfig('userName') ?: '';

        $TCPDF = new ReporteTcpdf($orientation, 'mm', $pageSize, true, 'UTF-8', false, $headerImage, $uniName, $reportTitle, $userName);

        $topMargin = 28;

        $TCPDF->SetMargins(
            $margin['left'] ?: 15,
            $topMargin,
            $margin['right'] ?: 15
        );
        $TCPDF->SetAutoPageBreak(true, $margin['bottom'] ?: 30);
        $TCPDF->setHeaderMargin(5);
        $TCPDF->setFooterMargin(10);
        $TCPDF->setPrintHeader(true);
        $TCPDF->setPrintFooter(true);

        $TCPDF->AddPage();
        $TCPDF->SetY($topMargin);
        $TCPDF->writeHTML($html);

        return $TCPDF->Output('', 'S');
    }
}

class ReporteTcpdf extends TCPDF
{
    private $headerImage;
    private $uniName;
    private $reportTitle;
    private $userName;

    public function __construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $headerImage, $uniName, $reportTitle, $userName)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        $this->headerImage = $headerImage;
        $this->uniName = $uniName;
        $this->reportTitle = $reportTitle;
        $this->userName = $userName;
    }

    public function Header()
    {
        if ($this->headerImage && @file_exists($this->headerImage)) {
            $this->Image($this->headerImage, 15, 3, 180, 0, 'PNG');
        }

        $this->SetY(16);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 4, $this->uniName, 0, 1, 'C');

        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 5, $this->reportTitle, 0, 1, 'C');

        $this->Line(15, $this->GetY() + 1, 195, $this->GetY() + 1);
    }

    public function Footer()
    {
        $this->SetY(-22);
        $this->SetFont('helvetica', '', 6);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->SetY($this->GetY() + 2);
        $this->Cell(90, 3, 'Generado por: ' . $this->userName, 0, 0, 'L');
        $this->Cell(0, 3, date('d/m/Y h:i A'), 0, 0, 'C');
        $this->Cell(0, 3, 'Pagina ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'R');
    }
}
