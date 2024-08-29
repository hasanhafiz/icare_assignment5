<?php 

namespace App\Storage;
use PDO;
use PDOException;
use App\Config\Config;
use App\Classes\TransactionType;
use App\Storage\StorageInterface;
/** 
 * Singleton database class
 * 
 * That means, how many we may connected to db, it only call once.
 */

class DatabaseStorage implements StorageInterface {
    
    private static $_instance = null; 
        
    private $_pdo, 
            $_query, 
            $_error = false, 
            $_results, 
            $_count = 0;
    
    // constructor method to establish a connection
    private function __construct( $filename = 'users' )
    {
        try{
            $this->_pdo = new PDO( 'mysql:host=' . Config::get('db')['host'] . ';dbname=' . Config::get('db')['database'], Config::get('db')['username'] , Config::get('db')['password'] );
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "connected!";
        } catch(PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    public static function getInstance() {
        if ( ! isset(self::$_instance) ) {
            return self::$_instance = new DatabaseStorage();
        }
        return self::$_instance;
    }
    
    // build a query constructor like is
    // DB::getInstance()->query("SELECT username FROM users WHERE username='hasanhafiz' OR username = 'sakil' ");
    // below one is protected from fishing / sql injection
    // DB::getInstance()->query("SELECT username FROM users WHERE username=? OR username = ? ", ['hasanhafiz', 'sakil']);
    public function query( string $sql, $params = [] )
    {
        $this->_error = false;

        if ( $this->_query = $this->_pdo->prepare($sql)) {
            $postion = 1;
            if ( count($params) ) {
                foreach($params as $param) {
                    $this->_query->bindValue($postion, $param);
                    $postion++;
                }
            }
            
            if ( $this->_query->execute() ) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        } 
        return $this; // this is for method chaining. For example, $user = DB::getInstance(); $user->error()
    }
    
    public function error() {
        return $this->_error;
    }

    // $user = DB::getInstance()->get('users', ['username', '-', 'alex']);
        
    public function action( $action, $table, $where = [] ) {
        
        // $sql = "SELECT * FROM users WHERE username = 'Alex'";
        // $sql = "SELECT * FROM users WHERE username = ? ", array('Alex');
                
        $operators = ['=', '>', '>=', '<', '<='];
        
        if ( count($where) == 3 ) {
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];
            
            if ( in_array($operator, $operators) ) {
                $sql = "{$action} FROM `{$table}` WHERE {$field} {$operator} ?";                
                if ( $this->query( $sql, array($value) ) ) {
                    return $this;
                }
            }
        } 
    }
    
    public function get( $table, $where = []) {
        return $this->action("SELECT *", $table, $where);
    }
    public function results() {
        return $this->_results;
    }
    
    public function first() {
        return $this->results()[0];
    }
    
    public function insert( $table ='', $fields =[] ) {        
        if ( count($fields) ) {
            // $sql = INSERT INTO users('name','id','password') values ('$name','$id','$password');
            $field_keys = array_keys( $fields );
            // $field_values = array_values( $fields );
            
            $values = '';
            $i = 1;
            foreach($fields as $field) {
                $values .= '?';
                if ( $i < count($fields) ) {
                    $values .= ', ';
                }
                $i++;
            }
            
            $sql = "INSERT INTO {$table} (`" . implode( '`, `' , $field_keys ) .  "`) values ( {$values} )";
            
            if ( ! $this->query( $sql, $fields )->error() ){
                return true;
            }
        }
        return false;                
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
    
    public function load() {
        return [];
    }
        
}