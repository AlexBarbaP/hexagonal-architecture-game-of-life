<?php
declare(strict_types=1);

namespace Application;

use Domain\Model\Board;

class OutputParser implements OutputParserInterface
{
    /**
     * @inheritdoc
     */
    public function parse(Board $board): string
    {
        $boardGrid = $board->toArray();

        $output = '';

        foreach ($boardGrid as $row) {
            $output .= implode(' ', array_map(
                        function ($element) {
                            return $element ?: ' ';

                        }, $row)
                ) . "\n";
        }

        return $output;
    }
}
