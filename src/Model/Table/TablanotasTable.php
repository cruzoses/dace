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
    }
}
