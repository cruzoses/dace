<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Tools\ExcelBuilder;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ArchivosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['exportarEstados', 'exportarMunicipios', 'exportarParroquias', 'exportarPeriodos', 'exportarDocentes', 'exportarSituacion', 'exportarParticipantes']);
    }

    public function exportarEstados()
    {
        $estadosTable = TableRegistry::getTableLocator()->get('Estados');
        $estados = $estadosTable->find('all', [
            'contain' => ['Paises'],
            'order' => ['Paises.nombre' => 'ASC', 'Estados.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($estados as $e) {
            $data[] = [
                'Codigo' => $e->id,
                'Pais' => $e->paise->nombre,
                'Nombre' => $e->nombre,
                'Creado' => $e->created->format('d/m/Y'),
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Pais' => ['justification' => 'left'],
            'Nombre' => ['justification' => 'left'],
            'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('estados');

        $content = $excel->generateExcel($data, 'LISTADO DE ESTADOS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarMunicipios()
    {
        $municipiosTable = TableRegistry::getTableLocator()->get('Municipios');
        $municipios = $municipiosTable->find('all', [
            'contain' => ['Estados'],
            'order' => ['Estados.nombre' => 'ASC', 'Municipios.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($municipios as $m) {
            $data[] = [
                'Codigo' => $m->id,
                'Estado' => $m->estado->nombre,
                'Nombre' => $m->nombre,
                'Creado' => $m->created->format('d/m/Y'),
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Estado' => ['justification' => 'left'],
            'Nombre' => ['justification' => 'left'],
            'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('municipios');

        $content = $excel->generateExcel($data, 'LISTADO DE MUNICIPIOS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarParroquias()
    {
        $parroquiasTable = TableRegistry::getTableLocator()->get('Parroquias');
        $parroquias = $parroquiasTable->find('all', [
            'contain' => ['Municipios'],
            'order' => ['Municipios.nombre' => 'ASC', 'Parroquias.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($parroquias as $p) {
            $data[] = [
                'Codigo' => $p->id,
                'Municipio' => $p->municipio->nombre,
                'Nombre' => $p->nombre,
                'Creado' => $p->created->format('d/m/Y'),
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Municipio' => ['justification' => 'left'],
            'Nombre' => ['justification' => 'left'],
            'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('parroquias');

        $content = $excel->generateExcel($data, 'LISTADO DE PARROQUIAS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarPeriodos()
    {
        $periodosTable = TableRegistry::getTableLocator()->get('Periodos');
        $periodos = $periodosTable->find('all', [
            'order' => ['Periodos.lapso' => 'DESC', 'Periodos.codigo' => 'ASC']
        ]);

        $data = [];
        foreach ($periodos as $p) {
            $data[] = [
                'Codigo' => $p->codigo,
                'Nombre' => $p->nombre,
                'Año' => $p->lapso,
                'Nota_Minima' => $p->nota_minima,
                'Inicio' => $p->inicio->format('d/m/Y'),
                'Cierre' => $p->cierre->format('d/m/Y'),
                'Califica' => $p->califica ? 'Si' : 'No',
                'Activo' => $p->activo ? 'Si' : 'No',
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Nombre' => ['justification' => 'left'],
            'Año' => ['justification' => 'center'],
            'Nota_Minima' => ['justification' => 'center'],
            'Inicio' => ['justification' => 'center'],
            'Cierre' => ['justification' => 'center'],
            'Califica' => ['justification' => 'center'],
            'Activo' => ['justification' => 'center'],
        ]);
        $excel->setFileName('periodos');

        $content = $excel->generateExcel($data, 'LISTADO DE PERIODOS ACADÉMICOS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarDocentes()
    {
        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $docentes = $docentesTable->find('all', [
            'contain' => ['Departamentos', 'Usuarios'],
            'order' => ['Docentes.apellidos' => 'ASC', 'Docentes.nombres' => 'ASC']
        ]);

        $data = [];
        foreach ($docentes as $d) {
            $data[] = [
                'Cédula' => $d->cedula,
                'Nombres' => $d->nombres,
                'Apellidos' => $d->apellidos,
                'fecha_nacimiento' => $d->fecha_nacimiento ? $d->fecha_nacimiento->format('d/m/Y') : '',
                'Sexo' => $d->sexo,
                'Correo' => $d->email,
                // 'Departamento' => $d->departamento->nombre,
                // 'Usuario' => $d->has('usuario') ? $d->usuario->username : 'N/A',
                //  'Creado' => $d->created->format('d/m/Y'),
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Cédula' => ['justification' => 'center'],
            'Nombres' => ['justification' => 'left'],
            'Apellidos' => ['justification' => 'left'],
            'Fecha_Nacimiento' => ['justification' => 'center'],
            'Sexo' => ['justification' => 'center'],
            'Correo' => ['justification' => 'left'],
            //'Departamento' => ['justification' => 'left'],
            //'Usuario' => ['justification' => 'center'],
            //'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('docentes');

        $content = $excel->generateExcel($data, 'LISTADO DE DOCENTES');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarSituacion($estudianteId = null, $programaId = null)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programasQuery = $programasTable->find()
            ->where(['EstudianteProgramas.estudiante_id' => $estudianteId])
            ->contain(['Carreras', 'Programas']);

        if ($programaId) {
            $programasQuery->where(['EstudianteProgramas.programa_id' => $programaId]);
        }

        $programas = $programasQuery->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $totalCols = 9;
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);
        $universidad = html_entity_decode(Configure::read('Universidad.Nombre'), ENT_QUOTES, 'UTF-8');
        $cedula = $estudiante->origen . '-' . $estudiante->cedula;
        $full_name = $estudiante->apellidos . ', ' . $estudiante->nombres;

        $row = 1;

        $sheet->mergeCells('A' . $row . ':' . $lastCol . $row);
        $sheet->setCellValueByColumnAndRow(1, $row, $universidad);
        $sheet->getStyleByColumnAndRow(1, $row)->getFont()->setBold(true)->setSize(13);
        $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $row++;

        foreach ($programas as $programa) {
            $sheet->mergeCells('A' . $row . ':' . $lastCol . $row);
            $sheet->setCellValueByColumnAndRow(1, $row, $programa->programa->codename);
            $sheet->getStyleByColumnAndRow(1, $row)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;

            $sheet->mergeCells('A' . $row . ':' . $lastCol . $row);
            $sheet->setCellValueByColumnAndRow(1, $row, 'Cédula: ' . $cedula . '    Nombre: ' . $full_name);
            $sheet->getStyleByColumnAndRow(1, $row)->getFont()->setBold(true)->setSize(10);
            $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $row++;

            $row++;

            $headers = ['No', 'Trayecto', 'Asignatura', 'Créditos', 'Nombre de la Asignatura', 'Nota', 'Sección', 'Periodo', 'Responsable'];
            $colIndex = 1;
            foreach ($headers as $header) {
                $sheet->setCellValueByColumnAndRow($colIndex, $row, $header);
                $sheet->getStyleByColumnAndRow($colIndex, $row)->getFont()->setBold(true)->setSize(10);
                $sheet->getStyleByColumnAndRow($colIndex, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyleByColumnAndRow($colIndex, $row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                $colIndex++;
            }
            $row++;

            $asignaturasTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
            $asignaturas = $asignaturasTable->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programa->programa_id,
                ])
                ->contain(['Asignaturas', 'Trayectos', 'Periodos'])
                ->order(['SituacionEstudiantes.trayecto_id' => 'ASC', 'SituacionEstudiantes.asignatura_id' => 'ASC'])
                ->toArray();

            $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
            $mallas = $mallasTable->find()
                ->where(['Mallas.programa_id' => $programa->programa_id])
                ->toArray();
            $mallasPorAsignatura = [];
            foreach ($mallas as $m) {
                $mallasPorAsignatura[$m->asignatura_id] = $m;
            }

            $notaMinimaPrograma = (float)$programa->programa->nota_minima;
            $totalCreditosPrograma = (int)$programa->programa->creditos;
            $totalCreditosAprobados = 0;
            $totalAsignaturasAprobadas = 0;
            $isaNumerador = 0;
            $isaDenominador = 0;
            $iraNumerador = 0;
            $iraDenominador = 0;

            $i = 1;
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
                    $isaNumerador += $notaISA * $creditos;
                    $isaDenominador += $creditos;
                    if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                        $iraNumerador += (int)$asig->acumulado;
                    } else {
                        $notaIRA = $esCualitativa ? $notaISA : (float)$asig->calificacion;
                        $iraNumerador += $notaIRA * $creditos;
                    }
                    $iraDenominador += $creditos * (int)$asig->cursada;
                }

                $rowData = [
                    $i++,
                    $asig->has('trayecto') ? $asig->trayecto->codigo : '',
                    $asig->has('asignatura') ? $asig->asignatura->codigo : '',
                    $creditos,
                    $asig->has('asignatura') ? $asig->asignatura->nombre : '',
                    !empty($asig->calificacion) ? $asig->calificacion : '',
                    !empty($asig->calificacion) ? $asig->seccion : '',
                    !empty($asig->calificacion) && $asig->has('periodo') ? $asig->periodo->lapso : '',
                    !empty($asig->calificacion) ? $asig->responsable : '',
                ];
                $colIndex = 1;
                $centerCols = [2, 4, 6, 7];
                foreach ($rowData as $value) {
                    $cell = $sheet->getCellByColumnAndRow($colIndex, $row);
                    $cell->setValue($value);
                    if (in_array($colIndex, $centerCols)) {
                        $sheet->getStyleByColumnAndRow($colIndex, $row)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $colIndex++;
                }
                $row++;
            }

            $porcentajeAprobado = $totalCreditosPrograma > 0
                ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 1) : 0;
            $isa = $isaDenominador > 0 ? round($isaNumerador / $isaDenominador, 5) : 0;
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

            $sheet->mergeCells('A' . $row . ':' . $lastCol . $row);
            $sheet->setCellValueByColumnAndRow(1, $row, 'Créditos Aprobados: ' . $totalCreditosAprobados . ' / ' . $totalCreditosPrograma . '    ISA: ' . $isa . '    IRA: ' . $ira . '    Aprobado: ' . $porcentajeAprobado . '%');
            $sheet->getStyleByColumnAndRow(1, $row)->getFont()->setBold(true)->setSize(10);
            $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $row++;

            $row++;
        }

        foreach (range(1, $totalCols) as $col) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'situacion_academica_' . $estudianteId . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarParticipantes($cursoId = null)
    {
        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($cursoId, [
            'contain' => ['Asignaturas', 'Periodos'],
        ]);

        $titulo = strtoupper($curso->asignatura->nombre) . ' - ' . $curso->periodo->codigo;

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $inscritos = TableRegistry::getTableLocator()->get('EstudianteCursos')->find()
            ->contain(['Estudiantes'])
            ->where(['EstudianteCursos.curso_id' => $cursoId, 'EstudianteCursos.activo' => 1])
            ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC']);

        $data = [];
        $i = 1;
        foreach ($inscritos as $ec) {
            $data[] = [
                'No.' => $i++,
                'Cedula' => $ec->estudiante->cedula,
                'Apellidos' => $ec->estudiante->apellidos,
                'Nombres' => $ec->estudiante->nombres,
                'Expediente' => $ec->estudiante->expediente,
                'Seccion' => $curso->seccion,
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'No.' => ['justification' => 'center'],
            'Cedula' => ['justification' => 'center'],
            'Apellidos' => ['justification' => 'left'],
            'Nombres' => ['justification' => 'left'],
            'Expediente' => ['justification' => 'center'],
            'Seccion' => ['justification' => 'center'],
        ]);
        $excel->setFileName('participantes_curso_' . $cursoId);

        $content = $excel->generateExcel($data, 'PARTICIPANTES - ' . $titulo);

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }
    
}

