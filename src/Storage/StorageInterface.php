<?php

namespace App\Storage;

interface StorageInterface
{     
    // get all data    
    public function error();    
    public function getTransactionsByUser( $user_id );
    public function getCurrentBalance( $user_id );    
    public function insert( $table = '', $fields =[] );    
    public function load();    
    public function setFilePath( $filename ); 
}
