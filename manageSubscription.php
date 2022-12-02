<?php
session_start();
if (isset($_SESSION["username"])) {
    $user_id = $_SESSION['user_id']; //Cart Total Price 
    session_write_close();
} else {
    session_unset();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
}

use Phppot\Subscription;
require_once __DIR__ . '/Model/Subscription.php';
$subscription = new Subscription();
$subscriptionInfo = $subscription->getMemberSubscription($user_id);


?>
<HTML>
<HEAD>
<TITLE>Manage Subscription</TITLE>
<link href="assets/css/phppot-style.css" type="text/css"
	rel="stylesheet" />
<link href="assets/css/user-registration.css" type="text/css"
	rel="stylesheet" />
<script src="assets/js/jquery.min.js"></script>
</HEAD>
<BODY>
    <div class="main-signup-heading"><h2>Advanced Stream Stats</h2></div>
	<div class="phppot-container">
		<div class="page-header">
            <span class="login-signup"><a href="logout.php">Logout</a></span>
            <span class="login-signup"><a href="stats.php">View Stats</a></span>
            <span class="login-signup"><a href="subscription.php">Manage Subscription</a></span>
            <span class="login-signup"><a href="home.php">Home</a></span>
		</div>
		<div class="page-content">

            <?php 
            $currentTime = date('Y-m-d H:i:s');

            $subscriptionExpired = false;
            
            if(!empty($subscriptionInfo)){
                if($subscriptionInfo[0]['status'] == 1){ 
                    if($subscriptionInfo[0]['product_id'] == 1){
                        if((strtotime($currentTime) - strtotime($subscriptionInfo[0]['created'])) > 86400){
                            $subscription->updateSubscription($user_id, 0);
                            $subscriptionExpired = true;
                        }
                    }else{

                        require_once 'lib/Braintree/lib/Braintree.php';
                        $config = new Braintree\Configuration([
                            'environment' => 'sandbox',
                            'merchantId' => 'dck5trc2pyxg8wky',
                            'publicKey' => 'bp2y5xjz75kpmqzx',
                            'privateKey' => '333773333e7a5eabe86c22fadc64994d'
                        ]);
                        $gateway = new Braintree\Gateway($config);
                        $subscriptionResult = $gateway->subscription()->find($subscriptionInfo[0]['subscriptionId']);
                        if(isset($subscriptionResult->status) && $subscriptionResult->status !== 'Active'){
                            $subscription->updateSubscription($user_id, 0);
                            $subscriptionExpired = true;
                        }
                    }
                }else{
                    $subscriptionExpired = true;
                }

                if($subscriptionExpired){ 
                    if($subscriptionInfo[0]['product_id'] == 1){ ?>

                        <h2 style="text-align:center">Your subscription has been expired.</h2>
                        <p style="text-align:center;margin-bottom: 50px;">Subscribe to our plan for more detailed stats.</p>

                        <a href="stats.php" class="button">Buy Now</a>
                        
                <?php  }else{ ?>
                        <h2 style="text-align:center">Your  <?php if($subscriptionInfo[0]['product_id'] == 2){ echo "monthly"; }elseif($subscriptionInfo[0]['product_id'] == 3){ echo "yearly"; } ?> subscription has been expired.</h2>
                        <p style="text-align:center;margin-bottom: 50px;">Subscribe to our plan for more detailed stats.</p>

                        <a href="renewSubscription.php" class="button">Renew Now</a>
                        <a class="button cancelSubscription" style="background-color: red;">Cancel Subscription</a>

                <?php } 
                }else{ ?>
                        <h2 style="text-align:center">Your <?php if($subscriptionInfo[0]['product_id'] == 2){ echo "monthly"; }elseif($subscriptionInfo[0]['product_id'] == 3){ echo "yearly"; }else{ echo "one day"; } ?> subscription is Active.</h2>
                        <p style="text-align:center;margin-bottom: 50px;">You can cancel your subscription anytime.</p>

                        <a class="button cancelSubscription" style="background-color: red;">Cancel Subscription</a>
                <?php } 

            }else{ ?>

                <h2 style="text-align:center">You don't have any active Subscription.</h2>
                <p style="text-align:center;margin-bottom: 50px;">Subscribe to our plan for more detailed stats.</p>

                <a href="stats.php" class="button">Buy Subscription</a>

           <?php } ?>
        </div>
	</div>

    <script>
    
    jQuery(document).ready(function($){

        $(".cancelSubscription").on('click',function(){ 
            $.ajax({
                type: "GET",
                url: "cancelSubscription.php",
                beforeSend: function()
                {  
                    $(".cancelSubscription").html('Processing..');
                },
                success: function(data) 
                {
                    
                    if(data.status){
                        $(".cancelSubscription").html('Subscription Cancelled.');
                    }else{
                        alert(data.msg);
                    }

                    location.reload(); 
                    
                },
                error: function(){ alert('error handing here'); }
            });
        });

    });

    </script> 
</BODY>
</HTML>
