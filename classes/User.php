<?php
namespace BankAPI;

use mysqli;
use Exception;

class User{
    static function login(string $email, string $password, mysqli $db) : int
    {
        // Prepare the SQL statement
        $sql = "SELECT user.ID, user.email, user.password, hash_data.account_hash, user.id_token, hash_data.ID 
                FROM user 
                JOIN hash_data ON user.id_token = hash_data.ID 
                WHERE user.email = ?";
        $query = $db->prepare($sql);
        $query->bind_param('s', $email);
        $query->execute();
        
        // Get the result
        $result = $query->get_result();
        
        // Check if user exists
        if($result->num_rows == 0){
            throw new Exception('Invalid password or email');
        } else {
            $user = $result->fetch_assoc();
            $id = $user['ID'];
            $hash = $user['password'];
        
        
        // Verify the password
        if(password_verify($password, $hash)){
            return $id;
        } else {
            throw new Exception('Invalid password or email');
        }
        }
    }
}
?>