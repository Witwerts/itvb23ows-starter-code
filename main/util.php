<?php

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function isNeighbour($a, $b) {
    $a = explode(',', $a);
    $b = explode(',', $b);
    if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) return true;
    if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) return true;
    if ($a[0] + $a[1] == $b[0] + $b[1]) return true;
    return false;
}

function hasNeighBour($a, $board) {
    foreach (array_keys($board) as $b) {
        if (isNeighbour($a, $b)) return true;
    }
}

function neighboursAreSameColor($player, $a, $board) {
    foreach ($board as $b => $st) {
        if (!$st) continue;
        $c = $st[count($st) - 1][0];
        if ($c != $player && isNeighbour($a, $b)) return false;
    }
    return true;
}

function len($tile) {
    return $tile ? count($tile) : 0;
}

function slide($board, $from, $to) {
    if (!hasNeighBour($to, $board)) return false;
    if (!isNeighbour($from, $to)) return false;
    $b = explode(',', $to);
    $common = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $p = $b[0] + $pq[0];
        $q = $b[1] + $pq[1];
        if (isNeighbour($from, $p.",".$q)) $common[] = $p.",".$q;
    }

    $f = array_key_exists($from, $board) ? $board[$from] : [];
    $t = array_key_exists($to, $board) ? $board[$to] : [];
    $m = array_key_exists($common[0], $board) ? $board[$common[0]] : [];
    $n = array_key_exists($common[1], $board) ? $board[$common[1]] : [];

    if (empty($m) && empty($n) && empty($f) && empty($t)) return false;
    return min(len($m), len($n)) <= max(len($f), len($t));
}

function getPossibleMoves($board, $emptyOnly = false){
    $to = [];

    if(!empty($board)){
        foreach ($GLOBALS['OFFSETS'] as $pq) {
            foreach (array_keys($board) as $pos) {
                $pq2 = explode(',', $pos);
                $newPos = ($pq[0] + $pq2[0]).','.($pq[1] + $pq2[1]);

                if(!$emptyOnly || ($emptyOnly && !isset($newPos)))
                    $to[] = $newPos;
            }
        }
    }
    else
        $to[] = '0,0';

    $to = array_unique($to);

    return $to;
}

function splitsHive($board, $to){
    if (!hasNeighBour($to, $board)){
        $_SESSION['error'] = "Move would split hive";
        return true;
    }
    else {
        $all = array_keys($board);
        $queue = [array_shift($all)];
        while ($queue) {
            $next = explode(',', array_shift($queue));
            foreach ($GLOBALS['OFFSETS'] as $pq) {
                list($p, $q) = $pq;
                $p += $next[0];
                $q += $next[1];
                if (in_array("$p,$q", $all)) {
                    $queue[] = "$p,$q";
                    $all = array_diff($all, ["$p,$q"]);
                }
            }
        }
        if ($all){
            $_SESSION['error'] = "Move would split hive";
            return true;
        }
    }

    return false;
}

function moveGrasshopper($board, $from, $to){
    //a. Een sprinkhaan verplaatst zich door in een rechte lijn een sprong te maken 
        //naar een veld meteen achter een andere steen in de richting van de sprong. 
    //b. Een sprinkhaan mag zich niet verplaatsen naar het veld waar hij al staat. 
    //c. Een sprinkhaan moet over minimaal één steen springen.
    //d. Een sprinkhaan mag niet naar een bezet veld springen.
    //e. Een sprinkhaan mag niet over lege velden springen. Dit betekent dat alle
        //velden tussen de start- en eindpositie bezet moeten zijn. 

    if($from == $to){
        $_SESSION['error'] = 'Tile must move';
        return false;
    }

    $fromPos = explode(',', $from);
    $toPos = explode(',', $to);

    $dx = ($toPos[0] - $fromPos[0]);
    $dy = ($toPos[1] - $fromPos[1]);

    if((($dx > 1 && $dy < -1) //rechtsboven
        || ($dx == 0 && $dy > 1) //rechtsonder
        || ($dx < -1 && $dy > 1) //linksonder
        || ($dx == 0 && $dy < -1))) //linksboven
        return false;

    if(isset($board[$to]))
        return false;

    $dx = max(-1, min($dx, 1));
    $dy = max(-1, min($dy, 1));

    $nx = $fromPos[0] + $dx;
    $ny = $fromPos[1] + $dy;
    $jumped = false;

    while(isset($board[$nx.",".$ny])){
        $nx += $dx;
        $ny += $dy;

        $jumped = true;
    }

    $nPos = $nx.",".$ny;

    return $jumped && $to == $nPos;
}

function moveSoldierAnt($board, $from, $to){
    //a. Een soldatenmier verplaatst zich door een onbeperkt aantal keren te
        //verschuiven
    //b. Een verschuiving is een zet zoals de bijenkoningin die mag maken 
    //c. Een soldatenmier mag zich niet verplaatsen naar het veld waar hij al staat. 
    //d. Een soldatenmier mag alleen verplaatst worden over en naar lege velden. 

    if($from == $to){
        $_SESSION['error'] = 'Tile must move';
        return false;
    }

    $emptyTiles = getPossibleMoves($board, true);

    return array_key_exists($to, $emptyTiles) && !splitsHive($board, $to);
}

?>