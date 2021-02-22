<?php

namespace App\Generator;

use http\Exception\InvalidArgumentException;

class DroidPathGenerator
{
    public const RESULT_CRASHED = 'crashed';
    public const RESULT_LOST = 'lost';
    public const RESULT_SUCCESS = 'success';

    private const ALLOWED_RESULTS = [
        self::RESULT_CRASHED,
        self::RESULT_LOST,
        self::RESULT_SUCCESS,
    ];
    private const ALLOWED_STEPS = [-1, 0, 1];

    /** @var int[]  */
    private array $path;
    private string $previousPathResult = self::RESULT_LOST;
    private int $direction = 1;

    /**
     * @param int[] $initialPath
     */
    public function __construct(array $initialPath = []) {
        foreach ($initialPath as $step) {
            if (!\in_array($step, self::ALLOWED_STEPS)) {
                throw new \InvalidArgumentException(sprintf(
                    'Unrecognized initial path "%s"',
                    \implode(', ', $initialPath)
                ));
            }
        }

        $this->path = $initialPath;
    }

    /**
     * @param string $oldPathResult One of RESULT_CRASHED or RESULT_LOST
     * @return int[]
     * @throws \InvalidArgumentException When old path result is not recognized
     */
    public function getNewPath(string $oldPathResult = self::RESULT_LOST): array
    {
        if (!\in_array($oldPathResult, self::ALLOWED_RESULTS)) {
            throw new \InvalidArgumentException(\sprintf('Unknown result "%s"', $oldPathResult));
        }

        if (self::RESULT_SUCCESS === $oldPathResult) {
            return $this->path;
        }

        if (self::RESULT_CRASHED === $oldPathResult && self::RESULT_CRASHED == $this->previousPathResult) {
            \array_pop($this->path);
        }

        $nextStep = 0 < \count($this->path) ? \end($this->path) : 0;


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