<?php
    use PHPUnit\Framework\TestCase;
    require_once 'main/util.php';

    class UnitTests extends TestCase {
        //Bug 1
        public function testPlay(){
            $player = 0;
            $board = [];
            $hand = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
    
            $this->assertEquals(true, checkPlay($player, $board, $hand[0], '0,0', 'Q'));
            $this->assertEquals(true, tryPlay($player, 'Q', '0,0'));
        }

        //Bug 2
        public function testQueenMovement(){
            $board = [];
            $hand = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
    
            $this->assertEquals(true, tryPlay(0, 'Q', '0,0'));
            switchTurn();

            $this->assertEquals(true, tryPlay(1, 'Q', '1,0'));
            switchTurn();

            $this->assertEquals(true, tryPlay(0, '0,0', '0,1'));
        }

        //Bug 3
        public function testFourthTurn(){
            $board = [];
            $hand = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
    
            $this->assertEquals(true, tryPlay(0, 'B', '0,0'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(1, 'S', '0,1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(0, 'B', '0,-1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(1, 'S', '0,2'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(0, 'S', '0,-2'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(1, 'B', '1,1'));
            switchTurn(); //only when true
            $this->assertEquals(false, tryPlay(1, 'B', '1,1')); //error: Queen bee is not played
        }

        //Bug 4
        public function testPositionUpdate(){
            $board = [];
            $hand = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
        
            $this->assertEquals(true, tryPlay(0, 'B', '0,0'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(1, 'S', '0,1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(0, 'B', '0,-1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(1, 'S', '0,2'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryMove(0, '0,0', '0,-1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryMove(1, '0,-1', '1,0'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryMove(0, '-1,0', '0,0'));
        }

        //Bug 5
        public function testUndo(){
            $board = [];
            $hand = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
    
            $this->assertEquals(true, tryPlay(0, 'B', '0,0'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(1, 'S', '0,1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryPlay(0, 'B', '0,-1'));
            switchTurn(); //only when true
            $this->assertEquals(true, tryUndo());
            switchTurn(); //switches back to white
        }

    }
?>