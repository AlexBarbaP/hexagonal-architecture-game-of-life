<?php
declare(strict_types=1);

namespace Tests\Domain\Model\PopulateStrategies;

use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use PHPUnit\Framework\TestCase;

class FixedPopulateStrategyTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException Domain\Exception\InvalidGridException
     */
    public function fixed_populate_strategy_should_throw_exception_for_one_dimension_array()
    {
        new FixedPopulateStrategy([]);
    }

    /**
     * @test
     *
     * @expectedException Domain\Exception\InvalidGridException
     */
    public function fixed_populate_strategy_should_throw_exception_for_empty_second_dimension_array()
    {
        new FixedPopulateStrategy([[]]);
    }

    /**
     * @test
     *
     * @expectedException Domain\Exception\InvalidSizeException
     */
    public function fixed_populate_strategy_should_throw_exception_for_different_dimensions_grid_and_board()
    {
        $fixedPopulateStrategy = new FixedPopulateStrategy([[0]]);

        $fixedPopulateStrategy->populate([[0,0],[0,0]]);
    }
}
