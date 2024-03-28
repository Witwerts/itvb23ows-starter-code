<?php

session_start();

$db = include 'database.php';

if(isset($_SESSION['last_move'])){
    $stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
    $stmt->execute();
    $currMove = $stmt->get_result()->fetch_array();

    if(!empty($currMove)){
        $db->prepare('DELETE FROM moves where id = '.$currMove["id"])->execute();
        $prevId = $currMove["previous_id"];

        if(!is_null($prevId)){
            $stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$prevId);
            $stmt->execute();
            $oldMove = $stmt->get_result()->fetch_array();
    
            $_SESSION['last_move'] = $prevId;

            if(!empty($oldMove))
                set_state($oldMove["state"]);
        }
        else {
            header('Location: restart.php');
            return;
        }
    }
}

header('Location: index.php');

?>
