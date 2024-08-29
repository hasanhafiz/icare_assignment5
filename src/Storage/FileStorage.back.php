<?php

namespace App\Storage;

use App\Config\Config;

class FileStorage implements StorageInterface
{
    private $filePath;
    
    public function __construct()
    {
        $this->filePath = Config::get('file_storage_path');
    }
    
    public function getAllCustomers()
    {
        return file_exists($this->filePath) ? unserialize(file_get_contents($this->filePath)) : [];
    }

    public function saveCustomer($customer)
    {
        $customers = $this->getAllCustomers();
        $customer['id'] = end($customers)['id'] + 1 ?? 1;
        $customers[] = $customer;
        file_put_contents($this->filePath, serialize($customers));
    }

    public function getCustomerById($id)
    {
        $customers = $this->getAllCustomers();
        foreach ($customers as $customer) {
            if ($customer['id'] == $id) {
                return $customer;
            }
        }
        return null;
    }

    public function updateCustomer($id, $updatedCustomer)
    {
        $customers = $this->getAllCustomers();
        foreach ($customers as &$customer) {
            if ($customer['id'] == $id) {
                $customer = array_merge($customer, $updatedCustomer);
                break;
            }
        }
        file_put_contents($this->filePath, serialize($customers));
    }
    
    public function deleteCustomer($id)
    {
        $customers = $this->getAllCustomers();
        $customers = array_filter($customers, fn($customer) => $customer['id'] != $id);
        file_put_contents($this->filePath, serialize($customers));
    }
}