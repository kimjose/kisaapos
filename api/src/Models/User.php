<?php
namespace Infinitops\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $fillable = ["user_name", "first_name", "last_name", "email", "phone_number", "gender", "password", 'last_login'];

}