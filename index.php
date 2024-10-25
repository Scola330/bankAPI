<?php 
require_once('Route.php');
require_once('classes/Account.php');
require_once('classes/User.php');
require_once('classes/hash_data.php');
use Steampixel\Route;
use BankAPI\Account;
$db = new mysqli('localhost', 'root', '', 'banco_de_skibidi');
$db->set_charset('utf8');
if ($db->connect_errno) {
    die('Failed to connect to MySQL: ' . $db->connect_error);
}
Route::add('/', function() {
    echo 'Working';
  });

  Route::add('/login', function() {
  return var_dump($_POST);
  }, 'post');

Route::add('/account/([0-9]*)', function($accountNo) use($db){
    $account = Account::getAccountNo($accountNo, $db);
    header(('Content-Type: application/json'));
    return json_encode($account->getArray());   
});

Route::run('/bankAPI');

$db->close();
?>