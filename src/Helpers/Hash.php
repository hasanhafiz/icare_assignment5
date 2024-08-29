<?php 

namespace App\Helpers;

class Hash {
    
    public static function make( string $string, string $salt = '')
    {
        return hash( 'sha256', $string . $salt, false );
    }
    
    public static function salt(int $length = 12): string
    {
        $length = $length / 2 ;
        $bytes = random_bytes( $length );
        // bin2hex generates randdom chars as double of the length. So above, we divided length into 2
        return bin2hex($bytes);                 
    }    
    
    public static function unique(): string     
    {
        return self::make( uniqid() );
    }
}