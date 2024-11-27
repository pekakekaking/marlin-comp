<?php

use App\QueryBuilder;
use League\Plates\Engine;
use PDO;

class UserController
{
    public function store()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $db = new QueryBuilder();
        $db->insert([
            'username' => $email,
            'password' => $password,
        ], 'users');
    }
}