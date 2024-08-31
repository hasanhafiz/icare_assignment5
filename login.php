<?php
session_start();

use App\Classes\FileType;
use App\Classes\Session;
use App\Classes\User;
use App\Classes\Input;
use App\Config\Config;
use App\Helpers\Utils;
use App\Classes\Validate;
use App\Helpers\Redirect;
use App\Storage\FileStorage;
use App\Storage\DatabaseStorage;

require_once 'vendor/autoload.php';

// determine the storage type
$storage = Config::get('storage_type') === 'file' ? new FileStorage( FileType::USERS ) : new DatabaseStorage( FileType::USERS );

// Redirect user to dashboard 
$user = new User($storage);
if (Session::exists('user')) {
    Redirect::to('dashboard.php');
}

// validate & logged in if user exists and redirect to dashboard
if (Input::exists()) {
    
    $validate = new Validate();
    $validate->check($_POST, ['email', 'password']);
    if ($validate->passed()) {
        $user = new User($storage);
        $login = $user->login(Input::get('email'), Input::get('password'));
        if ($login) {
            Session::flash('success', 'You logged in successfully!');
            echo 'Success!';
            Redirect::to('dashboard.php');
        } else {
            Session::put('message', 'Sorry! Log in failed! Either username or password is invalid!');
        }
    }
}

?>
<!DOCTYPE html>
<html class="h-full bg-white" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont,
                'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans',
                'Helvetica Neue', sans-serif;
        }
    </style>

    <title>Sign-In To Your Account</title>
</head>

<body class="h-full bg-slate-100">
    <div class="flex flex-col justify-center min-h-full py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-center text-gray-900">Sign In To Your Account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="px-6 py-12 bg-white shadow sm:rounded-lg sm:px-12">              
              <?php if (Session::exists('message')) { ?>
                  <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"></path>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                    <?php echo Session::flash('message'); ?>
                    </div>
                </div>                    
              <?php } ?>
                
                <form class="space-y-6" action="login.php" method="POST">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 p-2 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full p-2 text-gray-900 border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-10 text-sm text-center text-gray-500">
                Don't have an account?
                <a href="register.php" class="font-semibold leading-6 text-emerald-600 hover:text-emerald-500">Register</a>
            </p>
        </div>
    </div>
</body>

</html>