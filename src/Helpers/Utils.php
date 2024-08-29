<?php 
namespace App\Helpers;
class Utils {
    public static function escape( $string ): string {
        return htmlentities( $string, ENT_QUOTES, 'UTF-8');
    }
    
    public static function pretty_print( $data, $identifier = '' ) {
        echo '<pre>';
        echo "------------ $identifier ----------\n";
        print_r( $data );
        echo '</pre>';
    }    
}
