<?php 
	session_start();
	require_once('connection.php');
	
	function updateScore($newscore)
	{
		try {
			global $conn;
			updateSessionData();
			if($newscore > $_SESSION['userinfo']['highscore'])
			{
				$query = $conn->prepare("UPDATE USERS SET highscore = ? WHERE username = ?");
				$query->execute(array($newscore, $_SESSION['username']));
			}
			$query = $conn->prepare("UPDATE USERS SET lastscore = ?, plays = plays+1 WHERE username = ?");
			$query->execute(array($newscore, $_SESSION['username']));
			updateSessionData();
	   	}
		catch (PDOException $error) {
	    	echo $error->getMessage();
		}
		
		echo $_SESSION['userinfo']['lastscore']." | ".$_SESSION['userinfo']['highscore']." | ".$_SESSION['userinfo']['plays'];
	}
	
	if(isset($_POST['score']))
	updateScore($_POST['score']);
	
	//Test score update
	//updateScore(123);
?>