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
        $this->pathGenerator = new DroidPathGenerator();
    }

    /**
     * @covers ::__construct
     */
    public function testThrowsWhenInitialPathIsUnknown(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new DroidPathGenerator([1, 5, -1]);
    }

    /**
     * @covers ::getNewPath
     */
    public function testFirstStepIsForward(): void
    {
        self::assertSame([0], $this->pathGenerator->getNewPath());
    }

    /**
     * @covers ::getNewPath
     */
    public function testKeepsForwardIfForwardLost(): void
    {
        $this->pathGenerator->getNewPath();
        self::assertSame([0, 0], $this->pathGenerator->getNewPath('lost'));
    }

}

