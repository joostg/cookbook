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

    public function updatedBy()
    {
        return $this->belongsTo('model\database\User','updated_by','id');
    }

    public function createdBy()
    {
        return $this->belongsTo('model\database\User','created_by','id');
    }
}