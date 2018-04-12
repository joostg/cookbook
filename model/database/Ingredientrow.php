<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Ingredientrow extends Model
{
    protected $table = 'ingredientrows';

    public function recipe()
    {
        return $this->belongsTo('model\database\Recipe');
    }

    public function ingredient()
    {
        return $this->belongsTo('model\database\Ingredient');
    }

    public function quantity()
    {
        return $this->belongsTo('model\database\Quantity');
    }
}