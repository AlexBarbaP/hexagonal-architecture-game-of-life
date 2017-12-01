<?php
declare(strict_types=1);

namespace Domain\Model\PopulatorStrategies;

use Domain\Model\Cell;
use Domain\Model\Size;

interface PopulatorStrategyInterface
{
	/**
	 * Returns a grid array of Cells populated using some population strategy
	 *
	 * @param Cell[] $grid
	 *
	 * @return Cell[]
	 */
	public function populate(array $grid): array;
}
