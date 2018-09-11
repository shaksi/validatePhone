<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';
use Twilio\Rest\Client;
$filename = './.env';
if (file_exists($filename)) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}
$conn =   dbConnect();

$msg = "Hi %s! \n
Thank you for taking the time to discover IQOS! We would like to send you your IQOS code.\n
Enter M80V3 to get £20 off when you purchase an IQOS starter kit online or in-store. \n
Just click here https://uk.iqos.com/refer-a-smoker-friend and enter this code at checkout. \n
IQOS is not risk-free and is for adult users only. Terms and conditions apply. Discount applies to a device only. PML privacy policy: https://uk.iqos.com/privacy-policy";
$mgm = 'M80V3';
$sql = "INSERT INTO prospects (fullname, mobile, marketing, privacy) VALUES ('%s', '%s', '%s', '%s')";
// Your Account SID and Auth Token from twilio.com/console
$account_sid = getenv('TWILIO_ACCOUNT_SID'); 
$auth_token = getenv('TWILIO_AUTH_TOKEN');
// In production, these should be environment variables. E.g.:

// A Twilio number you own with SMS capabilities
$twilio_number = getenv('TWILIO_NUMBER');

$client = new Client($account_sid, $auth_token);


$errorMSG = "";
$data=array();
$fields = array('mobile', 'marketing', 'name', 'privacy');
foreach ($fields as $key => $field) {
    if (@empty($_POST[$field])) {
        $errorMSG.= $field." is required\n";
    } else {
        $data[$field] = $_POST[$field];
    }
}

// redirect to success page
if ($errorMSG == ""){
    $marketing = $data['marketing']?"true":'false';
    $privacy = $data['privacy']?"true":'false';
    $name = ucwords(strtolower($data['name']));
    try {
        //insert into db
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $data['mobile'],
            array(
            'from' => $twilio_number,
            'body' => sprintf($msg, $name)
            )
        );
       $conn->query(sprintf($sql,$name, $data['mobile'], $marketing, $privacy));

        echo json_encode(array("status"=>true, 'data'=> "MGM code has been sent to customer."));

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