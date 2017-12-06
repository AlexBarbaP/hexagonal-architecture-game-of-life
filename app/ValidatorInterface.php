<?php
declare(strict_types=1);

namespace Application;

use Application\Exceptions\InvalidInputException;

interface ValidatorInterface
{
    /**
     * Validates data
     *
     * @param array $input
     *
     * @throws InvalidInputException
     */
    public function validate(array $input): void;
}
