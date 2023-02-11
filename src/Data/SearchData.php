<?php
namespace App\Data;

use App\Entity\Categorie;
use App\Entity\Ville;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Categorie[]
     */
    public $categories = [];

    /**
     * @var string
     */
    public $ville='';
}
