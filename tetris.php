<?php 
	session_start();
	require_once 'connection.php';
	if(!isset($_SESSION['username']))
	{
	header("Location:index.php");
	}
	updateSessionData();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Tetris</title>
		<script src="lib/jquery-2.1.1.js" charset="utf-8"></script>
		<link rel="stylesheet" href="tetris.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" href="lib/font-awesome.min.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<script src="lib/howler.js" charset="utf-8"></script>
		<link rel="icon" href="tetris.png" type="image/png">
	</head>
	<body>
		<div id="gamecontainer">
			<div id="boardcontainer">
				<audio loop id="bgscore" src="sounds/bgscore.mp3"></audio>
				<p id="paused">GAME PAUSED</p>
				<p id="gameover">GAME OVER</p>
				<p id="newgame">Press N to start a new game</p>
				<canvas id="board" width="300" height="600"></canvas>
			</div>
			<div id="sidebar">
				<div id="userheader">
				<span class="username"><?php echo "<a id=\"managelink\" alt=\"Manage account\" href=manage.php>".explode(' ', $_SESSION['name'])[0]."</a>";?></span>
				<a href="logout.php" id="logoutb"><i class="fa fa-sign-out"></i>&nbsp;LOGOUT</a></div>
				<hr>
				<p>Score &nbsp;<span id="score">~</span></p>
				<hr>
				<div id="stats">
				<p style="font-size: small">LAST ~ HIGH ~ PLAYS</p>
				<p id="statsinfo"><?php echo $_SESSION['userinfo']['lastscore']." | ".$_SESSION['userinfo']['highscore']." | ".$_SESSION['userinfo']['plays'];?></p>
				</div>
				<hr><center>
				<input type="checkbox" id="musicswitch" value="sound" checked>
				<label id="musiclabel" for="musicswitch"><i class="fa fa-music"></i>&nbsp;</label> 
				<input type="checkbox" id="soundswitch" value="sound" checked>
				<label id="soundlabel" for="soundswitch"><i class="fa fa-bell-o"></i>&nbsp;</label>
				</center>
				<hr>
				<a id="helpb"><i class="fa fa-info-circle"></i>&nbsp;Help</a>
			</div>
		</div>
		<script src="tetris.js" charset="utf-8"></script>
	</body>
</html>
