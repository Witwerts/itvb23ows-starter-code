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
    }
?>