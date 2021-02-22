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
    public function testSwitchesForwardWhenCrashedOnRight(): void
    {
        self::assertSame([1, 0], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testSwitchesForwardWhenCrashedOnLeft(): void
    {
        $this->pathGenerator = new DroidPathGenerator([1, -1]);
        self::assertSame([1, 0], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testGoesBackAndSwitchesForwardAfterCrashingRightAndForward(): void
    {
        $this->pathGenerator->getNewPath('crashed'); //[1, 0]
        self::assertSame([0], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testGoesBackAndSwitchesForwardAfterCrashingLeftAndForward(): void
    {
        $this->pathGenerator = new DroidPathGenerator([-1, -1]);
        $this->pathGenerator->getNewPath('crashed'); //[-1, 0]
        self::assertSame([0], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testGoesBackAndSwitchesForwardRightAfterCrashingLeftAndForward(): void
    {
        $this->pathGenerator = new DroidPathGenerator([-1, -1]);
        $this->pathGenerator->getNewPath('crashed'); //[-1, 0]
        $this->pathGenerator->getNewPath('crashed'); //[0]
        $this->pathGenerator->getNewPath('lost'); //[0, 0]
        self::assertSame([0, 1], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testGoesBackAndSwitchesForwardLeftAfterCrashingRightAndForward(): void
    {
        $this->pathGenerator->getNewPath('crashed'); //[1, 0]
        $this->pathGenerator->getNewPath('crashed'); //[0]
        $this->pathGenerator->getNewPath('lost'); //[0, 0]
        self::assertSame([0, -1], $this->pathGenerator->getNewPath('crashed'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testRetunsOldPathOnSuccess(): void
    {
        self::assertSame([1, 1], $this->pathGenerator->getNewPath('success'));
    }

    /**
     * @covers ::getNewPath
     */
    public function testThrowsOnUnknownResult(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->pathGenerator->getNewPath('unknown');
    }
}

