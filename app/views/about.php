<?php $this->layout('layout',['title'=>'User Profile']) ?>
<?php
use \Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;

?>
<h1>About page</h1>
<p>Hello,
    <?= flash()->display();
    $this->e($name)?></p>
