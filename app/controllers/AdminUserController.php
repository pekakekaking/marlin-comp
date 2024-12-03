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

class AdminUserController extends UserController
{

    public function createUser()
    {
        echo $this->templates->render('create_user');
    }

    public function storeUser()
    {
        $username=$_POST['username'];
        $work=$_POST['work'];
        $phoneNumber=$_POST['phone_number'];
        $address=$_POST['address'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $status=$_POST['status'];
        $vk=$_POST['vk'];
        $telegram=$_POST['telegram'];
        $instagram=$_POST['instagram'];

        try {
            $userId = $this->auth->register($email, $password, $username, function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
                $this->auth->confirmEmail($selector, $token);
                header('Location: /login');
                die();

            });

            echo 'We have signed up a new user with the ID ' . $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header('Location: /create_user');
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header('Location: /create_user');
            die();
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header('Location: /create_user');
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /create_user');
            die();
        }
        $db=new QueryBuilder();
        $data=[
            'user_id'=>$userId,
            'work'=>$work,
            'phone_number'=>$phoneNumber,
            'address'=>$address,
            'status'=>$status,
            'vk'=>$vk,
            'telegram'=>$telegram,
            'instagram'=>$instagram
            ];
        $db->insert($data,'credentials');
    }
    public function showUser($id)
    {
        $db=new QueryBuilder();
        $user=$db->findOne($id,'users');
        $name=$user['username'];
        $work=$user['work'];
        $phoneNumber=$user['phone_number'];
        $address=$user['address'];
    }

}