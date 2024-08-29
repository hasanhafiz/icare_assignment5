<?php
session_start();
require_once 'vendor/autoload.php';

use App\User;
use App\Input;
use App\Session;
use App\Validate;
use App\Transaction;
use App\TransactionType;

// Utils::pretty_print($_SESSION, 'session');

if (Session::exists('user')) {
  // load user
  $user = new User;
  $user_obj = $user->data();
  $deposit = new Transaction;
  
  // Utils::pretty_print($user, 'User Class');
  // Utils::pretty_print($user_obj, 'user');
}

if (Input::exists()) {
  
  $validate = new Validate();
  $validate->check($_POST, ['amount', 'email']);
  
  if ($validate->passed()) {
    $transaction = new Transaction;
    
    $user = new User;
    $user_obj = $user->data();
    $loggedin_uid = $user_obj->user_id;
    // $user->get(Session::get('user'));
    
    // first withdraw transfer amount from user account
    
    // get user object by email
    $user->exists( Input::get('email') );
    $customer = $user->data();
        
    $transaction->create([
      'id' => uniqid(),
      'user_id' => $loggedin_uid,
      'customer_id' => $customer->user_id,
      'amount' => Input::get('amount'),
      'transaction_date' => date('d M Y, H:i:s A'), // 29 Sep 2023, 09:25 AM
      'transaction_type' => TransactionType::WITHDRAW
    ]);
    
    // second, deposit to useremail address account

    $transaction->create([
      'id' => uniqid(),
      'user_id' => $customer->user_id,
      'customer_id' => $loggedin_uid,
      'amount' => Input::get('amount'),
      'transaction_date' => date('d M Y, H:i:s A'), // 29 Sep 2023, 09:25 AM
      'transaction_type' => TransactionType::DEPOSIT
    ]);        
    
    // echo 'Error status : <br />';
    // var_dump($transaction->error());
    // Utils::pretty_print( $withdraw->error() , 'withdraw obj after insert data.' );    
    // Utils::pretty_print( $withdraw );    
    
    // $user_data = $withdraw->get( Input::get('email') );
    // var_dump( $withdraw );
    if ($transaction->error() === false) {
      Session::put('success', 'Transfer successfull!');
    }
    
  }
  
  $errors = $validate->errors();
}
?>

<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Tailwindcss CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- AlpineJS CDN -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Inter Font -->
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

  <title>Transfer Balance</title>
</head>

