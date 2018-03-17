<?php
declare(strict_types=1);

namespace Application;

use App\Domain\Model\Size;

class InputParser implements InputParserInterface
{
    /**
     * @inheritdoc
     */
    public function parse(array $input): Size
    {
        $size = new Size((int)$input['height'], (int)$input['width']);

        return $size;
    }
}
