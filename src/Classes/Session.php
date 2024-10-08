<?php 
namespace App\Classes;

class Session {
    public static function put( $name, $value ) {
        $_SESSION[$name] = $value;
    }
    
    public static function exists( $name ) {
        return isset( $_SESSION[$name] ) ? true : false;
    }
    
    public static function get( $name ) {
        return isset( $_SESSION[$name] ) ? $_SESSION[$name] : '';
    }
    
    public static function delete( $name ) {
        if ( self::exists( $name ) ) {
            unset( $_SESSION[$name] );
        }
    }
    
    public static function flash( $name, $message = '' ) {
        if ( self::exists( $name ) ) {
            $session = self::get( $name );
            self::delete( $name );
            return $session;            
        } else {
            self::put( $name, $message );
        }
    }
}