<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';

    public function image()
    {
        return $this->belongsTo('model\database\Image');
    }

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