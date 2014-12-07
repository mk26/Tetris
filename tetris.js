//GAME LOGIC

var dropSound = new Audio("sounds/drop.mp3");
var clearSound = new Audio("sounds/clear.mp3");
var COLS = 12;
var ROWS = 24;
var board = [];
var gameLost;
var gameBegan = false;
var tickinterval;
var renderInterval;
var isPaused = false;
var current; // current moving shape
var currentX, currentY; // position of current shape
var shapes = [
    [1, 1, 1, 1],
    [1, 1, 1, 0,
     1],
    [1, 1, 1, 0,
     0, 0, 1],
    [1, 1, 0, 0,
     1, 1],
    [1, 1, 0, 0,
     0, 1, 1],
    [0, 1, 1, 0,
     1, 1],
    [0, 1, 0, 0,
     1, 1, 1]
];
var colors = [
    'cyan', 'orange', 'blue', 'yellow', 'red', 'green', 'purple'
];
var score = 0;
var color;

function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}

// creates a new 4x4 shape in global variable 'current'
// 4x4 so as to cover the size when the shape is rotated
function newShape() {
    var id = Math.floor(Math.random() * shapes.length);
    var shape = shapes[id]; // maintain id for color filling

    current = [];
    
    for (var y = 0; y < 4; ++y) {
        current[y] = [];
        for (var x = 0; x < 4; ++x) {
            var i = 4 * y + x;
            if (typeof shape[i] != 'undefined' && shape[i]) {
                current[y][x] = id + 1;
            } else {
                current[y][x] = 0;
            }
        }
    }
       
    //Rotate randomly 
	var autoRotate = Math.floor((Math.random() * 5));
    for(var i=0; i<autoRotate; i++)
    {
        //handleKey('rotate');
    }

    //Entry point of tetrominoes
    currentX = Math.floor((Math.random() * 5) + 2);
    currentY = 0;  
}

// clears the board
function init() {
    for (var y = 0; y < ROWS; ++y) {
        board[y] = [];
        for (var x = 0; x < COLS; ++x) {
            board[y][x] = 0;
        }
    }
}

// keep the element moving down, creating new shapes and clearing lines
function tick() {
    if (valid(0, 1)) {
        ++currentY;
    }
    // if the element settled
    else {
        freeze();
        clearLines();
        if (gameLost) {
			gameOver();
            return false;
        }
        newShape();
    }
}

// stop shape at its position and fix it to board
function freeze() {
	//document.getElementById('dropsound').play();
	dropSound.play();
    for (var y = 0; y < 4; ++y) {
        for (var x = 0; x < 4; ++x) {
            if (current[y][x]) {
                board[y + currentY][x + currentX] = current[y][x];
				if(!gameLost) score = score + current[y][x];
            }
        }
    }
    $('#score').fadeOut("fast",function(){
		$('#score').html(score).fadeIn("fast");
    });
}

// returns rotates the rotated shape 'current' perpendicularly anticlockwise
function rotate(current) {
    var newCurrent = [];
    for (var y = 0; y < 4; ++y) {
        newCurrent[y] = [];
        for (var x = 0; x < 4; ++x) {
            newCurrent[y][x] = current[3 - x][y];
        }
    }
    return newCurrent;
}

// check if any lines are filled and clear them
function clearLines() {
    for (var y = ROWS - 1; y >= 0; --y) {
        var rowFilled = true;
        for (var x = 0; x < COLS; ++x) {
            if (board[y][x] == 0) {
                rowFilled = false;
                break;
            }
        }
        if (rowFilled) {
	        score = score + 200;
			$('#score').html(score);
            //document.getElementById('clearsound').play();
            for (var yy = y; yy > 0; --yy) {
                for (var x = 0; x < COLS; ++x) {
                    board[yy][x] = board[yy - 1][x];
                }
            }
            ++y;
        }
    }
}


