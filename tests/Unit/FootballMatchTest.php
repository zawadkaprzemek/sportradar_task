<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\FootballMatch;
use PHPUnit\Framework\TestCase;

final class FootballMatchTest extends TestCase
{
    public function testNewMatchStartsWithZeroScore(): void
    {
        $match = new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: 'Germany',
        );

        self::assertSame(0, $match->getHomeScore());
        self::assertSame(0, $match->getAwayScore());
    }

    public function testCannotStartMatchWithEmptyHomeTeam(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FootballMatch(
            id: 1,
            homeTeam: '',
            awayTeam: 'Germany',
        );
    }

    public function testCannotStartMatchWithEmptyAwayTeam(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: '',
        );   
    }

    public function testCannotStartMatchWithOnlySpacesInTeamName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FootballMatch(
            id: 1,
            homeTeam: '     ',
            awayTeam: 'Germany',
        );
    }

    public function testCannotStartMatchWithSameTeams(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: 'poland',
        );
    }

    public function testCannotStartMatchWithSameTeamsAndExtraSpaces(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: ' poland ',
        );
    }

    public function testCanUpdateScore(): void
    {
        $match = new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: 'Germany',
        );

        $match->updateScore(2, 1);

        self::assertSame(2, $match->getHomeScore());
        self::assertSame(1, $match->getAwayScore());
    }

    public function testCannotUpdateScoreWithNegativeValue(): void
    {
        $match = new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: 'Germany',
        );

        $this->expectException(\InvalidArgumentException::class);

        $match->updateScore(2, -1);
    }

    public function testFailedScoreUpdateDoesNotChangePreviousScore(): void
    {
        $match = new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: 'Germany',
        );

        $match->updateScore(2, 1);

        try{
            $match->updateScore(3, -1);
            self::fail('Expected InvalidArgumentException was not thrown.');
        }catch(\Exception)
        {
            self::assertSame(2, $match->getHomeScore());
            self::assertSame(1, $match->getAwayScore());
        }
    }

    public function testCanDecreaseScore(): void
    {
        $match = new FootballMatch(
            id: 1,
            homeTeam: 'Poland',
            awayTeam: 'Germany',
        );

        $match->updateScore(3, 1);
        $match->updateScore(1, 1);

        self::assertSame(1, $match->getHomeScore());
        self::assertSame(1, $match->getAwayScore());
    }
    
}