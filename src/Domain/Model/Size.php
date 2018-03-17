<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\InvalidSizeException;

final class Size
{
    /** @var int */
    private $height = 0;

    /** @var int */
    private $width = 0;

    /**
     * @param int $height
     * @param int $width
     *
     * @throws InvalidSizeException
     */
    public function __construct(int $height, int $width)
    {
        if ($height <= 0 || $width <= 0) {
            throw new InvalidSizeException();
        }

        $this->height = $height;
        $this->width  = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }
}
