<?php 

// Path: class/AccountDetailsResponse.php
namespace BankAPI;
// Create a new class called AccountDetailsResponse
class AccountDetailsResponse {
    // Create private variables for the class
    private array $account;
    private string $error;
    // Create a constructor for the class
    public function __construct() {
        $this->error = "";
    }
    public function GetJson() {
        $array = array();
        $array['account'] = $this->account;
        $array['error'] = $this->error;
        return json_encode($array);
    }
    public function setAccount(array $account) {
        $this->account = $account;
    }
    public function setError(string $error) {
        $this->error = $error;
    }
    public function send(){
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