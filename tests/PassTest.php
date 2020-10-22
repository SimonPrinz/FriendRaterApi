<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class PassTest extends TestCase
{
    public function testPass()
    {
        $this->assertTrue(true, 'always passes');
    }
}
