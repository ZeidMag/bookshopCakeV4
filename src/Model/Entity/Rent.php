<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Rent extends Entity
{
    protected $_accessible = [
        '*' => true,
        'created' => true,
        'duration_days' => true,
        'user' => true,
        'book' => true,
    ];
}