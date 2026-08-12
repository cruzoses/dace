<?php
namespace App\Reportes;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

use Cezpdf;

/**
 * Reporte de Actas de Grado (libro de actas).
 *
 * Adaptado desde el sistema anterior (Report\ReporteActasGrado) a CakePHP 3.
 */
class ReporteActasGrado
{
    private $nId;
    private $dFecha;
    private $nCarrera;
    private $lUseName;
    private $nIntitucion;

    private $pdf;
    private $noData = true;

    public function __construct($aData = [])
    {
        $this->nId = $aData['promocion'] ?? null;
        $this->dFecha = !empty($aData['fecha']) ? $aData['fecha'] : date('d-m-Y');
        $this->nCarrera = $aData['carrera'] ?? null;
        $this->lUseName = !empty($aData['credencial']);
        $this->nIntitucion = $aData['institucion'] ?? null;
    }

    public function generate()
    {
        $this->pdf = new Cezpdf('LETTER', 'portrait');
        $this->pdf->selectFont('Times-Roman.afm');
        $this->pdf->ezSetCmMargins(3, 3, 4, 3);

        $this->_showContenido($this->pdf);

        if ($this->noData) {
            return ['sFileName' => '', 'noData' => true];
        }

        $reportConfig = $this->_getReportConfig();
        $filename = 'libro_actas_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $this->pdf->ezOutput());
        $sFileName = $reportConfig['webroot'] . $filename;

        return ['sFileName' => $sFileName, 'noData' => false];
    }

