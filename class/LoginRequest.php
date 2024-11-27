<?php 

// Path: class/LoginRequest.php
namespace BankAPI;
// Import the mysqli class
use mysqli;
// Import the Exception class
use Exception;
// Create a new class called LoginRequest
class LoginRequest {
    // Create private variables for the class
    private $email;
    private $password;
    // Create a constructor for the class
    public function __construct() {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
    public function getLogin() : string{
        return $this->email;
    }
    public function getPassword() : string{
        return $this->password;
    }
}
?>