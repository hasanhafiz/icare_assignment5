<?php

namespace App\Storage;

use App\Config\Config;
use mysqli;

class DatabaseStorage implements StorageInterface
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli(
            Config::get('db')['host'],
            Config::get('db')['username'],
            Config::get('db')['password'],
            Config::get('db')['database']
        );
    }

    public function getAllCustomers()
    {
        $result = $this->conn->query("SELECT * FROM customers");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function saveCustomer($customer)
    {
        $stmt = $this->conn->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $customer['name'], $customer['email'], password_hash($customer['password'], PASSWORD_BCRYPT));
        $stmt->execute();
    }

    public function getCustomerById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCustomer($id, $updatedCustomer)
    {
        $stmt = $this->conn->prepare("UPDATE customers SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $updatedCustomer['name'], $updatedCustomer['email'], $id);
        $stmt->execute();
    }

    public function deleteCustomer($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}