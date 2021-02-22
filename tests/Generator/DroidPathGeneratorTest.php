<?php

namespace App\Generator;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Generator\DroidPathGenerator
 */
class DroidPathGeneratorTest extends TestCase
{
    private DroidPathGenerator $pathGenerator;

    protected function setUp(): void
    {
        $this->pathGenerator = new DroidPathGenerator([0, 1]);
    }

    /**
     * @covers ::getNewPath
     */
    public function testSamePathIsAddedWhenGone(): void
    {
        self::assertSame([0, 1, 1],$this->pathGenerator->getNewPath('lost'));
    }
}

