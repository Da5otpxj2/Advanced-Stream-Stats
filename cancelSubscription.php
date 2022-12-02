<?php

session_start();
if (isset($_SESSION["username"])) {
    $user_id = $_SESSION['user_id']; //Cart Total Price 
    session_write_close();
}
use Phppot\Subscription;
require_once __DIR__ . '/Model/Subscription.php';
$subscription = new Subscription();
$subscriptionInfo = $subscription->getMemberSubscription($user_id);

try{
        require_once 'lib/Braintree/lib/Braintree.php';

        $config = new Braintree\Configuration([
            'environment' => 'sandbox',
            'merchantId' => 'dck5trc2pyxg8wky',
            'publicKey' => 'bp2y5xjz75kpmqzx',
            'privateKey' => '333773333e7a5eabe86c22fadc64994d'
        ]);
        $gateway = new Braintree\Gateway($config);
        if(!empty($subscriptionInfo)){
            if(!empty($subscriptionInfo[0]['subscriptionId'])){ 
                $result = $gateway->subscription()->cancel($subscriptionInfo[0]['subscriptionId']); 
                if ($result->success)
                {
                    $subscription->deleteSubscription($user_id);
                    $response = '{"status": 1}';
                }
                else {
                    $msg = "Error processing cancelation:";
                    foreach($result->errors->deepAll() AS $error) {
                        $msg .= $error->code . ": " . $error->message . "\n";
                    }
                    $response = '{"status": 0,"msg":"'.$msg.'"}';
                }
            
            }
        }
    
        return $response;
    }
    catch(Exception $e){
        throw new Exception('Message: ' .$e->getMessage());
    }
?>
