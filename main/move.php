<?php

session_start();

include_once 'util.php';

if(isset($_POST)){
    $from = $_POST['from'];
    $to = $_POST['to'];
    
    if(tryMove($_SESSION['player'], $from, $to)){
        switchTurn();
    }
}

header('Location: index.php');

?>