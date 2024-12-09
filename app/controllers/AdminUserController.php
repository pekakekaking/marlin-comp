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
        echo $this->templates->render('create_user',['auth'=>$this->auth]);
    }

    public function storeUser()
    {
        $username = $_POST['username'];
        $work = $_POST['work'];
        $phoneNumber = $_POST['phone_number'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $status = $_POST['status'];
        $vk = $_POST['vk'];
        $telegram = $_POST['telegram'];
        $instagram = $_POST['instagram'];
        $img=$_FILES['image'];

        try {
            $userId = $this->auth->register($email, $password, $username, function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
                $this->auth->confirmEmail($selector, $token);

            });

            echo 'We have signed up a new user with the ID ' . $userId;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header('Location: /create_user');
            die();
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header('Location: /create_user');
            die();
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header('Location: /create_user');
            die();
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /create_user');
            die();
        }
        $db = new QueryBuilder();

        $data = [
            'user_id' => $userId,
            'work' => $work,
            'phone' => $phoneNumber,
            'address' => $address,
            'status' => $status,
            'vk' => $vk,
            'telegram' => $telegram,
            'instagram' => $instagram
        ];
        $db->insert($data, 'credentials');
        $db->uploadImage($img,$userId);
    }

    public function showUser($id)
    {
        $db = new QueryBuilder();
        $user = $db->findOne($id,'users');
        $credentials=$db->findRelation($id,'credentials','user_id');
//        $name=$user['username'];
//        $work=$user['work'];
//        $phoneNumber=$user['phone_number'];
//        $address=$user['address'];

        echo $this->templates->render('edit', ['user' => $user, 'credentials' => $credentials,'auth'=>$this->auth]);
    }

    public function updateUser()
    {
        $db = new QueryBuilder();
        $data = [
            'work' => $_POST['work'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
        ];
        $db->update(['username'=>$_POST['username']],$_GET['id'],'users');
        $db->update($data,$_GET['joinid'],'credentials');
        header('Location: /home');
    }
    public function showSecurity($id)
    {
        $db = new QueryBuilder();
        $user = $db->findOne($id,'users');
        echo $this->templates->render('security', ['user' => $user,'auth'=>$this->auth]);
    }
    public function updateSecurity()
    {
        $db = new QueryBuilder();
        $data = [
          'email' => $_POST['email'],
        ];

        try {
            $this->auth->admin()->changePasswordForUserById($_GET['id'], $_POST['password']);
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown ID');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }


        $db->update($data,$_GET['id'],'users');
        header('Location: /home');
    }
    public function showStatus($id)
    {
        $db = new QueryBuilder();
        $user = $db->findRelation($id,'credentials','user_id');
        echo $this->templates->render('status', ['user' => $user,'auth'=>$this->auth]);
    }
    public function updateStatus()
    {
        $db = new QueryBuilder();
        $data = [
            'status' => $_POST['status'],
        ];
        $db->update($data,$_GET['id'],'credentials');
        header('Location: /home');
    }
    public function showMedia($id)
    {
        $db = new QueryBuilder();
        $user = $db->findRelation($id,'credentials','user_id');
        echo $this->templates->render('media', ['user' => $user,'auth'=>$this->auth]);
    }
    public function updateMedia()
    {
        $db = new QueryBuilder();
        $id = $_GET['id'];
        $db->uploadImage($_FILES['image'],$id);
        header('Location: /home');
    }

    public function deleteUser($id)
    {
        $db = new QueryBuilder();
        $db->delete($id,'users');
        header('Location: /home');
    }
}