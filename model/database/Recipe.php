<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';

    public function image()
    {
        return $this->hasOne('model\database\Image','image');
    }

    public function ingredientrow()
    {
        return $this->belongsToMany('model\database\Ingredientrow');
    }
}