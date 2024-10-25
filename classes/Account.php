<?php
namespace BankAPI;

use mysqli;

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
    public static function getAccountNo(int $accountNo, mysqli $db) : Account
    {
        $stmt = $db->prepare("SELECT user.id_account, konto.money_value, konto.money_type, user.email FROM user, konto WHERE user.id_account = ? AND konto.ID = user.id_account");
        $stmt->bind_param("i", $accountNo);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result->fetch_assoc();
        $account = new Account($account['id_account'], $account['money_value'], $account['email'], $account['money_type']);
        return $account;
    }
    public function getArray() : array
    { 
        $array = [
            $User = ['user.id_account' => $this->accountNo ,
            'user.email' => $this->name],
            $Konto = ['konto.money_value' => $this->amount,
            'konto.money_type' => $this->money_type]
            
        ];
        return $array;
    }
}

?>