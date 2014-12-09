<?php 
	session_start();
	
	try {
	//DATABASE INFO
	$dbname="mktetris";
	$dbuser="mk";
	$dbpass="mkpass";
	
	$conn = new PDO('mysql:host=localhost;dbname='.$dbname.';', $dbuser, $dbpass);
	
	//Test if connection was a success
	/*
		$_SESSION['success']="Connection Successful";
		echo $_SESSION['success'];
	*/
	
	}
	catch (PDOException $error) {
	    echo $error->getMessage();
	}
	
	function updateSessionData()
	{
		global $conn;
		$query = $conn->prepare("SELECT * FROM USERS WHERE username = ?");
		$query->execute(array($_SESSION['username']));
		$_SESSION['userinfo'] = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['username'] = $_SESSION['userinfo']['username'];
		$_SESSION['name'] = $_SESSION['userinfo']['name'];  
	}
?>