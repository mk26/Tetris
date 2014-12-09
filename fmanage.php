<?php 
	session_start();
	require_once 'connection.php';
	
	function changePassword($oldpass,$newpass,$repassword)
	{
		try {
			global $conn;
			updateSessionData();
			$user = $_SESSION['userinfo'];
			$hash = $user['password'];
			$newhash = password_hash($newpass, PASSWORD_DEFAULT);

			if ($oldpass==NULL || $newpass==NULL || $repassword==NULL)
		    {
		    	header("Location: manage.php"."?change=failed");
			}
		    else if ($newpass!=$repassword)
		    {
				header("Location: manage.php"."?change=pwnomatch");
		    }
			else if(password_verify($oldpass, $hash)==true)
			{
				$query = $conn->prepare("UPDATE USERS SET password = ? WHERE username = ?");
				$query->execute(array($newhash, $_SESSION['username']));
				header("Location: manage.php"."?change=success");
			}
			updateSessionData();
	   	}
		catch (PDOException $error) {
	    	echo $error->getMessage();
		}			
	}
	
	function resetStats()
	{
		try {
			global $conn;
			$query = $conn->prepare("UPDATE USERS SET lastscore = 0, highscore = 0, plays = 0 WHERE username = ?");
			$query->execute(array($_SESSION['username']));
			header("Location: manage.php"."?resetstats=success");
			updateSessionData();
	   	}
		catch (PDOException $error) {
	    	echo $error->getMessage();
		}			
	}
	
	function deleteAccount()
	{
		try {
			global $conn;
			$query = $conn->prepare("DELETE FROM USERS WHERE username = ?");
			$query->execute(array($_SESSION['username']));
			session_destroy();
			echo "<!DOCTYPE html><html><head><title>Account deleted</title></head><body>";
			echo "<link rel=\"stylesheet\" href=\"tetris.css\" title=\"Tetris\" type=\"text/css\" media=\"screen\" charset=\"utf-8\">";
			echo "<link rel=\"stylesheet\" href=\"frameworks/css/font-awesome.min.css\" title=\"Tetris\" type=\"text/css\" media=\"screen\" charset=\"utf-8\">";
			echo "<p class=\"success\">Account deleted Successfully</p>";
			echo "<a href=\"index.php\" class=\"buttonb\"><i class=\"fa fa-home\"></i> GO TO HOME PAGE</a></body></html>";
			updateSessionData();
	   	}
		catch (PDOException $error) {
	    	echo $error->getMessage();
		}
	}

	if(isset($_POST['changepass']))
	changePassword($_POST['oldpassword'], $_POST['password'], $_POST['passwordre']);
	
	if(isset($_GET['stats'])) 
	resetStats();
	
	if(isset($_GET['acc'])) 
	deleteAccount();
?>