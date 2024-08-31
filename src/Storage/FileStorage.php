<?php 
namespace App\Storage;

use App\Config\Config;
use App\Helpers\Utils;
use App\Classes\TransactionType;
use App\Storage\StorageInterface;

class FileStorage implements StorageInterface {
    protected $_filepath = '',
            $_data = null,
            $_error = false,
            $filename = 'users';
            
    public function __construct( $filename = 'users' )
    {
        $this->filename = $filename;
        $this->_filepath = Config::get('file_storage_path') . '/' . $this->filename . '.json'; 
    }
    
    public function setFilePath( $filename ) {
        $this->_filepath = Config::get('file_storage_path') . '/' . $filename . '.json'; 
    }      
    
    public function load()
    {
        if ( file_exists( $this->_filepath ) ) {
            return json_decode( file_get_contents( $this->_filepath ), true );
        } else {
            return [];
        }
    }
        
    public function insert( $table = '', $fields = [] )
    {
        $this->_error = false;
        $this->filename = $table;
        $stored_data = $this->load();
        
        // Utils::pretty_print( $fields, 'fields**' );
        // Utils::pretty_print( $stored_data, '** stored_data' );
        // Utils::pretty_print( $this, '** this **' );
        
        array_push( $stored_data, $fields );
        $write_into_file = file_put_contents( $this->_filepath, json_encode( $stored_data, JSON_PRETTY_PRINT ) );     
        if ( $write_into_file !== FALSE ) {
            $this->_error = false;
            return $this;
        } else {
            $this->_error = true;
        }
    }
    
    public function data() {
        return $this->_data;
    }
    
    public function exists( $table, $id ) {
        $withdraws = $this->load();
        foreach ($withdraws as $withdraw) {
            if ( $withdraw == $withdraw['id'] ) {
                $this->_data = (object) $withdraw;
                return true;
            }
        }
        return false;
    }
        
    public function get( $table, $id ) {
        return $this->exists( $table , $id );
    }
    
    public function error() {
        return $this->_error;
    }
    
    public function getTransactionsByUser( $user_id ) {
        $all_transactions = $this->load();
        $filtered_data = [];
        foreach ($all_transactions as $transaction) {
            if ( $transaction['user_id'] == $user_id ) {
                $filtered_data[] = $transaction;
            }
        }
        return $filtered_data;
    }
    
    public function getCurrentBalance( $user_id ) {
        $deposited_amount = 0;
        $withdraw_amount = 0;
        $user_transactions = $this->getTransactionsByUser( $user_id );
        
        foreach ($user_transactions as $transaction) {
            if ( $transaction['transaction_type'] == TransactionType::DEPOSIT ) {
                $deposited_amount = $deposited_amount + $transaction['amount'];
            }
            if ( $transaction['transaction_type'] == TransactionType::WITHDRAW ) {
                $withdraw_amount = $withdraw_amount + $transaction['amount'];
            }            
        }
        return $deposited_amount - $withdraw_amount;
    }  
}