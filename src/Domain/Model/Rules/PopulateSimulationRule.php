<?php
declare(strict_types=1);

namespace Domain\Model\Rules;

use Domain\Model\CellStatus;

final class PopulateSimulationRule implements RuleInterface
{
    /**
     * @param CellStatus $cellStatus
     * @param int        $neighborCount
     *
     * @return bool
     */
    public function match(CellStatus $cellStatus, int $neighborCount): bool
    {
        if (
            $cellStatus() == CellStatus::UNPOPULATED
            && $neighborCount == 3
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return CellStatus
     */
    public function execute(): CellStatus
    {
        return new CellStatus(CellStatus::POPULATED);
    }
}
