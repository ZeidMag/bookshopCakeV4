<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Book extends Entity
{
    protected $_accessible = [
        'title' => true,
        'pages' => true,
        'publish_year' => true,
        'author_id' => true,
        'author' => true,
        'rents' => true,
        'image_url' => true
    ];
}