<?php

namespace App\Generator;

class DroidPathGenerator
{
    public const RESULT_CRASHED = 'crashed';
    public const RESULT_LOST = 'lost';
    public const RESULT_SUCCESS = 'success';

    /** @var int[]  */
    private array $path;

    /**
     * @param int $initialPath
     */
    public function __construct(array $initialPath = [])
    {
        $this->path = $initialPath;
    }

    /**
     * @param string $oldPathResult One of RESULT_CRASHED or RESULT_LOST
     * @return int[]
     */
    public function getNewPath(string $oldPathResult): array
    {
        $nextStep = \end($this->path);
        \array_push($this->path, $nextStep);

        return $this->path;
    }
}