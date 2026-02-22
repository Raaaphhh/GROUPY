<?php 
use PHPUnit\Framework\TestCase;

class testTest extends TestCase
{
    public function testExemple()
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    public function testExemple2(){
        $result = "hello";
        $this->assertEquals("hello", $result);
    }
}

?>