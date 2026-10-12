<?php

declare(strict_types=1);

namespace App;

use App\Domain\FootballMatch;

final class ScoreBoard
{
    /** @var array<int, FootballMatch> */
    private array $matches = [];

    private int $nextMatchId = 1;

    public function startGame(string $homeTeam, string $awayTeam): int
    {
        $matchId = $this->nextMatchId;

        $this->matches[$matchId] = new FootballMatch(
            id: $matchId,
            homeTeam: $homeTeam,
            awayTeam: $awayTeam,
        );

        $this->nextMatchId++;

        return $matchId;
    }

    public function finishGame(int $matchId): void
    {
        if (!isset($this->matches[$matchId])) {
            throw new \InvalidArgumentException(
                'Match with the given ID does not exist.'
            );
        }

        unset($this->matches[$matchId]);
    }

    public function updateScore(int $matchId, int $homeScore, int $awayScore): void
    {
        if (!isset($this->matches[$matchId])) {
            throw new \InvalidArgumentException('Match with the given ID does not exist.');
        }

        $this->matches[$matchId]->updateScore($homeScore, $awayScore);
    }

    /**
     * @return list<array{
     *     homeTeam: string,
     *     awayTeam: string,
     *     homeScore: int,
     *     awayScore: int
     * }>
     */
    public function getSummary(): array
    {
        $summary = [];
        $matches = $this->matches;

        usort($matches, static function (FootballMatch $match1, FootballMatch $match2): int {
            return ($match2->getScoreSummary() <=> $match1->getScoreSummary())
                ?: ($match2->getId() <=> $match1->getId());
        });

        foreach ($matches as $match) {
            $summary[] = [
                'homeTeam' => $match->getHomeTeam(),
                'awayTeam' => $match->getAwayTeam(),
                'homeScore' => $match->getHomeScore(),
                'awayScore' => $match->getAwayScore(),
            ];
        }

        return $summary;
    }
}