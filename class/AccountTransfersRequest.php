<?php
// Path: class/AccountTransfersRequest.php
namespace BankAPI;

use mysqli;
use Exception;

class AccountTransfersRequest {
    private $accountNo;
    private $source_usser_id;
    private $destination_usser_id;
    private $timestamp;
    private $transfer_amount;

    public function __construct($accountNo, $source_usser_id, $destination_usser_id, $timestamp, $transfer_amount) {
        $this->accountNo = $accountNo;
        $this->source_usser_id = $source_usser_id;
        $this->destination_usser_id = $destination_usser_id;
        $this->timestamp = $timestamp;
        $this->transfer_amount = $transfer_amount;
    }

    public static function getAccountNo(int $accountNo, mysqli $db) : int {
        $sql = "SELECT id_account FROM user WHERE id_token = ?";
        $query = $db->prepare($sql);
        $query->bind_param("i", $accountNo);
        $query->execute();
        $result = $query->get_result();
        $historyList = $result->fetch_assoc();
        return $historyList['id_account'];
    }

    public function getHistory(int $accountNo, mysqli $db) : AccountTransfersRequest {
        $sql = "SELECT transfer_usser.ID, transfer_user.source_usser, transfer_usser.target_usser, transfer_usser.timestamp, transfer_usser.amount_transfer FROM transfer_usser WHERE transfer_usser.source_usser = ?";
        $query = $db->prepare($sql);
        $query->bind_param('i', $accountNo);
        $query->execute();
        $History = $query->get_result()->fetch_assoc();
        return new AccountTransfersRequest($History['ID'], $History['source_usser'], $History['target_usser'], $History['timestamp'], $History['amount_transfer']);
    }

    public function getArray() : array {
        return [
            'ID' => $this->accountNo,
            'source_usser_id' => $this->source_usser_id,
            'destination_usser_id' => $this->destination_usser_id,
            'timestamp' => $this->timestamp,
            'transfer_amount' => $this->transfer_amount
        ];
    }

    public static function getTransferHistory($accountNo, $db) {
        $result = $db->query("SELECT transfer_usser.ID, transfer_usser.source_usser, transfer_usser.target_usser, transfer_usser.timestamp, transfer_usser.amount_transfer FROM transfer_usser WHERE transfer_usser.source_usser = $accountNo OR transfer_usser.target_usser = $accountNo");
        if ($result->num_rows > 0) {
            $transferHistoryList = [];
            while ($transferHistoryData = $result->fetch_assoc()) {
                $accountHistory = new AccountTransfersRequest(
                    $transferHistoryData['ID'],
                    $transferHistoryData['source_usser'],
                    $transferHistoryData['target_usser'],
                    $transferHistoryData['timestamp'],
                    $transferHistoryData['amount_transfer']
                );
                $transferHistoryList[] = $accountHistory->getArray();
            }
            return json_encode($transferHistoryList);
        } else {
            throw new Exception('Account not found');
        }
    }
}
?>