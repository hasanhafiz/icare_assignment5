<?php
/**
 * Configuration file to switch between file and database storage
 */
 
namespace App\Config;

class Config
{
    public static function get($key)
    {
        $config = [
            // 'file' for file-based storage, 'database' for MySQL storage
            'storage_type' => 'file', // Change to 'database' for DB storage
            'file_storage_path' => $_SERVER['DOCUMENT_ROOT'] . '/database',            
            // Database connection settings (used when storage_type is 'database')
            'db' => [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'hh_icare_assignment_6'
            ]
        ];
        
        return $config[$key] ?? null;
    }
}

echo Config::get('storage_type');