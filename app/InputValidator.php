<?php
declare(strict_types=1);

namespace Application;

use Application\Exceptions\InvalidInputException;
use App\Domain\Model\Size;

class InputValidator implements ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function validate(array $input): void
    {
        $size = new Size((int)$input['height'], (int)$input['width']);

        if ($size->getHeight() < 5 || $size->getWidth() < 5) {
            throw new InvalidInputException();
        }
    }
}
