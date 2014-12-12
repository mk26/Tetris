<?php
	require_once('connection.php');
	include_once('flogin.php');
	
	function signup($name, $username, $password, $repassword)
	{
		try {
			global $conn;
		    $query = $conn->prepare("SELECT username FROM USERS WHERE username = ?");
			$query->execute(array($username));
			$checkuser = $query->fetch(PDO::FETCH_ASSOC);
		    if ($name==NULL || $username==NULL || $password==NULL)
		    {
		    	header("Location: signup.php"."?signup=failed");
			}
			else if ($checkuser['username']==$username)
		    {
				header("Location: signup.php"."?user=exists");
		    }
		    else if ($password!=$repassword)
		    {
				header("Location: signup.php"."?signup=pwnomatch");
		    }
			else
			{
				//Hashes the password for extra safety
				$hash = password_hash($password, PASSWORD_DEFAULT);
			 	$insquery = $conn->prepare("INSERT INTO USERS(username,password,name) VALUES (?, ?, ?)");
				$insquery->execute(array($username,$hash,$name));
				authenticate($username, $password);
		    }
	    }
	    catch (PDOException $error) {
	    echo $error->getMessage();
		}
	}
	
	if(isset($_POST['signup']))
	signup($_POST['name'], $_POST['username'], $_POST['password'], $_POST['passwordre']);
	
	//Test Signup
	//signup("Karthik", "mk", "password");
?>