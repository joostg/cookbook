<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    public function ingredientrow()
    {
        return $this->hasMany('model\database\Ingredientrow');
    }
}