<?php
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
$filename = './.env';
if (file_exists($filename)) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}
$msg = "Hi %s \n
Your IQOS MGM code is: %s \n
You can redeem this code to get £20 off the IQOS starter kit on http://iqos.co.uk or in any IQOS store\n
%s";
$mgm = 'HSJASS';
$marketing = 'You have opted in to marketing';
// Your Account SID and Auth Token from twilio.com/console
$account_sid = getenv('TWILIO_ACCOUNT_SID'); 
$auth_token = getenv('TWILIO_AUTH_TOKEN');
// In production, these should be environment variables. E.g.:

// A Twilio number you own with SMS capabilities
$twilio_number = getenv('TWILIO_NUMBER');

$client = new Client($account_sid, $auth_token);


$errorMSG = "";
$data=array();
$fields = array('mobile', 'marketing', 'name');
foreach ($fields as $key => $field) {
    if (@empty($_POST[$field])) {
        $errorMSG.= $field." is required\n";
    } else {
        $data[$field] = $_POST[$field];
    }
}

// redirect to success page
if ($errorMSG == ""){
    $marketing = $data['marketing']?$marketing:'';
    try {
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $data['mobile'],
            array(
            'from' => $twilio_number,
            'body' => sprintf($msg, $date['name'], $mgm ,$marketing)
            )
        );

        echo json_encode(array("status"=>true, 'data'=> "Text message has been sent mgm code."));

    } catch (Exception $e) {
        echo json_encode(array("status"=>false, 'data'=> 'Error: '.  $e->getMessage()));
    }
    
    

}else {
    if($errorMSG == ""){
        echo json_encode(array("status"=>false, 'data'=> "Something went wrong :("));

    } else {
        echo json_encode(array("status"=>false, 'data'=> $errorMSG));

    } 
}

?>