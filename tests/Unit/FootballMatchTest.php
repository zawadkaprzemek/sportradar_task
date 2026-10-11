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
    
}