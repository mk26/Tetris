<?php 
	session_start();
	if(!isset($_SESSION['username'])) {
		header("Location:index.php");
	}
	require_once 'connection.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><? echo $_SESSION['username']?> - Manage account</title>
		<script src="frameworks/js/jquery-2.1.1.js" charset="utf-8"></script>
		<link rel="stylesheet" href="tetris.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" href="frameworks/css/font-awesome.min.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<link rel="icon" href="tetris.png" type="image/png">
	</head>
	<body>
		<div id="container">
			<?php echo "<p>Hello there, <b>".$_SESSION['name']."!</b></p>" ?>
			<table id="scoreboard">
				<caption>Account Details</caption>
				<tbody>
					<tr>
						<th>User Name</th>
						<td><? echo $_SESSION['username']?></td>
					</tr>
					<tr>
						<th>Password</th>
						<td><a id="changepassb" class="buttonb-small"><i class="fa fa-edit"></i>&nbsp;CHANGE</a></td>
					</tr>
					<tr>
						<th>Reset stats</th>
						<td><a id="resetb" class="buttonr-small"><i class="fa fa-repeat"></i>&nbsp;RESET</a>&nbsp;<a href="fmanage.php?stats=reset" id="resetsureb" class="buttonr-small"><i class="fa fa-question-circle"></i>&nbsp;SURE</a></td>
					</tr>
					<tr>
						<th>Delete Account</th>
						<td><a id="delaccb" class="buttonr-small"><i class="fa fa-remove"></i>&nbsp;DELETE ACCOUNT</a>&nbsp;<a href="fmanage.php?acc=delete" id="delsureb" class="buttonr-small"><i class="fa fa-question-circle"></i>&nbsp;SURE</a></td>
					</tr>
				</tbody>
			</table>
				<?php
					if($_GET['change']=="failed")
						echo "<p class=\"error\">Invalid details entered, try again!</p>";
					else if($_GET['change']=="pwnomatch")
						echo "<p class=\"error\">New Passwords do not match, try again!</p>";
					else if($_GET['change']=="success")
						echo "<p class=\"success\">Password successfully changed!</p>";
					else if($_GET['resetstats']=="success")
						echo "<p class=\"success\">Statistics successfully reset</p>";
				?>
			<div id="changepass">	
				<form name="changepass" value="changepass" action="fmanage.php" method="post">
					<input type="password" class="textbox" name="oldpassword" placeholder="Old Password" required pattern="[a-zA-Z0-9_@\#\^\$\ ]{4,}" title="4 or more characters (Alphabets, numbers, spaces, @, #, ^, $ allowed)"><br>
					<input type="password" class="textbox" name="password" placeholder="New Password" required pattern="[a-zA-Z0-9_@\#\^\$\ ]{4,}" title="4 or more characters (Alphabets, numbers, spaces, @, #, ^, $ allowed)"><br>
					<input type="password" class="textbox" name="passwordre" placeholder="Re-enter New Password" required pattern="[a-zA-Z0-9_@\#\^\$\ ]{4,}" title="Same as above field"><br>
                    <button type="submit" name="changepass" class="buttong"><i class="fa fa-check"></i>&nbsp;UPDATE</button>
 				</form>
			</div>
			<br>
			<a href="tetris.php" class="buttonb"><i class="fa fa-angle-left"></i>&nbsp;BACK</a>
		</div>
		<script>
			$(document).ready(function(){
				if(document.getElementsByClassName("error").length==0) {
					$('#changepass').hide();
					$('#resetsureb').hide();
					$('#delsureb').hide();
				}
			});
			$('#changepassb').click(function(){
				$('#changepass').toggle();
			});	
			$('#delaccb').click(function(){
				$('#delsureb').toggle();
			});	
			$('#resetb').click(function(){
				$('#resetsureb').toggle();
			});	
	</script>
	</body>
</html>
