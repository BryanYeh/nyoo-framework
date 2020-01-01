<?php

namespace App\Models;

use App\Core\Model;

class PersonModel extends Model
{
    protected $table = 'person';
    protected $timestamp = false;

    public $id;
    public $name;
    public $age;
}
