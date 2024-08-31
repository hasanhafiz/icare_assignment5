<?php 
namespace App\Classes;

use App\Helpers\Utils;
use App\Storage\StorageInterface;

class Transaction {
    private $_filepath = '',
            $_data = null,
            $_error = false,
            $filename = 'transactions',
            $storage;
            
    public function __construct( StorageInterface $storage )
    {
        $this->storage = $storage;
        $this->storage->setFilePath( FileType::TRANSACTIONS );        
        // Utils::pretty_print( $this->storage, __FILE__ );                                       
    }
    
    public function load()
    {
        return $this->storage->load();
    }
        
    public function create( $filename, $fields = [])
    {
        $this->storage->insert( $filename, $fields );    
    }
    
    public function data() {
        return $this->_data;
    }
    
    public function exists( $id ) {
        $withdraws = $this->load();
        foreach ($withdraws as $withdraw) {
            if ( $withdraw == $withdraw['id'] ) {
                $this->_data = (object) $withdraw;
                return true;
            }
        }
        return false;
    }
    
    public function get( $id ) {
        return $this->exists( $id );
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
       return $this->storage->getCurrentBalance( $user_id );       
    }    
}