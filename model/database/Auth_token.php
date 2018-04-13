<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Auth_token extends Model
{
    protected $table = 'auth_tokens';

    public function user()
    {
        return $this->belongsTo('model\database\User');
    }
}