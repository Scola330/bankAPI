<?php 
// Path: class/AccountDetailsRequest.php
namespace BankAPI;
// Import the mysqli class
use mysqli;
// Import the Exception class
use Exception;
// Create a new class called AccountDetailsRequest
class AccountDetailsRequest {
    // Create private variables for the class
    private string $token;
    // Create a constructor for the class
    public function __construct() {
        // Get the token from the input
        $data = file_get_contents('php://input');
        // Decode the JSON data
        $data = json_decode($data, true);
        $this->token = $data['token'];
    }
    // Create a function to get the token
    public function getToken(){
        // Return the token
        return $this->token;
    }
}
?>