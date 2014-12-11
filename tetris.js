/* tetris.js
	Contains all game logic
	+ Uses JQuery & Howler.js (for more info, see project document)
	COEN276 Project - KARTHIK
*/

/* Game Sounds */

var bgSound = new Howl({
    src: ["sounds/bgscore.mp3"],
    loop: true,
    volume: 0.5,
    preload: true,
    onfaded: function () {
        if (isPaused == true) this.pause();
        else if (gameLost == true) this.stop();
    },
    onplay: function () {
        this.fade(0.1, 0.5, 500);
    }
});

var dropSound = new Howl({
    src: ["sounds/drop.mp3"]
});
var clearSound = new Howl({
    src: ["sounds/clear.mp3"]
});
var pauseSound = new Howl({
    src: ["sounds/pause.mp3"]
});
var endSound = new Howl({
    src: ["sounds/end.mp3"],
    volume: 0.7
});

/* Global Variables */

//Game board related parameters
var board = [];
var columns = 12;
var rows = 24;
var blockWidth = $('#board').width() / columns;
var blockHeight = $('#board').height() / rows;

//Represents the type and position of the dropping block
var current;
var currentX;
var currentY;

//tetrimino shape configurations and colors
var tetriminoes = [
    [1, 1, 1, 1],	//I
    [1, 1, 1, 0,	//J
     0, 0, 1],
    [1, 1, 1, 0,	//L
     1],
    [1, 1, 0, 0,	//O
     1, 1],
    [0, 1, 1, 0,	//S
     1, 1],
    [0, 1, 0, 0,	//T
     1, 1, 1],
    [1, 1, 0, 0,	//Z
     0, 1, 1]
];
var colors = [
    'cyan', 'blue', 'darkorange', 'yellow', 'green', 'darkmagenta', 'red'
];

//Game state parameters
var score = 0;
var gameLost = false;
var gameBegan = false;
var isPaused = false;

//Canvas to draw on
var context = $('#board')[0].getContext("2d");
var dropRate;
var drawRate;

/* Game Logic */

//Create new tetrimino shape
function newShape() {
    var type = Math.floor(Math.random() * tetriminoes.length);
    var shape = tetriminoes[type];

    current = [];

    for (var y = 0; y < 4; y++) {
        current[y] = [];
        for (var x = 0; x < 4; x++) {
            var i = 4 * y + x;
            if (typeof shape[i] != 'undefined' && shape[i]) {
                current[y][x] = type + 1;
            } else {
                current[y][x] = 0;
            }
        }
    }

    //Rotate randomly 
    var autoRotate = Math.floor((Math.random() * 4));
    for (var i = 0; i < autoRotate; i++) {
        //handleKey('rotate');
    }

    //Entry point of tetriminoes
    currentX = Math.floor((Math.random() * 6) + 2);
    currentY = 0;
}

//Clear board
function init() {
    for (var y = 0; y < rows; y++) {
        board[y] = [];
        for (var x = 0; x < columns; x++) {
            board[y][x] = 0;
        }
    }
}

//Keep current shape falling down and clear lines if necessary
function drop() {
    if (isValid(0, 1)) {
        currentY++;
    }
    else {
		if (gameLost) {
            gameOver();
            return false;
        }
        settle();
        clearLines();
        newShape();
    }
}

//Freeze shape in board
function settle() {
	clearInterval(dropRate);
	dropRate = setInterval(drop, 400);
    dropSound.play();
    for (var y = 0; y < 4; y++) {
        for (var x = 0; x < 4; x++) {
            if (current[y][x]) {
                board[y + currentY][x + currentX] = current[y][x];
                if (!gameLost) score = score + current[y][x];
            }
        }
    }
    $('#score').fadeOut("fast", function () {
        $('#score').html(score).fadeIn("fast");
    });
}

//Rotate shape anticlockwise
function rotate(current) {
    var rotated = [];
    for (var y = 0; y < 4; y++) {
        rotated[y] = [];
        for (var x = 0; x < 4; x++) {
            rotated[y][x] = current[3 - x][y];
        }
    }
    return rotated;
}

//Clear line if no gaps remain
function clearLines() {
    for (var y = rows - 1; y >= 0; --y) {
        var rowFilled = true;
        for (var x = 0; x < columns; x++) {
            if (board[y][x] == 0) {
                rowFilled = false;
                break;
            }
        }
        if (rowFilled) {
            score = score + 200;
            $('#score').html(score);
            clearSound.play();
            for (var z = y; z > 0; z--) {
                for (var x = 0; x < columns; x++) {
                    board[z][x] = board[z - 1][x];
                }
            }
            y++;
        }
    }
}

//Check if shape can be placed properly
function isValid(offsetX, offsetY, rotated) {
    offsetX = offsetX || 0;
    offsetY = offsetY || 0;
    offsetX = currentX + offsetX;
    offsetY = currentY + offsetY;
    rotated = rotated || current;

    for (var y = 0; y < 4; y++) {
        for (var x = 0; x < 4; x++) {
            if (rotated[y][x]) {
                if (typeof board[y + offsetY] == 'undefined' 
                || typeof board[y + offsetY][x + offsetX] == 'undefined' 
                || board[y + offsetY][x + offsetX] 
                || x + offsetX < 0 
                || y + offsetY >= rows 
                || x + offsetX >= columns) {
                    if (offsetY == 1) {
                        gameLost = true; 
                    }
                    return false;
                }
            }
        }
    }
    return true;
}

