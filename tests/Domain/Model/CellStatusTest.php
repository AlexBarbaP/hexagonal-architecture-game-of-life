<?php
declare(strict_types=1);

namespace Tests\Domain\Model;

use App\Domain\Model\CellStatus;
use PHPUnit\Framework\TestCase;

class CellStatusTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException App\Domain\Exception\InvalidCellStatusEnumException
     */
    public function cell_status_should_throw_exception_for_invalid_cell_status_value()
    {
        new CellStatus(2);
    }
}
