<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

use MongoDB\BSON\Unserializable;

class DNAEntity
{
    public $data = '';
    public $columns = 0;
    public $rows = 0;
}