<?php

session_start();

include_once 'util.php';

$from = $_POST['from'];
$to = $_POST['to'];

$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];
unset($_SESSION['error']);

if (!isset($board[$from]))
    $_SESSION['error'] = 'Board position is empty';
elseif ($board[$from][count($board[$from])-1][0] != $player)
    $_SESSION['error'] = "Tile is not owned by player";
elseif (isset($hand['Q']))
    $_SESSION['error'] = "Queen bee is not played";
else {
    $tile = array_pop($board[$from]);
    if (!splitsHive($board, $to)){
        if ($from == $to) $_SESSION['error'] = 'Tile must move';
        elseif (isset($board[$to]) && $tile[1] != "B") $_SESSION['error'] = 'Tile not empty';
        elseif ($tile[1] == "B") {
            if (!slide($board, $from, $to))
                $_SESSION['error'] = 'Tile must slide';
        }
    }
    if (isset($_SESSION['error'])) {
        if (isset($board[$from])) array_push($board[$from], $tile);
        else $board[$from] = [$tile];
    } else {
        if (isset($board[$to])) array_push($board[$to], $tile);
        else $board[$to] = [$tile];
        $_SESSION['player'] = 1 - $_SESSION['player'];
        $db = include 'database.php';
        $state = get_state();
        $stmt = $db->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "move", ?, ?, ?, ?)');
        $stmt->bind_param('issis', $_SESSION['game_id'], $from, $to, $_SESSION['last_move'], $state);
        $stmt->execute();

        $_SESSION['last_move'] = $db->insert_id;

        if(empty($board[$from]))
            unset($board[$from]);
    }
    $_SESSION['board'] = $board;
}

header('Location: index.php');

?>