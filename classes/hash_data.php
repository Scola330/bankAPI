<?php 
namespace BankAPI;
use mysqli;
use Exception;

class hash_data {
    static function new(string $ip, string $id, mysqli $db) : string {
        $hash = hash('sha256', $id . $ip . time());
        $sql = "INSERT INTO hash_data (account_hash , ip , user_number) VALUES (?, ?, ?)";
        $query = $db->prepare($sql);
        $query->bind_param('ssi', $hash, $ip, $id);
        if (!$query->execute()) {
            throw new Exception('Could not create hash');
        } else {
            return $hash;
        }
    }

    static function check_hash(string $token, string $ip, mysqli $db) : bool {
        $sql = "SELECT * FROM hash_data WHERE account_hash = ? AND ip = ?";
        $query = $db->prepare($sql);
        $query->bind_param('ss', $token, $ip);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows == 0) {
            return false;
        } else {
            return true;
        }
    }

    static function getUserId(string $token, mysqli $db) {
        $sql = "SELECT user_number FROM hash_data WHERE account_hash = ? ORDER BY id DESC LIMIT 1";
        $query = $db->prepare($sql);
        $query->bind_param('s', $token);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows == 0) {
            throw new Exception('Invalid token');
        } else {
            $row = $result->fetch_assoc();
            return $row['user_number'];
        }
    }
}