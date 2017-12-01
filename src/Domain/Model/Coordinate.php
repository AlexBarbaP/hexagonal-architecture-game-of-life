<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidCoordinateException;

final class Coordinate
{
	/** @var int */
	private $x = 0;

	/** @var int */
	private $y = 0;

	/**
	 * @param int $x
	 * @param int $y
	 *
	 * @throws InvalidCoordinateException
	 */
	public function __construct(int $x, int $y)
	{
		if ($x < 0 || $y < 0) {
			throw new InvalidCoordinateException();
		}

		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * @return int
	 */
	public function getX(): int
	{
		return $this->x;
	}

	/**
	 * @return int
	 */
	public function getY(): int
	{
		return $this->y;
	}
}
