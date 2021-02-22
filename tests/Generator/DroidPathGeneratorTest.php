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
        $this->pathGenerator = new DroidPathGenerator([1, 1]);
    }

    /**
     * @covers ::getNewPath
     */
    public function testAdsRightWhenGoneAndLastStepWasRight(): void
    {
        self::assertSame([1, 1, 1], $this->pathGenerator->getNewPath('lost'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testAdsForwardWhenCrashedAndLastStepWasRight(): void
    {
        self::assertSame([1, 1, 0], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testGoesForwardWhenCrashedTwice(): void
    {
        $this->pathGenerator->getNewPath('crashed'); //[1, 1, 0]
        self::assertSame([1, 0], $this->pathGenerator->getNewPath('crashed'));
    }
}

