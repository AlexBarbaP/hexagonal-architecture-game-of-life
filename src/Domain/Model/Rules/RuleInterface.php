<?php
declare(strict_types=1);

namespace App\Domain\Model\Rules;

use App\Domain\Model\CellStatus;

interface RuleInterface
{
    /**
     * Matches if cell conditions satisfy simulation rule
     *
     * @param CellStatus $cellStatus
     * @param int        $neighborCount
     *
     * @return bool
     */
    public function match(CellStatus $cellStatus, int $neighborCount): bool;

    /**
     * Returns new CellStatus based on simulation rule
     *
     * @return CellStatus
     */
    public function execute(): CellStatus;
}
