<?php

session_start();

include_once 'util.php';

if(isset($_POST)){
    $piece = $_POST['piece'];
    $to = $_POST['to'];
    $player = $_SESSION['player'];

    if(tryPlay($player, $piece, $to)){
        switchTurn();
    }
}

header('Location: index.php');

?>