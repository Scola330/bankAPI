//*Base URL
The base URL for the API is:

bash

http://localhost/bankAPI/

Routes
1. Check API Health
Endpoint: /
Method: GET
Description: Simple health check endpoint to verify the API is working.
Response:
200 OK:
json

"Working"
2. User Login
Endpoint: /login

Method: POST

Description: Allows a user to login using their email and password. Upon successful login, a token is generated.

Request Body:

json

{
  "user": {
    "email": "user@example.com",
    "password": "yourpassword"
  }
}
email: The user's email.
password: The user's password.
Response:

200 OK (If login is successful):
json

{
  "token": "generated_token_here"
}
401 Unauthorized (If login fails, invalid username or password):
json

{
  "error": "Invalid username or password"
}
3. Get Account Details
Endpoint: /account/details

Method: POST

Description: Retrieves the account details of the user based on the provided token. The token is used to authenticate the request.

Request Body:

json

{
  "token": "user_token_here"
}
token: The authentication token received after login.
Response:

200 OK (If account details are found):
json

{
  "user": {
    "id_account": 123,
    "email": "user@example.com"
  },
  "konto": {
    "money_value": 1000.50,
    "money_type": "USD"
  }
}
401 Unauthorized (If the token is invalid or expired):
json

{
  "error": "Invalid token"
}
4. Get Account by Account Number
Endpoint: /account/{accountNo}

Method: GET

Description: Retrieves the account details based on the provided account number.

Request Parameters:

accountNo: The account number of the user (integer).
Response:

200 OK (If account is found):
json

{
  "user": {
    "id_account": 123,
    "email": "user@example.com"
  },
  "konto": {
    "money_value": 1000.50,
    "money_type": "USD"
  }
}
404 Not Found (If account is not found):
json

{
  "error": "Account not found"
}
Authentication and Authorization
Token-based authentication: After a successful login, the API generates a token that must be passed in the request body (for /account/details) to authenticate the user and fetch account details.
Token validity: If the token is invalid or expired, the server will respond with a 401 Unauthorized status.
Error Handling
The API provides standard error handling with the following common status codes:

400 Bad Request: This could be due to malformed request or missing parameters.
401 Unauthorized: Occurs when the provided token is invalid or expired.
404 Not Found: When the requested resource (account) is not found in the database.
500 Internal Server Error: If there’s an issue on the server side (e.g., database connection issues).
Example Workflows
Login Flow:
POST to /login with user credentials:

Request Body:
json

{
  "user": {
    "email": "user@example.com",
    "password": "yourpassword"
  }
}
On success, the server returns:

json

{
  "token": "generated_token_here"
}
Use the received token to access account details:

POST to /account/details with the token:
json

{
  "token": "generated_token_here"
}
On success, the server returns:

json

{
  "user": {
    "id_account": 123,
    "email": "user@example.com"
  },
  "konto": {
    "money_value": 1000.50,
    "money_type": "USD"
  }
}
Account Fetch Flow by Account Number:
GET to /account/{accountNo} with an account number:
Example URL: /account/123
On success, the server returns account details:
json

{
  "user": {
    "id_account": 123,
    "email": "user@example.com"
  },
  "konto": {
    "money_value": 1000.50,
    "money_type": "USD"
  }
}
If the account is not found, the server returns:
json

{
  "error": "Account not found"
}
Database Schema
The database uses a few tables for user authentication and account management:

user table:

id_account (Primary key, INT): Unique identifier for each user account.
email (VARCHAR): User email.
password (VARCHAR): User's hashed password.
id_token (INT): Unique identifier for user session token.
konto table:

id_account (INT): Foreign key linking to the user table.
money_value (DECIMAL): The balance of the account.
money_type (VARCHAR): The currency type (e.g., USD, EUR).
hash_data table:

account_hash (VARCHAR): Token used for authentication.
ip (VARCHAR): IP address from which the login attempt originated.
user_number (INT): The user ID associated with the token.
Conclusion
This API provides secure user login, token-based authentication, and the ability to retrieve detailed account information using a RESTful approach. Ensure to manage and secure the authentication tokens properly. The API follows a simple structure, and all responses are provided in JSON format for easy integration with front-end applications. *//



