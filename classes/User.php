<?php
namespace BankAPI;

use mysqli;
use Exception;

class Login{
    public static function getHash(string $email, string $password, mysqli $db) : Login
    {
        $result = "SELECT user.email , hash_data.account_hash, user.id_token FROM user, hash_data WHERE user.email = ?";
        $query = $db->prepare($result);
        $query -> bind_param('s', $email);
        $query -> execute();
        $result = $query->get_result();
        if($result->num_rows == 0){
            throw new Exception('Invalid password or email');
        }else{
            $user = $result->fetch_assoc();
            $id = $user['id_token'];
            $hash = $user['account_hash'];
        }
        if(password_verify($password, $hash)){
            return $id;
        }
        else{
            throw new Exception('Invalid password or email');
        }
    }
}

