<?php 
namespace App\Classes;

use App\Transaction;
use App\Classes\User;
use App\Config\Config;
use App\Storage\FileStorage;
use App\Classes\TransactionType;
use App\Storage\DatabaseStorage;

class Validate {
    private $_passed = false;
    private $_erros = [];
    
    // initialize db
    public function __construct()
    {
    }
    
    public function check( $source, $items = [] ) {      
        // check required field
        if ( !empty( $items ) ) {
            foreach ($items as $key => $item) {                
                if ( ! Input::get($item)  ) {
                    $this->addError("{$item} is required!");
                }
            }
        }
        
        // check user already exists 
        if ( !empty( $source['email'] ) && !empty( $source['name'] )) {            
            $storage = Config::get('storage_type') === 'file' ? new FileStorage( FileType::TRANSACTIONS ) : new DatabaseStorage( FileType::TRANSACTIONS );
            $user = new User($storage);
            $user_email = $source['email'];
            if ( $user->exists( $user_email ) ) {
                $this->addError("{$user_email} is already exists!");
            }                                  
        }
        
        // check password mismatch
        if ( !empty( $source['password'] ) && !empty( $source['confirm_password'] ) ) {                                
            if ( $source['password'] !== $source['confirm_password']) {
                $this->addError("Password does not match!");
            }          
        }
        
        // check if withdraw amount is less than deposited amount
        if ( isset( $source['transaction_type'] ) && $source['transaction_type'] == TransactionType::WITHDRAW ) {
            // $transaction = new Transaction();
            // $user = new User;
            // $user_obj = $user->data();
            // $total_deposited_amount = $transaction->getCurrentBalance( $user_obj->user_id );     
            
            $total_deposited_amount = 0 ;   
            
            if ( $source['amount'] > $total_deposited_amount ) {
                $this->addError( 'Withdraw amount must be less than Deposited amount' );                
            }
        }
        
        // Transfer form validation
        if ( isset( $source['transaction_type'] ) && $source['transaction_type'] == TransactionType::TRANSFER ) {                        
            // $transaction = new Transaction();
            // $user = new User;
            // $user_obj = $user->data();
            // $total_deposited_amount = $transaction->getCurrentBalance( $user_obj->user_id );
            
            $total_deposited_amount = 0 ;
            // echo $total_deposited_amount;
            // check user exists or not
            
            // $user = new User;
            $user = $user->getByEmail( $source['email'] );
            // var_dump( $user );            
            if ( ! $user ) {
                $this->addError( 'Email not exists!' );                
            }
            
            if ( $source['amount'] > $total_deposited_amount ) {
                $this->addError( 'Transfer amount must be less than Deposited amount' );                
            }
        }        
        
        if ( empty( $this->_erros ) ) {
            $this->_passed = true;
        }
                
        return $this;        
    }
    
    public function passed() {
        return $this->_passed;
    }
    
    public function addError(string $error): void
    {
        $this->_erros[] = $error; 
    }
    
    public function errors() {
        return $this->_erros;
    }
}