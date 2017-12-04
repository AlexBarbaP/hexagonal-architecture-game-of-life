<?php
declare(strict_types=1);

namespace Tests\Domain\Model;

use Domain\Model\Size;
use PHPUnit\Framework\TestCase;

class SizeTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException Domain\Exception\InvalidSizeException
     */
    public function size_should_throw_exception_for_invalid_size()
    {
        new Size(0, 0);
    }
}
