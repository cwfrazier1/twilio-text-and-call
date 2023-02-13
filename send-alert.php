<?php

// Connect to the MariaDB database
$servername = "localhost";
$username = "yourUsername";
$password = "yourPassword";
$dbname = "cwfrazier";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the phone numbers from the emergencyContacts table
$sql = "SELECT phoneNumber FROM emergencyContacts";
$result = $conn->query($sql);

// Load the Twilio library
require_once 'twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'yourAccountSid';
$auth_token = 'yourAuthToken';

// Initialize the Twilio client
$client = new Client($account_sid, $auth_token);

if ($result->num_rows > 0) {
    // Loop through each phone number
    while($row = $result->fetch_assoc()) {
        $phoneNumber = $row["phoneNumber"];

        // Send a text message to the phone number
        $client->messages->create(
            $phoneNumber,
            array(
                'from' => 'yourTwilioNumber',
                'body' => 'Chester may need help'
            )
        );

        // Make a call to the phone number
        $call = $client->calls->create(
            $phoneNumber,
            'yourTwilioNumber',
            array(
                "twiml" => '<Response><Say>Chester may need help</Say></Response>'
            )
        );
    }
} else {
    echo "0 results";
}

$conn->close();

?>
