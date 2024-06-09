<?php

session_start();

include_once 'util.php';

if(tryUndo()){
    switchTurn();
}

header('Location: index.php');

?>
