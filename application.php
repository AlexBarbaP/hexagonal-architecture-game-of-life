#!/usr/bin/env php
<?php
/**
 * Script entry point to run Symfony console ConsoleCommands
 */
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Application\ConsoleCommands\DatabaseSimulationConsoleCommand;
use Application\ConsoleCommands\RandomSimulationConsoleCommand;
use Symfony\Component\Console\Application;

// create application
$application = new Application('Simulation of Life - Hexagonal Architecture', '1.0.0');

// create and register ConsoleCommands
$simulationConsoleCommand         = new RandomSimulationConsoleCommand();
$databaseSimulationConsoleCommand = new DatabaseSimulationConsoleCommand();

$application->add($simulationConsoleCommand);
$application->add($databaseSimulationConsoleCommand);

// run application
try {
    $application->run();
} catch (Exception $e) {
    echo "A problem has occurred when executing application.";
}
