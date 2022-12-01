<?php
session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    session_write_close();
} else {
    // since the username is not set in session, the user is not-logged-in
    // he is trying to access this page unauthorized
    // so let's clear all session variables and redirect him to index
    session_unset();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
}

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
			<span class="login-signup"><a href="subscription.php">Manage Subscription</a></span>
            <span class="login-signup"><a href="home.php">Home</a></span>
		</div>
		<div class="page-content">

            <h2 style="text-align:center">Subscription Plans</h2>
            <p style="text-align:center">Subscribe to plans for more detailed stats.</p>

            <div class="columns">
            <ul class="price">
                <li class="header">Basic</li>
                <li class="grey">$ 1.00 / onetime</li>
                <li>Get access for all stats</li>
                <li>One time access</li>
                <li class="grey"><a href="#" class="button">Sign Up</a></li>
            </ul>
            </div>

            <div class="columns">
            <ul class="price">
                <li class="header" style="background-color:#04AA6D">Pro</li>
                <li class="grey">$ 12.99 / month</li>
                <li>Get access for all stats</li>
                <li>Unlimited Access</li>
                <li class="grey"><a href="#" class="button">Sign Up</a></li>
            </ul>
            </div>

            <div class="columns">
            <ul class="price">
                <li class="header">Premium</li>
                <li class="grey">$ 49.99 / year</li>
                <li>Get access for all stats</li>
                <li>Unlimited Access</li>
                <li class="grey"><a href="#" class="button">Sign Up</a></li>
            </ul>
            </div>
        </div>
	</div>
</BODY>
</HTML>
