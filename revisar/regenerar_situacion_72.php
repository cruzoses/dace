<?php
require dirname(__DIR__, 2) . '/dace/vendor/autoload.php';
require dirname(__DIR__, 2) . '/dace/config/bootstrap.php';

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

$conn = ConnectionManager::get('default');

echo "Inicio regeneracion situacion programa 72 - " . date('Y-m-d H:i:s') . PHP_EOL;

$estudiantes = $conn->execute(
    "SELECT ep.estudiante_id, ep.programa_id, ep.carrera_id, ep.periodo_id
     FROM estudiante_programas ep
     WHERE ep.programa_id = 72"
)->fetchAll('assoc');

echo "Estudiantes del programa 72: " . count($estudiantes) . PHP_EOL;

$situacionTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');

$totalActualizados = 0;
$totalEliminados = 0;
foreach ($estudiantes as $est) {
    $estId = (int)$est['estudiante_id'];
    $progId = (int)$est['programa_id'];
    $carrId = (int)$est['carrera_id'];
    $perId = (int)$est['periodo_id'];

    $eliminados = $conn->execute(
        "DELETE FROM situacion_estudiantes WHERE estudiante_id = :e AND programa_id = :p",
        ['e' => $estId, 'p' => $progId]
    )->rowCount();
    $totalEliminados += $eliminados;

    $situacionTable->registrarDesdeMalla($estId, $progId, $carrId, $perId);
    $totalActualizados += $situacionTable->sincronizarDesdeHistorico($estId, $progId);
}

echo "Filas eliminadas: $totalEliminados" . PHP_EOL;
echo "Asignaturas sincronizadas: $totalActualizados" . PHP_EOL;
echo "Fin - " . date('Y-m-d H:i:s') . PHP_EOL;
