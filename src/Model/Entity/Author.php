<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Author extends Entity
{
    protected $_accessible = [
        'name' => true,
        'books' => true,
    ];
}