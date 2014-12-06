<?php 
	session_start();
	if(!isset($_SESSION['username']))
	{
	header("Location:index.php");
	}
	require_once 'connection.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><? echo $_SESSION['username']?> - Manage account</title>
		<script src="jquery-2.1.1.js" charset="utf-8"></script>
		<link rel="stylesheet" href="tetris.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<link rel="icon" href="tetris.png" type="image/png">
	</head>
	<body>
		<div id="container">
			<?php echo "<p>Hello there, <b>".$_SESSION['name']."!</b></p>" ?>
			<table id="scoreboard" class="pure-table">
				<caption>Account Details</caption>
				<tbody>
					<tr>
						<th>User Name</th>
						<td><? echo $_SESSION['username']?></td>
					</tr>
					<tr>
						<th>Password</th>
						<td><a href="tetris.html" class="buttonb-small">Change Password</a>
</td>
					</tr>
				</tbody>
			</table>
			<br>
			<a href="tetris.html" class="buttonb">&lt; Back</a>
		</div>
	</body>
	<script>
				
	</script>
	<?php
	
		function changePassword($oldpass,$newpass)
		{
			$user = $_SESSION['username'];
			//TODO - INVOKE USER DETAILS ON LOGIN
		    if($oldpass==$cust['password'])
		    {
		        $query = pg_query("UPDATE CUSTOMER SET password = '$newpass' WHERE username='$user'");
		        header("Location: manage.php"."?change=success");
		    }
		    else header("Location: manage.php"."?change=failed");
		}
		
	?>
</html>
