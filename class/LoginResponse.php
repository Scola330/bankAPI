<?php 
// Path: class/LoginResponse.php
namespace BankAPI;

// Create a new class called LoginResponse
class LoginResponse {
    // Create private variables for the class
    private $token;
    private $error;
    // Create a constructor for the class
    public function __construct(string $token, string $error) {
        $this->token = $token;
        $this->error = $error;
    }
    public function GetJson() {
        $array = array();
        $array['token'] = $this->token;
        $array['error'] = $this->error;
        return json_encode($array);
    }
    // Create a function to send token
    public function send() {
        if ($this->error != "") {
            header('HTTP/1.1 401 Unauthorized');
        } else {
            header('HTTP/1.1 200 OK');
        }
        header('Content-Type: application/json');
        echo $this->GetJson();
    }
}

?>