    private function _showContenido($oPdf)
    {
        $firmasTable = TableRegistry::getTableLocator()->get('Firmas');
        $oFirma1 = $firmasTable->find()->where(['Firmas.codigo' => 'RECTOR'])->first();
        $oFirma2 = $firmasTable->find()->where(['Firmas.codigo' => 'SECRETARIA'])->first();
        $sFirma1 = $oFirma1 ? $this->_limpiaRubrica($oFirma1->rubrica) : '';
        $sFirma2 = $oFirma2 ? $this->_limpiaRubrica($oFirma2->rubrica) : '';

        $actosTable = TableRegistry::getTableLocator()->get('Actos');
        $oPromo = $actosTable->find()->where(['Actos.id' => $this->nId, 'Actos.activo' => 1])->first();
        $sNombre = $oPromo ? $oPromo->nombre : '';

        $graduandosTable = TableRegistry::getTableLocator()->get('Graduandos');

        $aCondiciones = [
            'Graduandos.institucion' => $this->nIntitucion,
            'Graduandos.acto_id' => $this->nId,
        ];
        if (!empty($this->nCarrera)) {
            $aCondiciones['Graduandos.carrera_id'] = $this->nCarrera;
        }

        $aCarreras = $graduandosTable->find()
            ->select(['carrera_id'])
            ->distinct(['Graduandos.carrera_id'])
            ->where($aCondiciones)
            ->order(['Graduandos.carrera_id' => 'ASC'])
            ->enableHydration(false)
            ->toArray();

        $nTotal = count($aCarreras);
        if ($nTotal == 0) {
            return;
        }

        $nWidthArea = $oPdf->ez['pageWidth'] - $oPdf->ez['rightMargin'] - $oPdf->ez['leftMargin'];
        $nCont = 0;

        foreach ($aCarreras as $aCarreraData) {
            $nCarrera = $aCarreraData['carrera_id'];

            $oModelCarrera = $graduandosTable->Carreras;
            $oCarrera = $oModelCarrera->find()->where(['Carreras.id' => $nCarrera])->first();

            $aGraduandos = $graduandosTable->find()
                ->where([
                    'Graduandos.institucion' => $this->nIntitucion,
                    'Graduandos.acto_id' => $this->nId,
                    'Graduandos.carrera_id' => $nCarrera,
                ])
                ->contain(['Estudiantes', 'Programas', 'Carreras'])
                ->order('Estudiantes.apellidos COLLATE utf8mb4_spanish_ci ASC, Estudiantes.nombres COLLATE utf8mb4_spanish_ci ASC')
                ->all();

            $nRow = 0;
            foreach ($aGraduandos as $oGraduando) {
                $this->noData = false;
                $oHeader = $oPdf->openObject();
                $this->_cabezera($oPdf, $oHeader);

                $oPdf->ezSetDy(-30);

                $aData = [
                    [
                        'Col1' => '<b>No. ' . $oGraduando->control . '</b>',
                        'Col2' => '<b>FOLIO No. ' . $oGraduando->control . '</b>',
                    ],
                ];

                $aOptions = [
                    'fontSize' => 12,
                    'width' => 400,
                    'maxWidth' => 400,
                    'xOrientation' => 'center',
                    'showHeadings' => 0,
                    'showLines' => 0,
                    'cols' => [
                        'Col1' => ['justification' => 'left'],
                        'Col2' => ['justification' => 'right'],
                    ],
                ];
                $oPdf->ezTable($aData, null, null, $aOptions);

                $dFecha = explode('-', $this->dFecha);
                $sTexto = '        Hoy ' . $dFecha[0] . ' de ' . $this->_currentMonth($dFecha[1]) . ' de ' . $dFecha[2] . ' , en la ciudad ';
                $sTexto .= 'de Higuerote, Estado Bolivariano de Miranda, quienes suscriben, Rectora y Secretaria General de la Universidad Politécnica Territorial de Barlovento ';
                $sTexto .= 'Argelia Laya, de conformidad con designación efectuada por el Ministerio del Poder Popular para la Educación Universitaria a través de la Resolución ';
                $sTexto .= 'No. 055, de fecha 21/05/2025 y publicada en Gaceta Oficial No. 43.136, en uso de las atribuciones conferidas en el Reglamento de Organización y ';
                $sTexto .= 'Funcionamiento de la UPTBAL, publicado en Gaceta Extraordinaria No. 6.321 de fecha 04/08/2017 Expiden el Título en:';

                $aTextOptions = [
                    'justification' => 'full',
                    'width' => $nWidthArea,
                    'fontSize' => 12,
                    'spacing' => 1.5,
                ];

                $oPdf->ezSetDy(-10);
                $oPdf->ezText($sTexto, 12, $aTextOptions);

                $oPdf->ezSetDy(-10);
                $sTitulo = $this->lUseName
                    ? ($oGraduando->has('programa') ? $oGraduando->programa->credencial : '')
                    : ($oCarrera ? $oCarrera->titulo_otorgado : '');
                $oPdf->ezText('<b>' . $sTitulo . '</b>', 12, ['justification' => 'center', 'width' => $nWidthArea]);

                $sTexto = 'A: <b>' . $oGraduando->estudiante->apellidos . '  ' . $oGraduando->estudiante->nombres . '</b>,  titular  de  la ';
                $sTexto .= ' Cédula  de  Identidad  No. <b> ' . $this->_fnc($oGraduando->estudiante->cedula) . '</b>, por haber aprobado satisfactoriamente el plan de estudios ';
                $sTexto .= 'en tal opción y cumplido los requisitos de Ley que rigen la materia.';

                $oPdf->ezSetDy(-10);
                $oPdf->ezText($sTexto, 12, $aTextOptions);

                $aFirmas = [
                    ['rector' => $sFirma1, 'secretaria' => $sFirma2],
                ];
                $aColumnas = ['rector' => '', 'secretaria' => ''];
                $aFirmasOptions = [
                    'showHeadings' => 1,
                    'fontSize' => 10,
                    'titleFontSize' => 10,
                    'rowGap' => 2,
                    'showLines' => 0,
                    'shaded' => 0,
                    'width' => 420,
                    'maxWidth' => 420,
                    'xOrientation' => 'center',
                    'outerLineThickness' => 0.5,
                    'innerLineThickness' => 0.5,
                    'cols' => [
                        'rector' => ['justification' => 'center'],
                        'secretaria' => ['justification' => 'center'],
                    ],
                ];

                $oPdf->ezSetDy(-20);
                $oPdf->ezTable($aFirmas, $aColumnas, '', $aFirmasOptions);

                $oPdf->ezSetDy(-50);
                $sNameStudent = $oGraduando->estudiante->apellidos . ' ' . $oGraduando->estudiante->nombres;
                $nLen = strlen($sNameStudent) + 14;
                $sLinea = str_repeat('_', $nLen);
                $nWidth = $oPdf->getTextWidth(11, $sLinea);
                $nPosX = ($oPdf->ez['pageWidth'] / 2) - ($nWidth / 2);
                $nPosY = $oPdf->y;
                $oPdf->line($nPosX + 24, $nPosY, $nPosX + $nWidth, $nPosY);

                $oPdf->ezText('<b>' . $sNameStudent . '</b>', 10, ['justification' => 'center', 'width' => $nWidthArea, 'fontSize' => 10]);
                $oPdf->ezText($this->_fnc($oGraduando->estudiante->cedula), 10, ['justification' => 'center', 'width' => $nWidthArea, 'fontSize' => 10]);
                $oPdf->ezText('GRADUANDO', 10, ['justification' => 'center', 'width' => $nWidthArea, 'fontSize' => 12]);

                $oPdf->ezSetDy(-10);
                $oPdf->ezText('<b>' . $sNombre . '</b>', 12, ['justification' => 'center', 'width' => $nWidthArea, 'fontSize' => 12]);

                $nRow++;
                $oPdf->stopObject($oHeader);

                if ($nRow < count($aGraduandos)) {
                    $oPdf->ezNewPage();
                }
            }

            if ($nCont < $nTotal - 1) {
                $oPdf->ezNewPage();
            }

            $nCont++;
        }
    }

