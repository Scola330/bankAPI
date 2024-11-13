<?php 
require_once('Route.php');
require_once('classes/Account.php');
require_once('classes/User.php');
require_once('classes/hash_data.php');

$db = new mysqli('localhost', 'root', '', 'banco_de_skibidi');
$db->set_charset('utf8');

use Steampixel\Route;
use BankAPI\Account;
use BankAPI\User;
use BankAPI\hash_data;


if ($db->connect_errno) {
    die('Failed to connect to MySQL: ' . $db->connect_error);
}
Route::add('/', function() {
    echo 'Working';
  });

  Route::add('/login', function() use($db) {
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    $ip = $_SERVER['REMOTE_ADDR'];
    try{
      $id = User::login($data['user.email'], $data['user.password'], $db);
      $token = hash_data::new($ip, $id, $db);
      header(('Content-Type: application/json'));
      echo json_encode(['token' => $token]);
    } catch (Exception $e) {
      header('HTTP/1.1 401 Unauthorized');
      echo json_encode(['error' => 'Invalid username or password']);
      return;
    }
  }, 'post');

  Route::add('/account/details', function() use($db){
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    $token = $dataArray['token'];
    if(!hash_data::check_hash($token, $_SERVER['REMOTE_ADDR'], $db)){
      header('HTTP/1.1 401 Unauthorized');
      echo json_encode(['error' => 'Invalid token']);
      return;
    }
    $userId = hash_data::getUserId($token, $db);
    $accountNo = Account::getAccountNo($userId, $db);
    $account = Account::getAccount($accountNo, $db);
    header(('Content-Type: application/json'));
    return json_encode($account->getArray());
  }, 'post');

Route::add('/account/([0-9]*)', function($accountNo) use($db){
    $account = Account::getAccountByNo($accountNo, $db);
    if (!is_object($account)) {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Account not found']);
        return;
    }
    $account = Account::getAccount($accountNo, $db);
    header(('Content-Type: application/json'));
    return json_encode($account->getArray());
});

Route::run('/bankAPI');

$db->close();
?>