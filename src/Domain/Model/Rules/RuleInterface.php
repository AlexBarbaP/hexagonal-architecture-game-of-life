<?php
declare(strict_types=1);

namespace Domain\Model\Rules;

use Domain\Model\CellStatus;

interface RuleInterface
{
    /**
     * Matches if cell conditions satisfy game rule
     *
     * @param CellStatus $cellStatus
     * @param int        $neighborCount
     *
     * @return bool
     */
    public function match(CellStatus $cellStatus, int $neighborCount): bool;

    /**
     * Returns new CellStatus based on game rule
     *
     * @return CellStatus
     */
    public function execute(): CellStatus;
}
