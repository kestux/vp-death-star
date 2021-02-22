<?php

namespace App\Generator;

class DroidPathGenerator
{
    private string $previousPath;

    public function __construct(string $initialPath = '')
    {
        $this->previousPath = $initialPath;
    }

    public function getNewPath(int $oldPathResult): string
    {
        return $this->previousPath . 'f';
    }
}