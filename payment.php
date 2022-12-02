<?php

session_start();
if (isset($_SESSION["username"])) {
    $subscriptionList = array(1, 2, 3);
    if(isset($_GET['type']) && !empty($_GET['type'])){
        if(in_array($_GET['type'], $subscriptionList)){
            $_SESSION["subscription_type"] = $_GET['type'];
        }else{
            $url = "./stats.php";
            header("Location: $url");
        }
    }else{
        $url = "./stats.php";
        header("Location: $url");
    }
    session_write_close();
} else {
    session_unset();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
}

?>
<HTML>
<HEAD>
<TITLE>Payment</TITLE>
<link href="assets/css/phppot-style.css" type="text/css"
	rel="stylesheet" />
<link href="assets/css/user-registration.css" type="text/css"
	rel="stylesheet" />
<link href="assets/css/payment.css" type="text/css"
	rel="stylesheet" />

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.creditCardValidator.js"></script>

</HEAD>
<BODY>
    <div class="main-signup-heading"><h2>Advanced Stream Stats</h2></div>
	<div class="phppot-container">
		<div class="page-header">
            <span class="login-signup"><a href="logout.php">Logout</a></span>
            <span class="login-signup"><a href="stats.php">View Stats</a></span>
			<span class="login-signup"><a href="manageSubscription.php">Manage Subscription</a></span>
            <span class="login-signup"><a href="home.php">Home</a></span>
		</div>
		<div class="page-content">
            <form method="post"  id="paymentForm">
                Payment details
                <ul>

                <li>
                <label>Card Number </label>
                <input type="text" name="card_number" id="card_number"  maxlength="20" placeholder="1234 5678 9012 3456" rel="0"/>
                </li>
                <li>
                <label>Name on Card</label>
                <input type="text" name="card_name" id="card_name" placeholder="Srinivas Tamada"/>
                </li>
                <li class="vertical">

                <ul>
                <li>
                <label>Expires</label>
                <input type="text" name="expiry_month" id="expiry_month" maxlength="2" placeholder="MM" />
                <input type="text" name="expiry_year" id="expiry_year" maxlength="2" placeholder="YY" />
                </li>
                <li>
                <label>CVV</label>
                <input type="text" name="cvv" id="cvv" maxlength="3" placeholder="123" autocomplete="off"/>
                </li>
                </ul>

                </li>
                <li>
                <input type="submit" id="paymentButton" value="Proceed" disabled="true" class="disable">
                </li>
                </ul>
            </form>
            <div id="orderInfo"></div>
            <div id="demoCards" style="display:none;">
                <h4>Try these demo numbers</h4>
                <ul id="cards">
                <li>5105105105105100</li>
                <li>
                4111 1111 1111 1210</li>
                <li>
                4111 1111 1113 1010
                </li>
                <li>
                4000 0000 0000 0002
                </li>
                <li>
                4026 0000 0000 0002
                </li>
                <li>
                5018 0000 0009
                </li>
                <li>
                5100 0000 0000 0008
                </li>
                <li>
                6011 0000 0000 0004
                </li>
                </ul>
            </div>
        </div>
	</div>


<script>
    /* Credit Card Type Check */
function cardValidate()
{
    $('#card_number').validateCreditCard(function(result) 
    {
        var N=$(this).val();
        var C=$(this).attr("class");
        $(this).attr("class","");
        if(result && N.length>0 && result.card_type !== null)
        {
            $(this).addClass(result.card_type.name);
            if(result.valid && result.length_valid && result.luhn_valid)
            {
            $(this).addClass('valid');  
            $(this).attr("rel","1");
            }
            else
            {
            $(this).attr("rel","0");  
            }
        }
        else
        {
            $(this).removeClass(C);
        }
    });
}

jQuery(document).ready(function($){

    /* Button Enable*/
    $("#paymentForm input[type=text]").on("keyup",function(){

        cardValidate();
        var cardValid=$("#card_number").attr('rel');
        var C=$("#card_name").val();
        var M=$("#expiry_month").val();
        var Y=$("#expiry_year").val();
        var CVV=$("#cvv").val();
        var expName =/^[a-z ,.'-]+$/i;
        var expMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
        var expYear = /^16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31$/;
        var expCVV=/^[0-9]{3,3}$/;
        var cardCheck=$('#card_number').attr("rel");

        console.log('cardValid', cardValid);
        console.log('expName', expName.test(C));
        console.log('expMonth', expMonth.test(M));
        console.log('expYear', expYear.test(Y));
        console.log('expCVV', expCVV.test(CVV));
        console.log('cardCheck', parseInt(cardCheck)>0);


        if(cardValid>0 && expName.test(C) && expMonth.test(M) && expYear.test(Y) && expCVV.test(CVV) && parseInt(cardCheck)>0)
        { 
            $('#paymentButton').prop('disabled', false);   
            $('#paymentButton').removeClass('disable');
        }
        else
        {
            $('#paymentButton').prop('disabled', true);  
            $('#paymentButton').addClass('disable'); 
        }
    });

    /* Card Click */
    $("#cards li").on('click',function(){
        var x=$.trim($(this).html());
        $("#card_number").val(x);
        cardValidate();
    })

    /*Payment Form */
    $("#paymentForm").on('submit',function(){
        var datastring = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "processPayment.php",
            data: datastring,
            dataType: "json",
            beforeSend: function()
            {  
            $("#paymentButton").val('Processing..');
            },
            success: function(data) 
            {
                $.each(data.OrderStatus, function(i,data){
                    var HTML;
                    if(data)
                    {
                    $("#paymentForm").slideUp("slow");  
                    if(data.status == '1')
                    {
                        HTML="Order <span>#"+data.orderID+"</span> your subscription has been Activated,<br>Click the stats menu to view detailed information. Thank you!."; 
                    }
                    else if(data.status == '0')
                    {
                        HTML="Transaction has been failed. "+data.msg; 
                    }
                    $("#orderInfo").show("slow");
                    $("#orderInfo").html(HTML);
                    }
                });
            },
            error: function(){ alert('error handing here'); }
        });
        return false;
    });

});

</script> 





</BODY>
</HTML>
