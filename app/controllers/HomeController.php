<?php

namespace App\controllers;

use App\exceptions\AccountBlockedException;
use App\exceptions\NotEnoughCashException;
use App\QueryBuilder;
use Delight\Auth\Auth;
use Exception;
use JasonGrimes\Paginator;
use League\Plates\Engine;
use PDO;
use \Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;

class HomeController
{

    private $templates;
    private $auth;
    protected $qb;

    public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->templates = $engine;
        $this->auth=$auth;
        $this->qb=$qb;
    }

    public function index()
    {
        $db=$this->qb;
        $itemsPerPage = 3;
        $users = $db->getPage('users',$itemsPerPage);
        $credentials=[];
        foreach ($users as $user) {
            $cred=$db->findRelation($user['id'],'credentials','user_id');
            $cred[0]['id']=$user['id'];
            $credentials[]=$cred;

        }
        $totalItems=$this->qb;
        $currentPage = $_GET['page'] ?? 1;
        $urlPattern='?page=(:num)';

        $paginator=new Paginator(count($totalItems->getAll('users')), $itemsPerPage, $currentPage,$urlPattern);

        echo $this->templates->render('users', [
            'paginator'=>$paginator,
            'users' => $users,
            'credentials'=>$credentials,
            'auth'=>$this->auth
        ]);
    }

    public function about()
    {
        try {
            $userId = $this->auth->register('user@gmail.com', 'password', 'username', function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
            });

            echo 'We have signed up a new user with the ID ' . $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

    }
public function emailVerification()
{
    try {
        $this->auth->confirmEmail('x9RH0kuHEMRrAD_Y', 'M3na4wyzLvRICoLP');

        echo 'Email address has been verified';
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        die('Invalid token');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        die('Token expired');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        die('Email address already exists');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
}
public function login()
{
    try {
        $this->auth->login('user@gmail.com', 'password');

        echo 'User is logged in';
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        die('Wrong email address');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        die('Wrong password');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
        die('Email not verified');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
}

}
