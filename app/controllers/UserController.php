<?php

namespace App\controllers;
if (!session_id()) {
    session_start();
}

use \Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;


use App\QueryBuilder;
use League\Plates\Engine;
use PDO;

class UserController
{
    protected $auth;
    protected $templates;
    public function __construct()
    {
        $this->templates = new Engine('../app/views');
        $db=new PDO("mysql:host=127.0.0.1;dbname=marlin","marlin","marlin");
        $this->auth=new \Delight\Auth\Auth($db);
    }
    public function showLogin()
    {
        echo $this->templates->render('page_login');
    }
    public function exitLogin()
    {
        $this->auth->logOut();
        $this->auth->destroySession();
        header('Location: /home');
    }
    public function showRegister()
    {
        echo $this->templates->render('page_register');
    }
    public function login()
    {
        try {
            $this->auth->login($_POST['email'], $_POST['password']);
            $qb=new QueryBuilder;
            $users=$qb->getAll('users');
//
//            echo 'User is logged in';
            echo $this->templates->render('users', ['users' => $users]);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
            header('Location: /login');
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
            header('Location: /login');
            die();
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
            header('Location: /login');
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /login');
            die();
        }
    }
    public function store()
    {
        $email=$_POST['email'];
        $password=$_POST['password'];
        $username=explode('@',$_POST['email'])[0];
        try {
            $userId = $this->auth->register($email, $password, $username, function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
                $this->auth->confirmEmail($selector, $token);
                header('Location: /show_login');
                die();

            });

            echo 'We have signed up a new user with the ID ' . $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header('Location: /show_register');
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header('Location: /show_register');
            die();
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header('Location: /show_register');
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /show_register');
            die();
        }
    }
}