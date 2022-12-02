<?php
    use Phppot\Order;
    use Phppot\Member;
    require_once __DIR__ . '/Model/Member.php';

    session_start();
    $subscription_type = $_SESSION['subscription_type'];  
    $user_id = $_SESSION['user_id']; 
    
    $member = new Member();
    $memberInfo = $member->getMemberById($user_id)[0]; 
    
    if($subscription_type == 1){
        $price = 1.00;
    }elseif($subscription_type == 2){
        $price = 12.99;
    }elseif($subscription_type == 3){
        $price = 49.99;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" )
    {   
        
        $card_number=str_replace("+","",$_POST['card_number']);
        $card_name=$_POST['card_number'];
        $expiry_month=$_POST['expiry_month'];
        $expiry_year=$_POST['expiry_year'];
        $cvv=$_POST['cvv'];
        $expirationDate=$expiry_month.'/'.$expiry_year;

        try{
            require_once 'lib/Braintree/lib/Braintree.php';

            // or like this:
            $config = new Braintree\Configuration([
                'environment' => 'sandbox',
                'merchantId' => 'dck5trc2pyxg8wky',
                'publicKey' => 'bp2y5xjz75kpmqzx',
                'privateKey' => '333773333e7a5eabe86c22fadc64994d'
            ]);
            $gateway = new Braintree\Gateway($config);

            if(empty($memberInfo['paymentMethodToken'])){
                $customer = $gateway->customer()->create([
                    'id' => $memberInfo['id'],
                    'firstName' => $memberInfo['username'],
                    'email' => $memberInfo['email'],
                    'creditCard' => array(
                        'number' => $card_number,
                        'cardholderName' => $card_name,
                        'expirationDate' => $expirationDate,
                        'cvv' => $cvv
                    )
                ]);

                if ($customer->success) {
                    $member->updateMemberPaymentToken($user_id, $customer->customer->paymentMethods[0]->token); 
                    $memberInfo['paymentMethodToken'] = $customer->customer->paymentMethods[0]->token;
                } else {
                    $msg = "Error processing transaction:";
                    foreach($customer->errors->deepAll() AS $error) {
                        $msg .= $error->code . ": " . $error->message . "\n";
                    }
                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';
                    return $response;
                }
            }

            if($subscription_type == 1){
                $result = $gateway->transaction()->sale(array(
                    'amount' => $price,
                    'paymentMethodToken' => $memberInfo['paymentMethodToken']
                ));

                if ($result->success)
                {
                    if($result->transaction->id)
                    {
                        $transactionId=$result->transaction->id;
                        $subscriptionId='';
                        require_once __DIR__ . '/Model/Order.php';
                        $order = new Order();
                        $response = $order->updateOrder($subscription_type, $user_id, $price, $subscriptionId, $transactionId);
                    }
                }
                else if($result->transaction) {
                    $msg =  "Error processing transaction:";
                    $msg .= "\n  code: " . $result->transaction->processorResponseCode;
                    $msg .= "\n  text: " . $result->transaction->processorResponseText;

                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';

                } else {
                    $msg = "Error processing transaction:";
                    foreach($result->errors->deepAll() AS $error) {
                        $msg .= $error->code . ": " . $error->message . "\n";
                    }
                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';
                }
            
            }elseif($subscription_type == 2){

                $result = $gateway->subscription()->create([
                    'paymentMethodToken' => $memberInfo['paymentMethodToken'],
                    'planId' => 'PRO-MONTHLY'
                ]);

                if ($result->success)
                {
                    if($result->subscription->transaction->id)
                    {
                        $transactionId=$result->subscription->transaction->id;
                        $subscriptionId=$result->subscription->id;
                        require_once __DIR__ . '/Model/Order.php';
                        $order = new Order();
                        $response = $order->updateOrder($subscription_type, $user_id, $price, $subscriptionId, $transactionId);
                    }
                }
                else if($result->transaction) {
                    $msg =  "Error processing transaction:";
                    $msg .= "\n  code: " . $result->transaction->processorResponseCode;
                    $msg .= "\n  text: " . $result->transaction->processorResponseText;

                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';

                } else {
                    $msg = "Error processing transaction:";
                    foreach($result->errors->deepAll() AS $error) {
                        $msg .= $error->code . ": " . $error->message . "\n";
                    }
                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';
                }
            
            }elseif($subscription_type == 3){

                $result = $gateway->subscription()->create([
                    'paymentMethodToken' => $memberInfo['paymentMethodToken'],
                    'planId' => 'PRO-YEARLY'
                ]);

                if ($result->success)
                {
                    if($result->subscription->transaction->id)
                    {
                        $transactionId=$result->subscription->transaction->id;
                        $subscriptionId=$result->subscription->id;
                        require_once __DIR__ . '/Model/Order.php';
                        $order = new Order();
                        $response = $order->updateOrder($subscription_type, $user_id, $price, $subscriptionId, $transactionId);
                    }
                }
                else if($result->transaction) {
                    $msg =  "Error processing transaction:";
                    $msg .= "\n  code: " . $result->transaction->processorResponseCode;
                    $msg .= "\n  text: " . $result->transaction->processorResponseText;

                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';

                } else {
                    $msg = "Error processing transaction:";
                    foreach($result->errors->deepAll() AS $error) {
                        $msg .= $error->code . ": " . $error->message . "\n";
                    }
                    $response = '{"OrderStatus": [{"status":"0", "orderID":"0", "message" : "'.$msg.'"}]}';
                }
            
            }

            echo $response;
        }
        catch(Exception $e){
            throw new Exception('Message: ' .$e->getMessage());
        }
       
    }
?>