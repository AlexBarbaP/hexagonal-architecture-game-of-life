<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Model\PopulatorStrategies\PopulatorStrategyInterface;

final class Game
{
	/** @var Board */
	private $board = null;

	public function __construct(Size $size, PopulatorStrategyInterface $populatorStrategy)
	{
		$this->board = new Board($size, $populatorStrategy);
	}

	public function iterate(): void
	{
		//
	}

	/**
	 * @return Board
	 */
	public function getBoard(): Board
	{
		return $this->board;
	}
}
