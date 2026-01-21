<?php
session_start();

/**
 * 1. GAME LOGIC
 */

// Initialize or Reset the game state
if (!isset($_SESSION['board']) || isset($_GET['reset'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['turn'] = 'X';
    $_SESSION['winner'] = null;
}

// Process a move
if (isset($_GET['cell']) && $_SESSION['winner'] == null) {
    $cell = (int)$_GET['cell'];
    
    // Check if the cell is valid and empty
    if (isset($_SESSION['board'][$cell]) && $_SESSION['board'][$cell] == '') {
        $_SESSION['board'][$cell] = $_SESSION['turn'];
        
        if (checkWin($_SESSION['board'], $_SESSION['turn'])) {
            $_SESSION['winner'] = $_SESSION['turn'];
        } elseif (!in_array('', $_SESSION['board'])) {
            $_SESSION['winner'] = 'Tie';
        } else {
            // Switch turns
            $_SESSION['turn'] = ($_SESSION['turn'] == 'X') ? 'O' : 'X';
        }
    }
}

/**
 * 2. WIN CHECKER FUNCTION
 */
function checkWin($board, $player) {
    $win_patterns = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Horizontal
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Vertical
        [0, 4, 8], [2, 4, 6]             // Diagonal
    ];
    foreach ($win_patterns as $p) {
        if ($board[$p[0]] == $player && $board[$p[1]] == $player && $board[$p[2]] == $player) {
            return true;
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Tic-Tac-Toe</title>
    <style>
        :root {
            --bg-color: #406a94;
            --cell-size: 100px;
            --x-color: #ff4757;
            --o-color: #2e86de;
            --board-bg: #ffffff;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .status-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .status-header h2 {
            margin: 5px 0;
            color: #2f3542;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* The Game Board */
        .board {
            display: grid;
            grid-template-columns: repeat(3, var(--cell-size));
            grid-template-rows: repeat(3, var(--cell-size));
            gap: 12px;
            padding: 15px;
            background: var(--board-bg);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        /* The Cells */
        .cell {
            width: var(--cell-size);
            height: var(--cell-size);
            background-color: #f1f2f6;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        /* Interactive state for empty cells */
        a.cell:hover {
            background-color: #dfe4ea;
            transform: translateY(-2px);
        }

        /* Colors for Markers */
        .cell.X { color: var(--x-color); }
        .cell.O { color: var(--o-color); }

        /* Game Over Styles */
        .result-box {
            margin-top: 25px;
            text-align: center;
            animation: fadeIn 0.5s ease-in;
        }

        .winner-text {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2ed573;
            margin-bottom: 15px;
        }

        .btn-reset {
            display: inline-block;
            text-decoration: none;
            color: white;
            background: #2f3542;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-reset:hover {
            background: #57606f;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="status-header">
        <?php if ($_SESSION['winner'] == null): ?>
            <h2>Player <span style="color: <?php echo ($_SESSION['turn'] == 'X' ? 'var(--x-color)' : 'var(--o-color)'); ?>">
                <?php echo $_SESSION['turn']; ?></span>'s Turn</h2>
        <?php else: ?>
            <h2>Game Over</h2>
        <?php endif; ?>
    </div>

    

    <div class="board">
        <?php foreach ($_SESSION['board'] as $index => $value): ?>
            <?php if ($value == '' && $_SESSION['winner'] == null): ?>
                <a href="?cell=<?php echo $index; ?>" class="cell"></a>
            <?php else: ?>
                <div class="cell <?php echo $value; ?>"><?php echo $value; ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php if ($_SESSION['winner']): ?>
        <div class="result-box">
            <div class="winner-text">
                <?php 
                    if ($_SESSION['winner'] == 'Tie') {
                        echo "It's a Draw! 🤝";
                    } else {
                        echo "Player " . $_SESSION['winner'] . " Wins! 🎉";
                    }
                ?>
            </div>
            <a href="?reset=1" class="btn-reset">Play Again</a>
        </div>
    <?php else: ?>
        <div style="margin-top: 20px;">
            <a href="?reset=1" style="color: #747d8c; text-decoration: none; font-size: 0.9rem;">Reset Board</a>
        </div>
    <?php endif; ?>

</body>
</html>