<?php 
namespace App\Classes;

use App\Helpers\Hash;
use App\Config\Config;
use App\Helpers\Utils;
use App\Classes\Session;
use App\Storage\StorageInterface;

class User {
    private $_data = null,
            $_session_name = 'user',
            $_is_logged_in = false,
            $_filename = 'users',
            $_filepath = '',
            $_is_admin = false,
            $storage;
    
    /**
     * User could be an user id or email
     *
     */
    public function __construct( StorageInterface $storage )
    {
        $this->storage = $storage;
        $this->storage->setFilePath( FileType::USERS );
        if ( Session::exists( $this->_session_name ) ) {           
            $this->get( Session::get($this->_session_name) );
            $this->_is_logged_in = true;  
            if ( $this->data()->is_admin ) {
                $this->_is_admin = true;                
            }
        }
    }
    
    public function load()
    {
        return $this->storage->load();
    }
        
    public function create($fields = []): void
    {
        $this->storage->insert(FileType::USERS, $fields);
    }
    
    public function data() {
        return $this->_data;
    }
    
    public function login(string $email = null, string $password = null)
    {
        if ( $this->exists( $email ) ) {
            if ( $this->data()->password == Hash::make( $password ) ) {
                $this->_is_logged_in = true;
                Session::put( $this->_session_name , $this->data()->email );                                                                    
                return true;
            }
        }
        return false;
    }
    
    public function is_logged_in() {
        return $this->_is_logged_in;
    }
    
    public function logout()
    {
        Session::delete( $this->_session_name );
    }
    
    public function exists( $email ) {
        $users = $this->load();                
        foreach ($users as $user) {
            if ( $email == $user['email'] ) {
                $this->_data = (object) $user;
                return $this;
            }
        }
        return false;
    }
    
    public function get( $email ) {
        return $this->exists( $email );
    }
    
    public function getByID( $id ) {
        $users = $this->storage->load();        
        foreach ($users as $user) {
            if ( $id == $user['user_id'] ) {                     
                $this->_data = (object) $user;
                return $this;
            }
        }              
        return false;
    }   
    
    public function getByEmail( $email ) {
        $users = $this->storage->load();                
        foreach ($users as $user) {
            if ( $email == $user['email'] ) {
                $this->_data = (object) $user;
                return $this;
            }
        }       
        return false;
    }        
    
    public function isAdmin() {
        if ( $this->_data->is_admin ) {
            return true;
        } else {
            return false;
        }
    }    
}