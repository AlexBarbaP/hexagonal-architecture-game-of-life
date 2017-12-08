<?php
declare(strict_types=1);

namespace Domain\Model\Entities;

final class GameStatus
{
    /** @var GameStatusId */
    private $id;

    /** @var string */
    private $status;

    /**
     * @param GameStatusId $id
     * @param string $status
     */
    public function __construct(GameStatusId $id, string $status)
    {
        $this->id     = $id;
        $this->status = $status;
    }

    /**
     * @return GameStatusId
     */
    public function getId(): GameStatusId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
