<?php

namespace App\Generator;

use http\Exception\InvalidArgumentException;

class DroidPathGenerator
{
    public const RESULT_CRASHED = 'crashed';
    public const RESULT_LOST = 'lost';
    public const RESULT_SUCCESS = 'success';

    private const STEP_F0REWARD = 0;
    private const STEP_LEFT = -1;
    private const STEP_RIGHT = 1;

    private const ALLOWED_RESULTS = [
        self::RESULT_CRASHED,
        self::RESULT_LOST,
        self::RESULT_SUCCESS,
    ];
    private const ALLOWED_STEPS = [-1, 0, 1];

    /** @var int[]  */
    private array $path;
    private string $previousPathResult = self::RESULT_LOST;
    private int $direction;

    /**
     * @param int[] $initialPath
     */
    public function __construct(array $initialPath = [], int $initialDirection = self::STEP_RIGHT) {
        foreach ($initialPath as $step) {
            if (!\in_array($step, self::ALLOWED_STEPS)) {
                throw new \InvalidArgumentException(sprintf(
                    'Unrecognized initial path "%s"',
                    \implode(', ', $initialPath)
                ));
            }
        }

        if (!\in_array($initialDirection, self::ALLOWED_STEPS)) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown initial direction "%s"',
                $initialDirection
            ));
        }

        $this->path = $initialPath;
        $this->direction = $initialDirection;
    }

    /**
     * @param string $oldPathResult One of RESULT_CRASHED or RESULT_LOST
     * @return int[] new Path
     * @throws \InvalidArgumentException When old path result is not recognized
     */
    public function getNewPath(string $oldPathResult = self::RESULT_LOST): array
    {
        if (self::RESULT_SUCCESS === $oldPathResult) {
            return $this->path;
        }

        if (empty($this->path)) {
            \array_push($this->path, self::STEP_F0REWARD);

            return $this->path;
        }

        $lastStep = \array_pop($this->path);

        switch ($oldPathResult) {
            case self::RESULT_LOST:
                \array_push($this->path, $lastStep);
                \array_push($this->path, self::STEP_F0REWARD);
                $this->direction = 1;

                break;
            case self::RESULT_CRASHED:
                switch ($lastStep) {
                    case self::STEP_F0REWARD:
                        \array_push($this->path, $this->direction);

                        break;
                    case self::STEP_RIGHT:
                        $this->switchLeft($lastStep);
                        $this->direction = self::STEP_LEFT;

                        break;
                    default:
                        throw new \LogicException('Unhandled situation');
                }

                break;
            default:
                throw new \LogicException('Unhandled situation');
        }

        return $this->path;
    }

    private function switchLeft(int $lastStep): void
    {
        while (self::STEP_F0REWARD !== $lastStep) {
            $lastStep = \array_pop($this->path);
        }

        \array_push($this->path, $lastStep);
        \array_push($this->path, self::STEP_LEFT);
    }
}