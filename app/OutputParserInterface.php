<?php
declare(strict_types=1);

namespace Application;

use Domain\Model\Board;

interface OutputParserInterface
{
    /**
     * Parses data and returns a Size value object
     *
     * @param Board $board
     *
     * @return array
     */
    public function parse(Board $board): array;
}
