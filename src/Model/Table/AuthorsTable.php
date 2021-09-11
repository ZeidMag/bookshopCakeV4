<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AuthorsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('authors');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Books', [
            'foreignKey' => 'author_id',
        ]);
    }
    
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}