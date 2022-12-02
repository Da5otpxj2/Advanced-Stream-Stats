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
<TITLE>Stats</TITLE>
<link href="assets/css/phppot-style.css" type="text/css"
	rel="stylesheet" />
<link href="assets/css/user-registration.css" type="text/css"
	rel="stylesheet" />
</HEAD>
<BODY>
    <div class="main-signup-heading"><h2>Advanced Stream Stats</h2></div>
	<div class="phppot-container">
		<div class="page-header">
            <span class="login-signup"><a href="logout.php">Logout</a></span>
            <span class="login-signup"><a href="stats.php">View Stats</a></span>
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
                            $subscriptionInfo = $subscription->updateSubscription($user_id, 0);
                            $subscriptionExpired = true;
                        }
                    }
                }else{
                    $subscriptionExpired = true;
                }

                if($subscriptionExpired){ 
                    if($subscriptionInfo[0]['product_id'] == 1){ ?>
                        <h2 style="text-align:center">Your subscription has been expired.</h2>
                        <p style="text-align:center">Subscribe to our plan for more detailed stats.</p>
                        <div class="columns">
                        <ul class="price">
                            <li class="header">Basic</li>
                            <li class="grey">$ 1.00 / 1 Day</li>
                            <li>Get access for all stats</li>
                            <li>One Day access</li>
                            <li class="grey"><a href="payment.php?type=1" class="button">Sign Up</a></li>
                        </ul>
                        </div>

                        <div class="columns">
                        <ul class="price">
                            <li class="header" style="background-color:#04AA6D">Pro</li>
                            <li class="grey">$ 12.99 / month</li>
                            <li>Get access for all stats</li>
                            <li>Unlimited Access</li>
                            <li class="grey"><a href="payment.php?type=2" class="button">Sign Up</a></li>
                        </ul>
                        </div>

                        <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>Get access for all stats</li>
                            <li>Unlimited Access</li>
                            <li class="grey"><a href="payment.php?type=3" class="button">Sign Up</a></li>
                        </ul>
                        </div>
                <?php  }else{ ?>
                        <h2 style="text-align:center">Your <?php if($subscriptionInfo[0]['product_id'] == 2){ echo "monthly"; }elseif($subscriptionInfo[0]['product_id'] == 3){ echo "yearly"; } ?> subscription has been expired.</h2>

                        <a href="renewSubscription.php" class="button">Renew</a>
                        <a href="cancelSubscription.php" class="button" style="background-color: red;">Cancel Subscription</a>
                <?php  } 
                }else{ ?>
                        <h2 style="text-align:center">Your detailed stats are here.</h2>
                        <p style="text-align: left;">                     
                            What is Lorem Ipsum?

                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                            Why do we use it?

                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).

                            Where does it come from?

                            Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                        </p>
                <?php } 

            }else{ ?>

                <h2 style="text-align:center">Subscription Plans</h2>
                <p style="text-align:center">Subscribe to our plan for more detailed stats.</p>

                <div class="columns">
                <ul class="price">
                    <li class="header">Basic</li>
                    <li class="grey">$ 1.00 / 1 Day</li>
                    <li>Get access for all stats</li>
                    <li>One Day access</li>
                    <li class="grey"><a href="payment.php?type=1" class="button">Sign Up</a></li>
                </ul>
                </div>

                <div class="columns">
                <ul class="price">
                    <li class="header" style="background-color:#04AA6D">Pro</li>
                    <li class="grey">$ 12.99 / month</li>
                    <li>Get access for all stats</li>
                    <li>Unlimited Access</li>
                    <li class="grey"><a href="payment.php?type=2" class="button">Sign Up</a></li>
                </ul>
                </div>

                <div class="columns">
                <ul class="price">
                    <li class="header">Premium</li>
                    <li class="grey">$ 49.99 / year</li>
                    <li>Get access for all stats</li>
                    <li>Unlimited Access</li>
                    <li class="grey"><a href="payment.php?type=3" class="button">Sign Up</a></li>
                </ul>
                </div>

           <?php } ?>
        </div>
	</div>
</BODY>
</HTML>
