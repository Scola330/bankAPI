<?php 

class Token{
    static function new(string $id, string $ip, mysqli $db ) : string
    {
        $hash = hash('sha256' . $id . $ip, time());
        $sql = "INSERT INTO hash_data (account_hash, id_token, ip) VALUES (?, ?, ?)";
        $query = $db->prepare($sql);
        $query -> bind_param('ss', $id, $ip);
        if(!$query->execute()){
            throw new Exception('Could not create hash');
        } else{
            return $hash;
        }
    }
    static function check_hash(string $token, string $ip, mysqli $db) : bool
    {
        $sql = "SELECT * FROM hash_data WHERE account_hash = ? AND ip = ?";
        $query = $db->prepare($sql);
        $query -> bind_param('ss', $token, $ip);
        $query -> execute();
        $result = $query->get_result();
        if($result->num_rows == 0){
            return false;
        }else{
            return true;
        }
    }
}

?>