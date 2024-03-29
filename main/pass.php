<?php

session_start();

include_once 'util.php';

$board = $_SESSION['board'];
$player = $_SESSION['player'];

if(isset($board) && !canMove($board, $player)){
    $db = include 'database.php';
    $state = get_state();
    $stmt = $db->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "pass", null, null, ?, ?)');
    $stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $state);
    $stmt->execute();
    $_SESSION['last_move'] = $db->insert_id;
    $_SESSION['player'] = 1 - $_SESSION['player'];
}

header('Location: index.php');

?>