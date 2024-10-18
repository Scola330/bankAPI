<?php
namespace BankAPI;

use mysqli;

class Account
{
    private $accountNo;
    private $amount;
    private $name;

    public function __construct($accountNo, $amount, $name)
    {
        $this->accountNo = $accountNo;
        $this->amount = $amount;
        $this->name = $name;
    }

    public static function getAccountNo(int $accountNo, mysqli $db) : Account
    {
        $result = $db->query("SELECT user.id_account ,  konto.money_value , konto.money_type, user.email FROM user , konto WHERE user.id_account = $accountNo AND konto.ID = user.id_account");
        $account = $result->fetch_assoc();
        $account = new Account($account['id_account'], $account['money_value'] . $account['money_type'], $account['email']);
        return $account;
    }
    public function getArray() : array
    { 
        $array = [
            'user.id_account' => $this->accountNo,
            'konto.money_value' => $this->amount,
            'konto.money_type' => $this->amount,
            'user.email' => $this->name
        ];
        return $array;
    }
}

?>