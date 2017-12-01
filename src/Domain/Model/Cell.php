<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidCellEnumException;

final class Cell
{
	public const UNPOPULATED = 0;
	public const POPULATED   = 1;

	/** @var int */
	private $value = 0;

	/**
	 * @param int $value
	 *
	 * @throws InvalidCellEnumException
	 */
	public function __construct(int $value)
	{
		if (
			$value !== self::UNPOPULATED
			&& $value !== self::POPULATED
		) {
			throw new InvalidCellEnumException();
		}

		$this->value = $value;
	}

	/**
	 * @return int
	 */
	public function __invoke()
	{
		return $this->value;
	}
}
