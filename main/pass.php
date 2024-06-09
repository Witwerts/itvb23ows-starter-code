<?php

session_start();

include_once 'util.php';

$player = $_SESSION['player'];

if(tryPass($player)){
    switchTurn();
}

header('Location: index.php');

?>