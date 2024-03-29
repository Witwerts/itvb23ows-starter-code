<?php

session_start();

include_once 'util.php';

if(isset($_POST)){
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

        if (!tryMove($board, $from, $to, $tile[1], true) || isset($_SESSION['error']))
            updateMove($board, $from, $tile);
        else {
            updateMove($board, $to, $tile);
            switchTurn();
            $db = include 'database.php';
            $state = get_state();
            $stmt = $db->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "move", ?, ?, ?, ?)');
            $stmt->bind_param('issis', $_SESSION['game_id'], $from, $to, $_SESSION['last_move'], $state);
            $stmt->execute();

            $_SESSION['last_move'] = $db->insert_id;
        }
        $_SESSION['board'] = $board;
    }
}

header('Location: index.php');

?>