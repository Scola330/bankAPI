<?php
namespace BankAPI;

use mysqli;
use Exception;

class Account
{
    private $accountNo;
    private $amount;
    private $name;
    private $money_type;

    public function __construct($accountNo, $amount, $money_type, $name)
    {
        $this->accountNo = $accountNo;
        $this->amount = $amount;
        $this->money_type = $money_type;
        $this->name = $name;
    }

    public static function getAccountNo(int $userId, mysqli $db) : int
    {
        $sql = "SELECT id_account FROM user WHERE id_token = ?";
        $query = $db->prepare($sql);
        $query->bind_param("i", $userId);
        $query->execute();
        $result = $query->get_result();
        $account = $result->fetch_assoc();
        return $account['id_account'];
    }

    public static function getAccount(int $accountNo, mysqli $db) : Account
    {
        $stmt = $db->prepare("SELECT user.id_account, konto.money_value, konto.money_type, user.email FROM user, konto WHERE user.id_account = ?");
        $stmt->bind_param("i", $accountNo);
        $stmt->execute();
        $result = $stmt->get_result();
        $accountData = $result->fetch_assoc();
        $account = new Account($accountData['id_account'], $accountData['money_value'], $accountData['money_type'], $accountData['email']);
        return $account;
    }

    public function getArray() : array
    { 
        $accountData = [
                'user' => [
                    'id_account' => $this->accountNo,
                    'email' => $this->name
                ],
                'konto' => [
                    'money_value' => $this->amount,
                    'money_type' => $this->money_type
                ]
        ];
        return $accountData;
    }

    public static function getAccountByNo($accountNo, $db) {
        // Fetch account details from the database and return an Account object
        $result = $db->query("SELECT * FROM user, konto WHERE user.ID = '$accountNo'");
        if ($result->num_rows > 0) {
            $accountData = $result->fetch_assoc();
            $amount = $accountData['money_value'];
            $money_type = $accountData['money_type'];
            $name = $accountData['email'];
            $account = new Account($accountNo, $amount, $money_type, $name);
            return $account;
        } else {
            throw new Exception("Account not found");
        }
    }
}
?>