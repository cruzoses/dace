<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RolsUsuarios Model
 *
 * @property \App\Model\Table\RolsTable&\Cake\ORM\Association\BelongsTo $Rols
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 *
 * @method \App\Model\Entity\RolsUsuario get($primaryKey, $options = [])
 * @method \App\Model\Entity\RolsUsuario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RolsUsuario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RolsUsuario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RolsUsuario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RolsUsuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RolsUsuario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RolsUsuario findOrCreate($search, callable $callback = null, $options = [])
 */
class RolsUsuariosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('rols_usuarios');
        $this->setDisplayField('rol_id');
        $this->setPrimaryKey(['rol_id', 'usuario_id']);

        $this->belongsTo('Rols', [
            'foreignKey' => 'rol_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['rol_id'], 'Rols'));
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'));

        return $rules;
    }
}
