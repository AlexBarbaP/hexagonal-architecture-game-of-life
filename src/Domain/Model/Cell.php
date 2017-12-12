<?php
declare(strict_types=1);

namespace Domain\Model;

final class Cell
{
    public const UNPOPULATED = 0;
    public const POPULATED = 1;

    /** @var CellStatus */
    private $cellStatus;

    /**
     * @param CellStatus $cellStatus
     */
    public function __construct(CellStatus $cellStatus)
    {
        $this->cellStatus = clone $cellStatus;
    }

    /**
     * @return CellStatus
     */
    public function getCellStatus(): CellStatus
    {
        return clone $this->cellStatus;
    }
}
