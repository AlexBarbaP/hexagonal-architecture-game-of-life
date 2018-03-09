<?php
declare(strict_types=1);

namespace Domain\Model\Commands\Simulation;

use Domain\Model\PopulateStrategies\PopulateStrategyInterface;

class InitializeSimulationCommand
{
    /** @var int */
    private $height = 0;

    /** @var int */
    private $width = 0;

    /** @var PopulateStrategyInterface */
    private $populateStrategy;

    /**
     * @param int                       $height
     * @param int                       $width
     * @param PopulateStrategyInterface $populateStrategy
     */
    public function __construct(int $height, int $width, PopulateStrategyInterface $populateStrategy)
    {
        $this->height           = $height;
        $this->width            = $width;
        $this->populateStrategy = clone $populateStrategy;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * @return PopulateStrategyInterface
     */
    public function populateStrategy(): PopulateStrategyInterface
    {
        return clone $this->populateStrategy;
    }
}
