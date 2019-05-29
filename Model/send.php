<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md

//require dirname(__DIR__,4) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';


use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure
// $sid    = "AC2dba542abd7a60ddc6c6f7a08b9ca383";
// $token  = "4e56773546e642561c1c8a1af33a628f";


/*We've verified 095614 19835
As a last resort, you can use this emergency code to access the account:

dahuxJCEQxJjYihviVX73gZK3tgOXAVwhcHvq9Ch*/

 
$sid    = "ACc5fa089b7cc58f153e4a4edfacc25a23";
$token  = "82829e6846c1ffe7383c6052f9b27445";
$twilio = new Client($sid, $token);
 
$message = $twilio->messages
                  ->create(
                      "whatsapp:+919561419835", // to
                      [
                               "from" => "whatsapp:+14155238886",
                               "body" => "Hi There!"
                           ]
                  );
 
print_r($message->sid);
