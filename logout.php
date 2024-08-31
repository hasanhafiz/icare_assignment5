<?php 
session_start();

require_once 'vendor/autoload.php';

use App\Classes\User;
use App\Config\Config;
use App\Classes\FileType;
use App\Helpers\Redirect;
use App\Storage\FileStorage;
use App\Storage\DatabaseStorage;

// determine the storage type
$storage = Config::get('storage_type') === 'file' ? new FileStorage( FileType::USERS ) : new DatabaseStorage( FileType::USERS );

// logout
$user = new User($storage);
$user->logout();

// redirect
Redirect::to('index.php');