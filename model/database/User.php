<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    public function auth_tokens()
    {
        return $this->hasMany('model\database\Auth_token');
    }

}