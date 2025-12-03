<?php

declare(strict_types=1);

namespace App\MatchMaker\Queue;

use App\MatchMaker\Player\PlayerInterface;

class QueuingPlayer implements QueuingPlayerInterface
{
    private PlayerInterface $player;
    private int $range = 1;
    private \DateTimeImmutable $queuedAt;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
        $this->queuedAt = new \DateTimeImmutable();
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function getName(): string
    {
        return $this->player->getName();
    }

    public function getRatio(): float
    {
        return $this->player->getRatio();
    }

    public function updateRatioAgainst(PlayerInterface $player, int $result): void
    {
        $this->player->updateRatioAgainst($player, $result);
    }

    public function getQueuedAt(): \DateTimeImmutable
    {
        return $this->queuedAt;
    }

    public function getRange(): int
    {
        return $this->range;
    }

    public function upgradeRange(): void
    {
        $this->range = min($this->range + 1, 40);
    }
}
