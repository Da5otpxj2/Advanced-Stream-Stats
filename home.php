<?php
session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
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
<TITLE>Welcome</TITLE>
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
		<div class="page-content">Welcome <?php echo $username;?></div>
	</div>
</BODY>
</HTML>
