<?php

namespace App\Generator;

class DroidPathGenerator
{
    public const RESULT_CRASHED = 'crashed';
    public const RESULT_LOST = 'lost';
    public const RESULT_SUCCESS = 'success';

    /** @var int[]  */
    private array $path;
    private string $previousPathResult = self::RESULT_LOST;
    private int $direction = 1;

    /**
     * @param int[] $initialPath
     */
    public function __construct(array $initialPath = []) {
        $this->path = $initialPath;
    }

    /**
     * @param string $oldPathResult One of RESULT_CRASHED or RESULT_LOST
     * @return int[]
     */
    public function getNewPath(string $oldPathResult): array
    {
        if (self::RESULT_SUCCESS === $oldPathResult) {
            return $this->path;
        }

        if (self::RESULT_CRASHED === $oldPathResult && self::RESULT_CRASHED == $this->previousPathResult) {
            \array_pop($this->path);
        }

        $nextStep = \end($this->path);
        if (self::RESULT_CRASHED === $oldPathResult) {
            $lastStep = \array_pop($this->path);
            $nextStep = self::changeStep($lastStep);
        }

        \array_push($this->path, $nextStep);
        $this->previousPathResult = $oldPathResult;

        return $this->path;
    }

    private function changeStep(int $step): int
    {
        $step += $this->direction;

        if (-1 > $step || 1 < $step) {
            $step = 0;
            $this->direction *= -1;
        }

        return $step;
    }
}