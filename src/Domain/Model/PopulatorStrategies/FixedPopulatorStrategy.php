<?php
declare(strict_types=1);

namespace Domain\Model\PopulatorStrategies;

use Domain\Exception\InvalidGridException;
use Domain\Exception\InvalidSizeException;

final class FixedPopulatorStrategy implements PopulatorStrategyInterface
{
	/**
	 * @var array
	 */
	private $grid = [];

	/**
	 * @param array $grid
	 *
	 * @throws InvalidGridException
	 */
	public function __construct(array $grid)
	{
		if (count($grid) <= 0) {
			throw new InvalidGridException();
		}

		if (count($grid[0]) <= 0) {
			throw new InvalidGridException();
		}

		$this->grid = $grid;
	}

	/**
	 * @param array $grid
	 *
	 * @return array
	 *
	 * @throws InvalidSizeException
	 */
	public function populate(array $grid): array
	{
		for ($y = 0; $y < count($grid); $y++) {

			for ($x = 0; $x < count($grid[$y]); $x++) {
				if (!isset($this->grid[$y][$x])) {
					throw new InvalidSizeException();
				}

				$grid[$y][$x] = $this->grid[$y][$x];
			}
		}

		return $grid;
	}
}
