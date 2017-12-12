<?php
declare(strict_types=1);

namespace Domain\Model;

final class Cell
{
    public const UNPOPULATED = 0;
    public const POPULATED = 1;

    /**
     * @param CellStatus $cellStatus
     */
    public function __construct(CellStatus $cellStatus)
    {
    }

    /**
     * @return CellStatus
     */
    public function getCellStatus(): CellStatus
    {
    }
}
