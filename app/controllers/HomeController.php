<?php

namespace App\controllers;

use App\exceptions\AccountBlockedException;
use App\exceptions\NotEnoughCashException;
use App\QueryBuilder;
use Exception;
use League\Plates\Engine;
use PDO;
use \Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;

class HomeController
{

    private $templates;
    private $auth;

    public function __construct()
    {
        $this->templates = new Engine('../app/views');
        $db=new PDO("mysql:host=127.0.0.1;dbname=marlin","marlin","marlin");
        $this->auth=new \Delight\Auth\Auth($db);
    }

    public function index($vars)
    {
        d($this->auth->getRoles());die;
        $db = new QueryBuilder();
        $users = $db->getAll('users');
        echo $this->templates->render('homepage', ['users' => $users]);
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

//        try {
//            $total = 10;
//            throw new AccountBlockedException("Account is blocked");
//            if ($vars['amount'] > $total) {
//                throw new NotEnoughCashException("not enough cash");
//            }
//
//        } catch (NotEnoughCashException $exception) {
//            flash()->error($exception->getMessage());
//        } catch (AccountBlockedException $exception) {
//            flash()->error($exception->getMessage());
//        }
      //  echo $this->templates->render('about', ['name' => 'Jonathan about page']);
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
//
//$db = new QueryBuilder();
//$db->update([
//    'email'=>'newuser2@gmail.com',
//    'password'=>'5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8'
//],17,'users');

//$user=$db->findOne(17,'users');
//var_dump($user);

//$db->delete(17,'users');