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

                if(!$emptyOnly || ($emptyOnly && !isset($board[$newPos])))
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

function findPaths($emptyTiles, $pos, $end, &$visited, $path, &$allPaths, $length = 0){
    list($x, $y) = explode(",", $pos);

    if ($pos === $end && ($length == 0 || count($path) == ($length+1))) {
        $allPaths[] = $path;
        return;
    }

    if ($length > 0 && count($path) > $length) {
        return;
    }

    $directions = $GLOBALS['OFFSETS'];

    foreach ($directions as $dir) {
        $newX = $x + $dir[0];
        $newY = $y + $dir[1];
        $newPos = $newX.",".$newY;

        if (in_array($newPos, $emptyTiles) && !isset($visited[$newPos])){
            $visited[$newPos] = true;
            findPaths($emptyTiles, $newPos, $end, $visited, array_merge($path, [$newPos]), $allPaths, $length);
            unset($visited[$newPos]);
        }
    }
}

function getAllPaths($emptyTiles, $start, $end, $length = 0) {
    $visited = [];
    $allPaths = [];
    $visited[$start] = true;

    findPaths($emptyTiles, $start, $end, $visited, [$start], $allPaths, $length);

    return $allPaths;
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

function moveSpider($board, $from, $to){
    //a: Een spin verplaatst zich door precies drie keer te verschuiven.
    //b. Een verschuiving is een zet zoals de bijenkoningin die mag maken.
    //c. Een spin mag zich niet verplaatsen naar het veld waar hij al staat. 
    //d. Een spin mag alleen verplaatst worden over en naar lege velden.
    //e. Een spin mag tijdens zijn verplaatsing geen stap maken naar een veld waar hij
        //tijdens de verplaatsing al is geweest.

    if($from == $to){
        $_SESSION['error'] = 'Tile must move';
        return false;
    }

    $emptyTiles = getPossibleMoves($board, true);

    $possiblePaths = getAllPaths($emptyTiles, $from, $to, 3);

    return !empty($possiblePaths);
}

function canMove($board, $player){
    $emptyTiles = getPossibleMoves($board, true);

    foreach($board as $from => $tile){
        $tileSize = count($tile);

        if($tile[$tileSize-1][0] != $player)
            continue;

        foreach($emptyTiles as $to){
            switch($tile[$tileSize-1][1]){
                case "S":
                    if(moveSpider($board, $from, $to))
                        return true;

                    break;
                case "A":
                    if(moveSoldierAnt($board, $from, $to))
                        return true;

                    break;
                case "G":
                    if(moveGrasshopper($board, $from, $to))
                        return true;

                    break;
                case "B":
                    if(!splitsHive($board, $to) && slide($board, $from, $to))
                        return true;

                    break;
                case "Q":
                    return true;
            }
        }
    }

    return false;
}

?>