<?php 
	session_start();
	if(!isset($_SESSION['username']))
	{
	header("Location:index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Tetris</title>
		<script src="jquery-2.1.1.js" charset="utf-8"></script>
		<link rel="stylesheet" href="tetris.css" title="Tetris" type="text/css" media="screen" charset="utf-8">
		<link rel="icon" href="tetris.png" type="image/png">
	</head>
	<body>
		<div id="maincontainer">
			<div id="gameboard">
				<audio loop id="bgscore" src="sounds/bgscore.mp3"></audio>
				<p id="paused">GAME PAUSED</p>
				<p id="gameover">GAME OVER</p>
				<p id="newgame">Press N to start a new game</p>
				<canvas id="board" width="300" height="600"></canvas>
			</div>
			<div id="sidebar">
				<?php
					echo "<p>Welcome, ".$_SESSION['name']."!</p>"; 
				?>
				<hr>
				<p>Score &nbsp;<span id="score">~</span></p>
				<hr>
				<div id="stats">
				<p style="font-size: small">LAST ~ HIGH ~ PLAYS</p>
				<p id="statsinfo"><?php echo $_SESSION['userinfo']['lastscore']." | ".$_SESSION['userinfo']['highscore']." | ".$_SESSION['userinfo']['plays'];?></p>
				</div>
				<hr><br>
				<a href="tetris.php" class="buttonb-small">Play again</a>
			</div>
		</div>
		<script src="tetris.js" charset="utf-8"></script>
	</body>
</html>
