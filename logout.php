<?php 
session_start();

require_once 'vendor/autoload.php';
use App\User;
use App\Redirect;

$user = new User();
$user->logout();

Redirect::to('index.php');