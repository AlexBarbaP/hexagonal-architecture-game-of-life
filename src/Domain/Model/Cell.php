<?php
declare(strict_types=1);

namespace Domain\Model;

final class Cell
{
    public const UNPOPULATED = 0;
    public const POPULATED = 1;

    /** @var CellStatus */
    private $cellStatus;

    /** @var Coordinate */
    private $coordinate;

    /**
     * @param Coordinate $coordinate
     * @param CellStatus $cellStatus
     */
    public function __construct(Coordinate $coordinate, CellStatus $cellStatus)
    {
        $this->coordinate = clone $coordinate;
        $this->cellStatus = clone $cellStatus;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinate(): Coordinate
    {
        return clone $this->coordinate;
    }

    /**
     * @return CellStatus
     */
    public function getCellStatus(): CellStatus
    {
        return clone $this->cellStatus;
    }
}