<body class="h-full">
  <div class="min-h-full">
    <div class="bg-emerald-600 pb-32">
      <!-- Navigation -->
      <nav class="border-b border-emerald-300 border-opacity-25 bg-emerald-600" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 justify-between">
            <div class="flex items-center px-2 lg:px-0">
              <div class="hidden sm:block">
                <div class="flex space-x-4">
                  <!-- Current: "bg-emerald-700 text-white", Default: "text-white hover:bg-emerald-500 hover:bg-opacity-75" -->
                  <a href="dashboard.php" class="text-white hover:bg-emerald-500 hover:bg-opacity-75 rounded-md py-2 px-3 text-sm font-medium" aria-current="page">Dashboard</a>
                  <a href="deposit.php" class="text-white hover:bg-emerald-500 hover:bg-opacity-75 rounded-md py-2 px-3 text-sm font-medium">Deposit</a>
                  <a href="withdraw.php" class="text-white hover:bg-emerald-500 hover:bg-opacity-75 rounded-md py-2 px-3 text-sm font-medium">Withdraw</a>
                  <a href="transfer.php" class="bg-emerald-700 text-white rounded-md py-2 px-3 text-sm font-medium">Transfer</a>
                </div>
              </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex gap-2 sm:items-center">
              <!-- Profile dropdown -->
              <div class="relative ml-3" x-data="{ open: false }">
                <div>
                  <button @click="open = !open" type="button" class="flex rounded-full bg-white text-sm focus:outline-none" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <!-- <img
                        class="h-10 w-10 rounded-full"
                        src="https://avatars.githubusercontent.com/u/831997"
                        alt="Ahmed Shamim Hasan Shaon" /> -->
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                      <span title="<?php echo $user_obj->name;?>" class="font-medium leading-none text-emerald-700"><?php echo substr($user_obj->name, 0, 2);?></span>
                    </span>
                  </button>
                </div>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                  <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
                </div>
              </div>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
              <!-- Mobile menu button -->
              <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-emerald-100 hover:bg-emerald-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500" aria-controls="mobile-menu" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <!-- Icon when menu is closed -->
                <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

                <!-- Icon when menu is open -->
                <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div x-show="mobileMenuOpen" class="sm:hidden" id="mobile-menu">
          <div class="space-y-1 pt-2 pb-3">
            <a href="dashboard.php" class="text-white hover:bg-emerald-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium" aria-current="page">Dashboard </a>
            
             <a href="./deposit.php" class="text-white hover:bg-emerald-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium">Deposit </a>
            
             <a href="./withdraw.php" class="text-white hover:bg-emerald-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium">Withdraw </a>
              
             <a href="./transfer.php" class="bg-emerald-700 text-white block rounded-md py-2 px-3 text-base font-medium">Transfer</a>
          </div>
          <div class="border-t border-emerald-700 pb-3 pt-4">
            <div class="flex items-center px-5">
              <div class="flex-shrink-0">
                <!-- <img
                    class="h-10 w-10 rounded-full"
                    src="https://avatars.githubusercontent.com/u/831997"
                    alt="" /> -->
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                  <span class="font-medium leading-none text-emerald-700">AS</span>
                </span>
              </div>
              <div class="ml-3">
                <div class="text-base font-medium text-white">
                  Ahmed Shamim
                </div>
                <div class="text-sm font-medium text-emerald-300">
                  ahmed@shamim.com
                </div>
              </div>
              <button type="button" class="ml-auto flex-shrink-0 rounded-full bg-emerald-600 p-1 text-emerald-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600">
                <span class="sr-only">View notifications</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
              </button>
            </div>
            <div class="mt-3 space-y-1 px-2">
              <a href="logout.php" class="block rounded-md px-3 py-2 text-base font-medium text-white hover:bg-emerald-500 hover:bg-opacity-75">Sign out</a>
            </div>
          </div>
        </div>
      </nav>
      <header class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold tracking-tight text-white">
            Transfer Balance
          </h1>
        </div>
      </header>
    </div>

    <main class="-mt-32">
      <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg p-2">
          <!-- Current Balance Stat -->
          <dl class="mx-auto grid grid-cols-1 gap-px sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
              <dt class="text-sm font-medium leading-6 text-gray-500">
                Current Balance
              </dt>
              <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-gray-900">
              $<?php echo $deposit->getCurrentBalance( $user_obj->user_id ); ?>
              </dd>
            </div>
          </dl>

          <hr />
          <!-- Transfer Form -->
          <div class="sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <!-- Show error message -->
              <?php
              // $validate = new Validate();
              if (isset($errors)) {
                foreach ($errors as $error) { ?>
                  <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"></path>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                      <?php echo $error; ?>
                    </div>
                  </div>
              <?php
                }
              }
              ?>
              
              <?php 
              // show transfer message
              if ( Session::exists('success') ) {
                echo Session::flash('success');
              }
              ?>
              <div class="mt-4 text-sm text-gray-500">
                <form action="transfer.php" method="POST">
                  <!-- hidden field to hold transaction type -->
                  <input type="hidden" name="transaction_type" value="<?php echo TransactionType::TRANSFER; ?>">
                  <!-- Recipient's Email Input -->
                  <input type="email" name="email" id="email" class="block w-full ring-0 outline-none py-2 text-gray-800 border-b placeholder:text-gray-400 md:text-4xl" placeholder="Recipient's Email Address" required />

                  <!-- Amount -->
                  <div class="relative mt-4 md:mt-8">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-0">
                      <span class="text-gray-400 md:text-4xl">$</span>
                    </div>
                    <input type="number" name="amount" id="amount" class="block w-full ring-0 outline-none pl-4 py-2 md:pl-8 text-gray-800 border-b border-b-emerald-500 placeholder:text-gray-400 md:text-4xl" placeholder="0.00" required />
                  </div>

                  <!-- Submit Button -->
                  <div class="mt-5">
                    <button type="submit" class="w-full px-6 py-3.5 text-base font-medium text-white bg-emerald-600 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-emerald-300 rounded-lg md:text-xl text-center">
                      Proceed
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>