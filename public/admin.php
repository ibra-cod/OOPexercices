<?php
use App\Auth;
use App\Database;
use App\FormValidator;

require '../vendor/autoload.php';

$auth = Database::getAuth()->role('admin');
?>

Réservé à l'admin