/* Game Display */

//Draws a single block within a tetrimino
function drawBlock(x, y, color) {
	context.fillStyle = color;
    context.fillRect(blockWidth * x, blockHeight * y, blockWidth - 1, blockHeight - 1);
}

//Overall rendering of the board and the tetriminoes on screen
function draw() {
    context.clearRect(0, 0, $('#board').width(), $('#board').height());
    //Settled blocks
    for (var x = 0; x < columns; x++) {
        for (var y = 0; y < rows; y++) {
            if (board[y][x]) {
                drawBlock(x, y, colors[board[y][x] - 1]);
            }
        }
    }
    //Moving shapes
    for (var y = 0; y < 4; y++) {
        for (var x = 0; x < 4; x++) {
            if (current[y][x]) {
                drawBlock(currentX + x, currentY + y, colors[current[y][x] - 1]);
            }
        }
    }
}

/* Game Controls */

function newGame() {
    if ($('#musicswitch').is(':checked')) bgSound.stop().play();
    if ($('#soundswitch').is(':checked')) {
	    dropSound.mute(false);
	    clearSound.mute(false);
	    pauseSound.mute(false);
	    endSound.mute(false);
	}
    clearInterval(dropRate);
    clearInterval(drawRate);
    $('#newgame').hide();
    $('#gameover').hide();
    $("#board").css({
        "-webkit-animation": "blurout 1s forwards",
        "animation": "blurout 1s forwards"
    });
    init();
    newShape();
    gameLost = false;
    score = 0;
    $('#score').html(score);
    dropRate = setInterval(drop, 400);
    drawRate = setInterval(draw, 10);
}

function pauseGame() {
    if (isPaused == false) {
        pauseSound.play();
        clearInterval(dropRate);
        isPaused = true;
        if ($('#musicswitch').is(':checked')) bgSound.fade(0.5, 0.0, 500);
        $('#paused').show();
        $("#board").css({
            "-webkit-animation": "blurin 1s forwards",
            "animation": "blurin 1s forwards"
        });
    } else {
        dropRate = setInterval(drop, 400);
        isPaused = false;
        if ($('#musicswitch').is(':checked')) bgSound.play();
        $('#paused').hide();
        $("#board").css({
            "-webkit-animation": "blurout 1s forwards",
            "animation": "blurout 1s forwards"
        });
    }
}

function gameOver() {
	clearInterval(dropRate);
    clearInterval(drawRate);
    bgSound.fade(0.5, 0.0, 500);
    endSound.play();
    $.post("updatescore.php", "score=" + score, function (data) {
        $("#statsinfo").html(data);
    });
    $("#board").css({
        "-webkit-animation": "blurin 1s forwards",
        "animation": "blurin 1s forwards"
    });
    $('#gameover').show();
}

//Handle key presses by player
function handleKey(key) {
    switch (key) {
    case 'left':
        if (isValid(-1) && isPaused == false && !gameLost) {
            currentX--;
        }
        break;
    case 'right':
        if (isValid(1) && isPaused == false && !gameLost) {
            currentX++;
        }
        break;
    case 'down':
        if (isValid(0, 1) && isPaused == false && !gameLost) {
            currentY++;
        }
        break;
    case 'fall':
        if (isValid(0, 1) && isPaused == false && !gameLost) {
			clearInterval(dropRate);
			dropRate = setInterval(drop, 10);
        }
        break;
    case 'rotate':
        var rotated = rotate(current);
        if (isValid(0, 0, rotated) && isPaused == false && !gameLost) {
            current = rotated;
        }
        break;
    case 'pause':
        if (!gameLost && gameBegan == true) {
            pauseGame();
        }
        break;
    case 'end':
        if (isPaused == false && gameBegan == true) {
            gameLost = true;
            gameBegan = false;
            gameOver();
        }
        break;
    case 'new':
        if (isPaused == false) {
            gameBegan = true;
            newGame();
        }
        break;
    }
}

//Setup the game environment and controls
$(document).keydown(function (event) {
    var pressedKey = event.keyCode;
    var controlKeys = {
        37: 'left',
        39: 'right',
        40: 'down',
        32: 'fall',
        38: 'rotate',
        80: 'pause',
        78: 'new',
        69: 'end'
    };
    if (pressedKey in controlKeys) {
        handleKey(controlKeys[pressedKey]);
        draw();
    }
});

$(document).keyup(function (event) {
	if(event.keyCode==32) {
		if(isPaused == false && !gameLost) {
	      	clearInterval(dropRate);
		  	dropRate = setInterval(drop, 400);
        }
	}
});

$('#musicswitch').click(function(){
    if ($('#musicswitch').is(':checked')) bgSound.play();
    else bgSound.stop();
});

$('#helpb').click(function(){
	$('#helpcontainer').toggle();
});	

$('#soundswitch').click(function () {
    if ($('#soundswitch').is(':checked')) {
	    dropSound.mute(false);
	    clearSound.mute(false);
	    pauseSound.mute(false);
	    endSound.mute(false);
	}
    else {
	    dropSound.mute(true);
	    clearSound.mute(true);
	    pauseSound.mute(true);
	    endSound.mute(true);
	}
});

$(document).ready(function () {
    $('#gameover').hide();
    $('#paused').hide();
    $('#helpcontainer').hide();
});