<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class RentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('rents');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey(['user_id', 'book_id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Books', [
            'foreignKey' => 'book_id',
            'joinType' => 'INNER',
        ]);
    }
    
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('duration_days')
            ->allowEmptyString('duration_days');

        return $validator;
    }
    public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['book_id'], 'Books'));

        return $rules;
    }
}