<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TablanotasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tablanotas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
        ]);
        $this->belongsTo('Asignaturas', [
            'foreignKey' => 'asignatura_id',
        ]);
        $this->belongsTo('Periodos', [
            'foreignKey' => 'periodo_id',
        ]);
    }
}
