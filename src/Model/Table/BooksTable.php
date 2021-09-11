<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class BooksTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('books');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Authors', [
            'foreignKey' => 'author_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Rents', [
            'foreignKey' => 'book_id',
        ]);
    }
    
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->integer('pages')
            ->allowEmptyString('pages');

        $validator
            ->integer('publish_year')
            ->allowEmptyString('publish_year');
        
        $validator
            ->scalar('image_url')
            ->maxLength('image_url', 255)
            ->requirePresence('image_url', 'create')
            ->allowEmptyString('image_url');

        return $validator;
    }
    public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->existsIn(['author_id'], 'Authors'));

        return $rules;
    }
}