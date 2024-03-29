<?php
    use PHPUnit\Framework\TestCase;
    require_once 'main/util.php';

    class TestFeatures extends TestCase {
        //Feature 1: grasshopper

        //A
        public function testGrasshopperJump(){
            $board = [
                '0,0' => [[0, 'Q']],  
                '0,1' => [[1, 'Q']],  
                '-1,0' => [[0, 'G']],  
                '1,1' => [[1, 'S']]
            ];

            $this->assertFalse(moveGrasshopper($board, '-1,0', '2,0'));
            $this->assertTrue(moveGrasshopper($board, '-1,0', '1,0'));
        }
        
        //B
        public function testGrasshopperPosition(){
            $board = [
                '0,0' => [[0, 'Q']],  
                '0,1' => [[1, 'Q']],  
                '-1,0' => [[0, 'G']],  
                '1,1' => [[1, 'S']]
            ];

            $this->assertFalse(moveGrasshopper($board, "-1,0", "-1,0"));
            $this->assertTrue(moveGrasshopper($board, '-1,0', '1,0'));
        }

        //C
        public function testGrasshopperMovement(){
            $board = [
                '0,0' => [[0, 'Q']],  
                '0,1' => [[1, 'Q']],  
                '-1,0' => [[0, 'G']],  
                '1,1' => [[1, 'S']]
            ];

            $this->assertFalse(moveGrasshopper($board, '-1,0', '2,0'));
        }

        //D
        public function testGrasshopperTaken(){
            $board = [
                '0,0' => [[0, 'Q']],  
                '0,1' => [[1, 'Q']],  
                '-1,0' => [[0, 'G']],  
                '1,1' => [[1, 'S']]
            ];

            $this->assertFalse(moveGrasshopper($board, '-1,0', '0,0'));
        }

        //E
        public function testGrasshopperEmpty(){
            $board = [
                '0,0' => [[0, 'Q']],  
                '0,1' => [[1, 'Q']],  
                '-1,0' => [[0, 'G']],  
                '1,1' => [[1, 'S']]
            ];

            $this->assertFalse(moveGrasshopper($board, '-1,0', '2,0'));
        }

        //Feature 2: soldier ant
        public function testSoldierantMovement(){
            $board = [
                '0,0' => [[0, 'Q']],
                '0,1' => [[1, 'Q']],
                '-1,0' => [[0, 'S']],
                '1,1' => [[1, 'S']],
                '-2,0' => [[0, 'A']],
                '2,1' => [[1, 'A']],
                '-3,0' => [[0, 'G']],
                '3,1' => [[1, 'G']],
            ];

            $this->assertFalse(moveSoldierAnt($board, '-2,0', '-1,1'));
        }

        //Feature 3: spider
        public function testSpiderMovement(){
            $board = [
                '0,0' => [[0, 'Q']],
                '0,1' => [[1, 'Q']],
                '-1,0' => [[0, 'S']],
                '1,1' => [[1, 'S']],
                '-2,0' => [[0, 'A']],
                '2,1' => [[1, 'A']],
                '-3,0' => [[0, 'G']],
                '3,1' => [[1, 'G']],
            ];

            $this->assertFalse(moveSpider($board, '-1,0', '0,1'));
            $this->assertTrue(moveSpider($board, '-1,0', '-3,-1'));
        }

        //Feature 4: pass if there is no moves possible
        public function testPass(){
            $board = [
                '0,0' => [[0, 'Q']],
                '0,1' => [[1, 'Q']],
                '-1,0' => [[0, 'S']],
                '1,1' => [[1, 'S']],
                '-2,0' => [[0, 'A']],
                '2,1' => [[1, 'A']],
                '-3,0' => [[0, 'G']],
                '3,1' => [[1, 'G']],
            ];

            $this->assertTrue(canMove($board, 0));
        }

        //Feature 5: win/tie/loss

        //A
        public function testWin(){
            $board = [
                '0,0' => [[0, 'Q']],
                '1,0' => [[1, 'S']],
                '1,-1' => [[1, 'A']],
                '0,-1' => [[0, 'G']],
                '-1,-1' => [[1, 'G']],
                '-1,0' => [[0, 'S']],
                '-1,1' => [[1, 'A']],
                '0,1' => [[0, 'B']],
                '1,1' => [[1, 'B']],
            ];

            $this->assertTrue(gameOver($board, 1, 0));
            $this->assertTrue(gameOver($board, 1, 1));
        }

        //B
        public function testTie(){
            $board = [
                '0,0' => [[0, 'Q']],
                '1,0' => [[1, 'Q']],
                '2,0' => [[0, 'A']],
                '1,-1' => [[1, 'S']],
                '0,-1' => [[0, 'S']],
                '-1,0' => [[1, 'A']],
                '-1,1' => [[0, 'G']],
                '0,1' => [[1, 'G']],
                '1,1' => [[0, 'B']],
                '2,-1' => [[1, 'B']],
            ];

            $this->assertTrue(gameOver($board, 0, 1));
            $this->assertTrue(gameOver($board, 1, 1));
        }
    }
?>