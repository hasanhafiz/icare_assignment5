<?php

namespace App\Storage;

interface StorageInterface
{
    // public function getAllCustomers();
    // public function saveCustomer($customer);
    // public function getCustomerById($id);
    // public function updateCustomer($id, $customer);
    // public function deleteCustomer($id);        
    // get all data
    public function error();    
    public function getTransactionsByUser( $user_id );
    public function getCurrentBalance( $user_id );    
    public function insert( $table = '', $fields =[] );
}
