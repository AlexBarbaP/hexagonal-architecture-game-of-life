<?php
declare(strict_types=1);

namespace Domain\Model\Entities;

use Ramsey\Uuid\Uuid;

final class GameStatusId
{
    /** @var string */
    private $id;

    /**
     * @param null $id
     */
    private function __construct($id = null)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    /**
     * @param null $id
     *
     * @return GameStatusId
     */
    public static function create($id = null): GameStatusId
    {
        return new static($id);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
