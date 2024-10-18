<?php 
require_once('Route.php');
require_once('Account.php');
require_once('hash_data.php');
use Steampixel\Route;
use BankAPI\Account;
require_once('Account.php');
$db = new mysqli('localhost', 'root', '', 'banco_de_skibidi');
$db->set_charset('utf8');
if ($db->connect_errno) {
    die('Failed to connect to MySQL: ' . $db->connect_error);
}
Route::add('/', function() {
    echo 'Hello world!';
  });

  Route::add('/login', function() {
  
  }, 'post');

Route::add('/account/([0-9]*)', function($accountNo) use($db){
    $account = Account::getAccountNo($accountNo, $db);
    header(('Content-Type: application/json'));
    return json_encode($account->getArray());   
});

Route::run('/bankAPI');

$db->close();
?>