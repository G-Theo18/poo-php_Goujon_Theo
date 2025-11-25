<?php

/*
 * This file is part of the OpenClassRoom PHP Object Course.
 *
 * (c) Grégoire Hébert <contact@gheb.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

abstract class AbstractPlayer
{
    public function __construct(
        protected string $name,
        protected float $ratio = 400.0
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getRatio(): float
    {
        return $this->ratio;
    }

    abstract public function updateRatioAgainst(self $player, int $result): void;
}

final class Player extends AbstractPlayer
{
    private function probabilityAgainst(AbstractPlayer $player): float
    {
        return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
    }

    public function updateRatioAgainst(AbstractPlayer $player, int $result): void
    {
        $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
    }
}

final class QueuingPlayer extends AbstractPlayer
{
    protected int $range = 1;

    public function __construct(AbstractPlayer $player)
    {
        parent::__construct($player->getName(), $player->getRatio());
    }

    public function getRange(): int
    {
        return $this->range;
    }

    public function setRange(int $range): void
    {
        $this->range = max(1, $range);
    }

    public function updateRatioAgainst(AbstractPlayer $player, int $result): void
    {

    }
}

final class Lobby
{
    /** @var array<QueuingPlayer> */
    public array $queuingPlayers = [];

    public function findOponents(QueuingPlayer $player): array
    {
        $minLevel = round($player->getRatio() / 100);
        $maxLevel = $minLevel + $player->getRange();

        return array_filter(
            $this->queuingPlayers,
            static function (QueuingPlayer $potentialOponent) use ($minLevel, $maxLevel, $player) {
                $playerLevel = round($potentialOponent->getRatio() / 100);

                return $player !== $potentialOponent && ($minLevel <= $playerLevel) && ($playerLevel <= $maxLevel);
            }
        );
    }

    public function addPlayer(AbstractPlayer $player): void
    {
        $this->queuingPlayers[] = new QueuingPlayer($player);
    }

    public function addPlayers(AbstractPlayer ...$players): void
    {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }
    }
}

$greg = new Player('greg', 400);
$jade = new Player('jade', 476);

$lobby = new Lobby();
$lobby->addPlayers($greg, $jade);

var_dump($lobby->findOponents($lobby->queuingPlayers[0]));

exit(0);
