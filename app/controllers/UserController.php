<?php

namespace App\controllers;
if (!session_id()) {
    session_start();
}

use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use Faker\Factory;
use \Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;


use App\QueryBuilder;
use League\Plates\Engine;
use PDO;

class UserController
{
    protected $auth;
    protected $templates;
    protected $db;
    protected $qb;

    public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->templates = $engine;
        $this->auth = $auth;
        $this->qb = $qb;
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
        echo $this->templates->render('page_register', ['auth' => $this->auth]);
    }

    public function login()
    {
        try {
            $this->auth->login($_POST['email'], $_POST['password']);
            header('Location:/home');
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
            header('Location: /show_login');
            die();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
            header('Location: /show_login');
            die();
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
            header('Location: /show_login');
            die();
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /show_login');
            die();
        }
    }

    public function store()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = explode('@', $_POST['email'])[0];
        try {
            $userId = $this->auth->register($email, $password, $username, function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
                $this->auth->confirmEmail($selector, $token);

            });

            echo 'We have signed up a new user with the ID ' . $userId;

            $db = $this->qb;
            $db->insert(['user_id' => $userId], 'credentials');
            header('Location:/show_login');
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header('Location: /show_register');
            die();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header('Location: /show_register');
            die();
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header('Location: /show_register');
            die();
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /show_register');
            die();
        }
    }

    public function factory()
    {
        $db = $this->qb;


        $pdo = new PDO("mysql:host=127.0.0.1;dbname=marlin", "marlin", "marlin");
        $queryFactory = new QueryFactory('mysql');

        $faker = Factory::create();
//        $insert=$queryFactory->newInsert();
//        $insert->into('users');
//        for ($i=13;$i<30;$i++) {
//            $insert->cols([
//                'email'=>$faker->safeEmail(),
//                'password'=>$faker->password(),
//                'username'=>$faker->userName(),
//                'status'=>0,
//                'verified'=>1,
//                'resettable'=>0,
//                'roles_mask'=>0,
//                'registered'=>1,
//                'last_login'=>0,
//                'force_logout'=>0,
//            ]);
//            $insert->addRow();
////        }
//
//        $sth=$pdo->prepare($insert->getStatement());
//        $sth->execute($insert->getBindValues());

        $insertCred = $queryFactory->newInsert();
        $insertCred->into('credentials');
        for ($i = 13; $i < 80; $i++) {
            $insertCred->cols([
                'user_id' => $i,
                'work' => $faker->word(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'status' => $faker->numberBetween(1, 3),
                'image' => null,
                'vk' => $faker->userName(),
                'telegram' => $faker->userName(),
                'instagram' => $faker->userName(),
            ]);
            $insertCred->addRow();
        }

        $sth = $pdo->prepare($insertCred->getStatement());
        $sth->execute($insertCred->getBindValues());
        return 'ok';
    }
}