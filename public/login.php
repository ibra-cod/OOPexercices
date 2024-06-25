<?php

use App\Auth;
use App\Database;
use App\FormValidator;

require '../vendor/autoload.php';

$auth = Database::getAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    
        $auth = Database::getAuth()->login($_POST['username'], $_POST['username']);
    
    dump($user);
    if ($user) {
            header('Location: index.php?login=1');
            exit();
    }
}
// if ($auth->user() !== null) {
//     header('Location: index.php');
//     exit();
// }git 




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="p-4">
 

    <form action="" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Pseudo">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Mot de passe">
        </div>
        <button name="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <
</body>
</html>