<?php
    $session_price = $_SESSION['session_price']; //Cart Total Price  
    $session_user_id = $_SESSION['session_user_id']; //Cart Total Price 
    if($_SERVER["REQUEST_METHOD"] == "POST" )
    {
        $card_number=str_replace("+","",$_POST['card_number']);
        $card_name=$_POST['card_number'];
        $expiry_month=$_POST['expiry_month'];
        $expiry_year=$_POST['expiry_year'];
        $cvv=$_POST['cvv'];
        $expirationDate=$expiry_month.'/'.$expiry_year;

        require_once 'braintree/Braintree.php';
        Braintree_Configuration::environment('sandbox'); // Change to production
        Braintree_Configuration::merchantId('Merchant_ID');
        Braintree_Configuration::publicKey('Public_Key');
        Braintree_Configuration::privateKey('Private_Key');
        //BrainTree payment process
        $result = Braintree_Transaction::sale(array(
        'amount' => $price,
        'creditCard' => array(
            'number' => $card_number,
            'cardholderName' => $card_name,
            'expirationDate' => $expirationDate,
            'cvv' => $cvv
            )
        ));

        if ($result->success)
        {
            if($result->transaction->id)
            {
            $braintreeCode=$result->transaction->id;
            //updateUserOrder($braintreeCode,$session_user_id,$session_price); //Order table update.  
            }
        }
        else if ($result->transaction)
        {
        echo '{"OrderStatus": [{"status":"2"}]}';
        }
        else  
        {
        echo '{"OrderStatus": [{"status":"0"}]}';
        }
    }
?>