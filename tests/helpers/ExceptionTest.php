<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExceptionTest extends TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Right Message
     */
    public function testExceptionHasRightMessage()
    {
        throw new InvalidArgumentException('Right Message', 10);
    }

    /**
     * @expectedException              InvalidArgumentException
     * @expectedExceptionMessageRegExp #Right.*#
     */
    public function testExceptionMessageMatchesRegExp()
    {
        // Optional: Test anything here, if you want.
        $this->assertTrue(TRUE, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
        throw new InvalidArgumentException('Right Message', 10);
    }

    /**
     * @expectedException     InvalidArgumentException
     * @expectedExceptionCode 20
     */
    public function testExceptionHasRightCode()
    {
        throw new InvalidArgumentException('Some Message', 20);
    }
}
