<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OutputTest extends TestCase
{
    public function testExpectFooActualFoo()
    {
        $this->expectOutputString('foo');
        print 'foo';
    }

    public function testExpectBarActualBaz()
    {
        $this->expectOutputString('bar');
        print 'bar';
    }

    public function testEquality() {
        $this->assertEquals(
            array(0,0,0,0,0,0,0,0,0,0,0,0,1,2,'33' ,4,5,6),
            array(0,0,0,'0',0,0,0,0,0,0,0,0,1,2,33,4,5,6)
        );
    }
}
