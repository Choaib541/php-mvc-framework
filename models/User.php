<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
    public static string $table = "users";
    public string $name;
    public string $email;
    public string $password;
    public string $birthday;

}