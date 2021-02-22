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
        $this->pathGenerator = new DroidPathGenerator('fr');
    }

    /**
     * @covers ::getNewPath
     */
    public function testNewPathElementIsAdded(): void
    {
        self::assertContains($this->pathGenerator->getNewPath(410), ['frr', 'frf']);
    }
}

