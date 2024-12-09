<?php

use Aura\SqlQuery\QueryFactory;
use Faker\Factory;

$db=new \App\QueryBuilder();


$pdo = new PDO("mysql:host=127.0.0.1;dbname=marlin", "marlin", "marlin");
$queryFactory = new QueryFactory('mysql');

$faker = Factory::create();
$insert=$queryFactory->newInsert();
$insert->into('users');
for ($i=1;$i<30;$i++) {
    $insert->cols([
        'email'=>$faker->safeEmail(),
        'password'=>$faker->password(),
        'username'=>$faker->userName(),
        'status'=>0,
        'verified'=>1,
        'resettable'=>0,
        'roles_mask'=>0,
        'registered'=>1,
        'last_login'=>0,
        'force_logout'=>0,
    ]);
    $insert->addRow();
}

$sth=$pdo->prepare($insert->getStatement());
$sth->execute($insert->getBindValues());

$insertCred=$queryFactory->newInsert();
$insertCred->into('credentials');
for ($i=1;$i<30;$i++) {
    $insert->cols([
        'user_id'=>$i,
        'work'=>$faker->word(),
        'phone'=>$faker->phoneNumber(),
        'address'=>$faker->address(),
        'status'=>$faker->numberBetween(1,3),
        'image'=>$faker->image(),
        'vk'=>$faker->userName(),
        'telegram'=>$faker->userName(),
        'instagram'=>$faker->userName(),
    ]);
    $insert->addRow();
}

$sth=$pdo->prepare($insert->getStatement());
$sth->execute($insert->getBindValues());
