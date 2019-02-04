<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['name'];

    public function recipe()
    {
        return $this->belongsToMany('model\database\Recipe');
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