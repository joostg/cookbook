<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    public function recipe()
    {
        return $this->hasOne('model\database\Recipe');
    }
}