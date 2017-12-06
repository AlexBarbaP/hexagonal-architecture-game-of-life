<?php
declare(strict_types=1);

namespace Application;

use Domain\Model\Size;

interface InputParserInterface
{
    /**
     * Parses data and returns a Size value object
     *
     * @param array $data
     *
     * @return Size
     */
    public function parse(array $data): Size;
}
