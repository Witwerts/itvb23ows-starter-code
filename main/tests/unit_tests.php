<?php
    use PHPUnit\Framework\TestCase;
    require_once 'main/util.php';

    class UnitTests extends TestCase {
        //Bug 1
        public function testPlay(){
            $player = 0;
            $board = [];
            $hand = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
    
            $this->assertEquals(true, tryPlay($player, $board, $hand[0], '0,0', 'Q'));
        }

        //Bug 2
        //Bug 3
        //Bug 4
        //Bug 5
    }
?>