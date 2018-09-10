<?php
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
$filename = './.env';
if (file_exists($filename)) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}
$msg = "Your IQOS MGM code is: %s \n
Please share this with the store staff at IQOS, High Street Kensington to receive your IQOS Starter Kit for £99.00";
$mgm = 'HSJASS'
// Your Account SID and Auth Token from twilio.com/console
$account_sid = getenv('TWILIO_ACCOUNT_SID'); 
$auth_token = getenv('TWILIO_AUTH_TOKEN');
// In production, these should be environment variables. E.g.:

// A Twilio number you own with SMS capabilities
$twilio_number = getenv('TWILIO_NUMBER');

$client = new Client($account_sid, $auth_token);


$errorMSG = "";
$data=array();
$fields = array('mobile');
foreach ($fields as $key => $field) {
    if (@empty($_POST[$field])) {
        $errorMSG.= $field." is required\n";
    } else {
        $data[$field] = $_POST[$field];
    }
}

// redirect to success page
if ($errorMSG == ""){
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $data['mobile'],
            array(
            'from' => $twilio_number,
            'body' => sprintf($msg, $mgm)
            )
        );
        echo json_encode(array("status"=>true, 'data'=> "Text message has been sent mgm code."));

}else {
    if($errorMSG == ""){
        echo json_encode(array("status"=>false, 'data'=> "Something went wrong :("));

    } else {
        echo json_encode(array("status"=>false, 'data'=> $errorMSG));

    } 
}

?>