<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    protected $table = 'quantities';

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

    public function setQuery($queryString)
    {
        return $this->where(function ($query) use ($queryString) {
            $query
                ->where('name', $queryString)
                ->orWhere('plural', $queryString);
        });
    }
}