<?php
use App\App;
use App\Auth;
use App\Database;

require '../vendor/autoload.php';
$auth = Database::getAuth()->role('admin', 'user');
?>
this is for the user