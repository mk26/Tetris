<?php 
	session_start();
	require_once('connection.php');

	function authenticate($username, $password)
	{
		try {
		global $conn;
		$query = $conn->prepare("SELECT * FROM USERS WHERE username = ?");
		$query->execute(array($username));
		$user = $query->fetch(PDO::FETCH_ASSOC);
		$hash = $user['password'];
	    if(!$query || $user==NULL || $username==NULL || $password==NULL || password_verify($password, $hash)==false) {
			header("Location: index.php"."?login=failed");
	    }
	    else 
	    {
		    $_SESSION['userinfo']=$user;
	        $_SESSION['username']=$user['username'];
	        $_SESSION['name']=$user['name'];      
			header("Location: tetris.php");
	    }
	   	}
		catch (PDOException $error) {
	    echo $error->getMessage();
		}
	}
	
	if(isset($_POST['login']))
	authenticate($_POST['username'], $_POST['password']);
	
	//Test login
	/*
		authenticate("test", "test");
		echo "Login success";
	*/
?>