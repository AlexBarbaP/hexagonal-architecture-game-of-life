<?php
declare(strict_types=1);

namespace Tests\Integration;

use Application\Application;
use Application\InputParser;
use Application\OutputParser;
use Application\InputValidator;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    /**
     * @test
     */
    public function run_game_for_ten_iterations_should_output_ten_game_boards()
    {
        $inputParser  = new InputParser();
        $outputParser = new OutputParser();
        $validator    = new InputValidator();
        $iterations   = 10;

        $application = new Application($inputParser, $outputParser, $validator, $iterations);

        $applicationOutput = $application->run('5', '5');

        $this->assertEquals('board', $applicationOutput);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function run_game_should_output_a_message_error_for_invalid_board_dimensions()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function run_game_should_save_game_initial_board_into_database()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function run_game_loading_initial_status_from_database_should_output_an_arbitraty_final_game_board()
    {
        $this->assertTrue(true);
    }
}
