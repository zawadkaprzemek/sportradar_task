<?php

declare(strict_types=1);

namespace App\Domain;

final class FootballMatch
{
    private int $homeScore = 0; 
    private int $awayScore = 0; 

    public function __construct(
        private readonly int $id,
        private readonly string $homeTeam,
        private readonly string $awayTeam,
    ){
        if (trim($homeTeam) === '' || trim($awayTeam) === '') {
            throw new \InvalidArgumentException('The team name cannot be empty.');
        }

        if (strtolower(trim($homeTeam)) === strtolower(trim($awayTeam))) {
            throw new \InvalidArgumentException(
                'The match must be played by two different teams.'
            );
        }
    }

    public function getHomeScore(): int
    {
        return $this->homeScore;
    }

    public function getAwayScore(): int
    {
        return $this->awayScore;
    }

    public function updateScore(int $homeScore, int $awayScore): void
    {
        if ($homeScore < 0 || $awayScore < 0) {
            throw new \InvalidArgumentException(
                'Team scores cannot be negative.'
            );
        }

        $this->homeScore = $homeScore;
        $this->awayScore = $awayScore;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
    {
        return $this->awayTeam;
    }

    public function getScoreSummary(): int
    {
        return $this->homeScore + $this->awayScore;
    }

    public function getId(): int
    {
        return $this->id;
    }
}