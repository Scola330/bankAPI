<?php 

require_once('Route.php');
require_once('classes/Account.php');
require_once('classes/User.php');
require_once('classes/hash_data.php');
require_once('classes/Transfer.php');
require_once('class/LoginRequest.php');
require_once('class/LoginResponse.php');


// Create a new MySQL connection
$db = new mysqli('localhost', 'root', '', 'banco_de_skibidi');
$db->set_charset('utf8');

// Check if the connection was successful
use Steampixel\Route;
use BankAPI\Account;
use BankAPI\User;
use BankAPI\hash_data;
use BankAPI\Transfer;
use BankAPI\LoginRequest;
use BankAPI\LoginResponse;

// Check if the connection was successful
if ($db->connect_errno) {
  // If there is an error with the connection, stop the script and display an error
    die('Failed to connect to MySQL: ' . $db->connect_error);
}
// Define the routes
Route::add('/', function() {
  // This is the default route
    echo 'Working';
  });
// Define the routes
  Route::add('/login', function() use($db) {
    $request = new LoginRequest();
    // Try to log in the user
    try{
      // Call the login function from the User class
      $id = User::login($request->getLogin(), $request->getPassword(), $db);

    $ip = $_SERVER['REMOTE_ADDR'];
      // Generate a new token
      $token = hash_data::new($ip, $id, $db);
      // Send the token back to the user
      $response = new LoginResponse($token, "");
      $response->send();

    } catch (Exception $e) {
      $response = new LoginResponse("", $e->getMessage());
      return;
    }
  }, 'post');

  Route::add('/account/details', function() use($db){
    // Get the data from the request
    $data = file_get_contents('php://input');
    // Decode the JSON data
    $dataArray = json_decode($data, true);
    // Get the token from the data
    $token = $dataArray['token'];
    // Check if the token is valid
    if(!hash_data::check_hash($token, $_SERVER['REMOTE_ADDR'], $db)){
      // Return an error message
      header('HTTP/1.1 401 Unauthorized');
      // Return the error message as a JSON object
      echo json_encode(['error' => 'Invalid token']);
      // Stop the script
      return;
    }
    $userId = hash_data::getUserId($token, $db);
    // Get the account number for the user
    $accountNo = Account::getAccountNo($userId, $db);
    // Get the account details
    $account = Account::getAccount($accountNo, $db);
    // Return the account details as a JSON object
    header(('Content-Type: application/json'));
    // Return the account details as a JSON object
    return json_encode($account->getArray());
    // Return the account details as a JSON object
  }, 'post');

Route::add('/account/([0-9]*)', function($accountNo) use($db){
  // Get the account details
    $account = Account::getAccountByNo($accountNo, $db);
    // Check if the account exists
    if (!is_object($account)) {
      // Return an error message
        header('HTTP/1.1 404 Not Found');
        // Return the error message as a JSON object
        echo json_encode(['error' => 'Account not found']);
        // Stop the script
        return;
    }
    // Return the account details as a JSON object
    $account = Account::getAccount($accountNo, $db);
    // Return the account details as a JSON object
    header(('Content-Type: application/json'));
    // Return the account details as a JSON object
    return json_encode($account->getArray());
});

Route::add('/transfer/new', function() use($db){
  // Get the data from the request
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    // Get the token from the data
    $token = $dataArray['token'];
    // Check if the token is valid
    if(!hash_data::check_hash($token, $_SERVER['REMOTE_ADDR'], $db)){
      //  Return an error message
      header('HTTP/1.1 401 Unauthorized');
      echo json_encode(['error' => 'Invalid token']);
      return;
    }
    // Get the user ID
    $userId = hash_data::getUserId($token, $db);
    // Get the account number for the user
    $source = Account::getAccountNo($userId, $db);
    // Get the target account number
    $target = $dataArray['target'];
    // Get the amount to transfer
    $amount = $dataArray['amount'];
    // Try to make the transfer
    try{
      // Call the new function from the Transfer class
      Transfer::new($source, $target, $amount, $db);
      // Return a success message
      header(('Status: 200 OK'));
      // Return the success message as a JSON object
      echo json_encode(['status' => 'ok']);
      // If there is an error
    } catch (Exception $e) {
      // Return an error message
      header('HTTP/1.1 400 Bad Request');
      echo json_encode(['error' => $e->getMessage()]);
    }
  }, 'post');

Route::run('/bankAPI');

$db->close();
?>