    private function _cabezera($oPdf, $oHeader)
    {
        $oPdf->saveState();
        $oPdf->setColor(0.9, 0.9, 0.9);
        $oPdf->setColor(0, 0, 0);

        $oImage = WWW_ROOT . 'img' . DS . 'site' . DS . 'cintillo.png';
        if (file_exists($oImage)) {
            $oPdf->addPngFromFile($oImage, 90, 740, 480, 30);
        }

        $nWidthArea = $oPdf->ez['pageWidth'] - $oPdf->ez['rightMargin'] - $oPdf->ez['leftMargin'];

        $oPdf->ezText('REPÚBLICA BOLIVARIANA DE VENEZUELA', 11, ['justification' => 'center', 'spacing' => 1]);
        $oPdf->ezText('MINISTERIO DEL PODER POPULAR PARA LA EDUCACION UNIVERSITARIA', 11, ['justification' => 'center', 'spacing' => 1]);
        $oPdf->ezText('<b>UNIVERSIDAD POLITÉCNICA TERRITORIAL</b>', 11, ['justification' => 'center', 'spacing' => 1]);
        $oPdf->ezText('<b>DE BARLOVENTO "ARGELIA LAYA"</b>', 11, ['justification' => 'center', 'spacing' => 1]);
        $oPdf->ezText('SECRETARÍA GENERAL', 11, ['justification' => 'center', 'spacing' => 1.5]);
        $oPdf->ezText('HIGUEROTE ESTADO MIRANDA', 11, ['justification' => 'center', 'spacing' => 1]);
        $oPdf->restoreState();
        $oPdf->closeObject();
        $oPdf->addObject($oHeader, 'all');
    }

    private function _limpiaRubrica($sRubrica)
    {
        if ($sRubrica === null) {
            return '';
        }
        $sRubrica = str_replace(["\r\n", "\r", "\n"], '<br>', $sRubrica);

        return $sRubrica;
    }

    private function _currentMonth($monthNum)
    {
        $aMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return $aMeses[(int)$monthNum] ?? '';
    }

    private function _fnc($nCedula)
    {
        $nLen = strlen((string)$nCedula);
        switch ($nLen) {
            case 5:
                $cMask = substr($nCedula, 0, 1) . '.' . substr($nCedula, 2, 3);
                break;
            case 6:
                $cMask = substr($nCedula, 0, 2) . '.' . substr($nCedula, 3, 3);
                break;
            case 7:
                $cMask = substr($nCedula, 0, 1) . '.' . substr($nCedula, 1, 3) . '.' . substr($nCedula, 4, 3);
                break;
            case 8:
                $cMask = substr($nCedula, 0, 2) . '.' . substr($nCedula, 2, 3) . '.' . substr($nCedula, 5, 3);
                break;
            case 9:
                $cMask = substr($nCedula, 0, 3) . '.' . substr($nCedula, 4, 3) . '.' . substr($nCedula, 7, 3);
                break;
            default:
                $cMask = $nCedula;
                break;
        }

        return $cMask;
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