<?php

namespace BankAPI;

class AccountTransfersResponse {
    private $history;
    private $error;

    public function __construct() {
        $this->error = "";
    }

    public function GetJson() {
        $array = array();
        $array['history'] = $this->history;
        $array['error'] = $this->error;
        return json_encode($array);
    }

    public function setHistory(array $history) {
        $this->history = $history;
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