// checks if the resulting position of current shape will be feasible
function valid(offsetX, offsetY, newCurrent) {
    offsetX = offsetX || 0;
    offsetY = offsetY || 0;
    offsetX = currentX + offsetX;
    offsetY = currentY + offsetY;
    newCurrent = newCurrent || current;

    for (var y = 0; y < 4; ++y) {
        for (var x = 0; x < 4; ++x) {
            if (newCurrent[y][x]) {
                if (typeof board[y + offsetY] == 'undefined' || typeof board[y + offsetY][x + offsetX] == 'undefined' || board[y + offsetY][x + offsetX] || x + offsetX < 0 || y + offsetY >= ROWS || x + offsetX >= COLS) {
                    if (offsetY == 1) 
                    gameLost = true; // gameLost if the current shape at the top row when checked
                    return false;
                }
            }
        }
    }
    return true;
}

//GAME RENDER
var canvas = document.getElementById("board");
var context = canvas.getContext("2d");
var W = 300,
    H = 600;
var BLOCK_W = W / COLS,
    BLOCK_H = H / ROWS;
renderInterval = setInterval(render, 30);

// draw a single square at (x, y)
function drawBlock(x, y) {
    context.fillRect(BLOCK_W * x, BLOCK_H * y, BLOCK_W - 1, BLOCK_H - 1);
}

// draws the board and the moving shape
function render() {
    context.clearRect(0, 0, W, H);

	//settled
    for (var x = 0; x < COLS; ++x) {
        for (var y = 0; y < ROWS; ++y) {
            if (board[y][x]) {
                context.fillStyle = colors[board[y][x] - 1];
                drawBlock(x, y);
            }
        }
    }

    //moving
    for (var y = 0; y < 4; ++y) {
        for (var x = 0; x < 4; ++x) {
            if (current[y][x]) {
                context.fillStyle = colors[current[y][x] - 1];
                drawBlock(currentX + x, currentY + y);
            }
        }
    }
    
}

//GAME CONTROLS


function newGame() {
	clearInterval(tickinterval);
	clearInterval(renderInterval);
	shuffleArray(colors);
	$('#newgame').hide();
	$('#gameover').hide();
	$("#board").css("-webkit-animation","blurout 1s forwards");
    init();
    newShape();
    gameLost = false;
    score = 0;
    $('#score').html(score);
    tickinterval = setInterval(tick, 400);
	renderInterval = setInterval(render, 30);
}

function pauseGame() {
    if (isPaused == false) {
        clearInterval(tickinterval);
        isPaused = true;
        $('#paused').show();
        $("#board").css("-webkit-animation","blurin 1s forwards");
    } else {
        tickinterval = setInterval(tick, 400);
        isPaused = false;
		$('#paused').hide();
		$("#board").css("-webkit-animation","blurout 1s forwards");
    }
}

function gameOver() {
	clearInterval(tickinterval);
	clearInterval(renderInterval);
	$.post("updatescore.php", "score="+score, function(data) {
		$("#statsinfo").html(data);
	 });
	$("#board").css("-webkit-animation","blurin 1s forwards");
	$('#gameover').show();
}

function handleKey(key) {
    switch (key) {
    case 'left':
        if (valid(-1) && isPaused==false && !gameLost) {
            --currentX;
        }
        break;
    case 'right':
        if (valid(1) && isPaused==false && !gameLost) {
            ++currentX;
        }
        break;
    case 'down':
        if (valid(0, 1) && isPaused==false && !gameLost) {
            ++currentY;
        }
        break;
    case 'rotate':
        var rotated = rotate(current);
        if (valid(0, 0, rotated) && isPaused==false && !gameLost) {
            current = rotated;
        }
        break;
	case 'pause':
		if (!gameLost && gameBegan==true) {
    		pauseGame();
    	}
		break;
    case 'end':
    	if (isPaused==false && gameBegan==true) {
	    	gameLost=true;
	    	gameBegan=false;
    		gameOver();
    	}
		break;
    case 'new':
    	if (isPaused==false) {
	    	gameBegan=true;
    		newGame();
    	}
		break;
    }
}

$(document.body).keydown(function(event){
	var pressedKey = event.keyCode;
	var controlKeys = { 37: 'left', 39: 'right', 40: 'down', 38: 'rotate', 80: 'pause', 78: 'new', 69: 'end' };
	if(pressedKey in controlKeys) {
		handleKey(controlKeys[pressedKey]);
		render();
    }
});

$(document).ready(function(){
	//$('#bgscore')[0].play();
	$('#gameover').hide();
	$('#paused').hide();
});
