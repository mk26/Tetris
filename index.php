<?php 
	session_start(); 
	if(isset($_SESSION['username'])) { 
		header( "Location:tetris.php"); 
	}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tetris - Login</title>
    <script src="frameworks/js/jquery-2.1.1.js" charset="utf-8"></script>
    <link rel="stylesheet" href="tetris.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="frameworks/css/font-awesome.min.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
    <link rel="icon" href="tetris.png" type="image/png">
</head>

<body>
    <div id="container">
        <img id="logo" src="tetris.png" width="250px" height="250px">
        <center>
            <h1>Welcome to Tetris</h1>
            <p>Enter credentials to play</p>
            <?php 
	            if($_GET['login']=="failed")
	            	echo "<p class=\"error\">Invalid Username/Password, try again!</p>"; 
	        ?>
            <form name="login" value="login" action="flogin.php" method="post">
                <input type="text" class="textbox" name="username" placeholder="Username">
                <br>
                <input type="password" class="textbox" name="password" placeholder="Password">
                <br>
                <input type="submit" name="login" class="buttonb" value="LOGIN">
            </form>
            <hr>
            <br>
            <span>Don't have an account?&nbsp;</span>
            <a href="signup.php" class="buttong"><i class="fa fa-user"></i>&nbsp;SIGN UP</a>
        </center>
    </div>
    </div>
</body>
</html>
