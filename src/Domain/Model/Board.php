<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Model\PopulatorStrategies\PopulatorStrategyInterface;

final class Board
{
	/** @var array */
	private $grid = [];

	/** @var PopulatorStrategyInterface */
	private $populatorStrategy;

	/**
	 * @param Size                       $size
	 * @param PopulatorStrategyInterface $populatorStrategy
	 */
	public function __construct(Size $size, PopulatorStrategyInterface $populatorStrategy)
	{
		$this->grid = array_fill(0, $size->getHeight(), []);

		array_map(
			function(&$row) use ($size) {
				$row = array_fill(0, $size->getWidth(), new Cell(Cell::UNPOPULATED));
			},
			$this->grid
		);

		$this->populatorStrategy = clone $populatorStrategy;

		$this->grid = $populatorStrategy->populate($this->grid);
	}

	/**
	 * @return array
	 */
	public function getGrid(): array
	{
		return $this->grid;
	}
}
