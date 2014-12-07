<!DOCTYPE html>
<html>
	<head>
		<title>Tetris - Sign up</title>
		<script src="tetris.js" charset="utf-8"></script>
		<script src="jquery-2.1.1.js" charset="utf-8"></script>
		<link rel="stylesheet" href="tetris.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<link rel="icon" href="tetris.png" type="image/png">
	</head>
	<body>
		<div id="container">
			<img id="logo" src="tetris.png" width="100px" height="100px">
				<center>
				<h1>Signup</h1>
				<p>Enter username and password</p>
						<?php
			if($_GET['signup']=="failed")
				echo "<p class=\"error\">Username already exists, try again!</p>";
				?>	
				<form name="signup" value="signup" action="signup.php" method="post">
				<input type="text" class="textbox" name="name" placeholder="Name" required><br>
					<input type="text" class="textbox" name="username" placeholder="Username" required><br>
					<input type="password" class="textbox" name="password" placeholder="Password" required><br>
					<input type="password" class="textbox" name="passwordre" placeholder="Re-enter Password" required><br>
                    <input type="submit" name="signup" class="buttong" value="Sign up">
 				</form><hr>
 				<br>
<a href="index.php" class="buttonb-small">&lt; Go Back</a>
				</center>
			</div>
		</div>
		</body>
</html>
