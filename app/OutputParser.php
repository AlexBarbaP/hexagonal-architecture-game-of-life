<?php
declare(strict_types=1);

namespace Application;

use Domain\Model\Board;

class OutputParser implements OutputParserInterface
{
    /**
     * @inheritdoc
     */
    public function parse(Board $board): array
    {
        return $board->toArray();
    }
}
