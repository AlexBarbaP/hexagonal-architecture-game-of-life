<?php
declare(strict_types=1);

namespace Domain\Model;

final class Simulation
{
    /**
     * @param int $height
     * @param int $width
     */
    public function __construct(int $height, int $width)
    {
    }

    /**
     *
     */
    public function iterate(): void
    {
    }

    /**
     * @return Board
     */
    public function getBoard(): Board
    {
    }
}
