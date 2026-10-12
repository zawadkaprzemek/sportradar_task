<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\ScoreBoard;
use PHPUnit\Framework\TestCase;

final class ScoreBoardTest extends TestCase
{
	public function testCanStartGame(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $matchId = $scoreBoard->startGame('Poland', 'Germany');

	    self::assertSame(1, $matchId);
	}

	public function testStartingMultipleGamesGeneratesUniqueIds(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $firstId = $scoreBoard->startGame('Poland', 'Germany');
	    $secondId = $scoreBoard->startGame('Brazil', 'Argentina');

	    self::assertSame(1, $firstId);
	    self::assertSame(2, $secondId);
	}

	public function testCanFinishGame(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $matchId = $scoreBoard->startGame('Poland', 'Germany');

	    $scoreBoard->finishGame($matchId);

	    $this->expectException(\InvalidArgumentException::class);

	    $scoreBoard->finishGame($matchId);
	}

	public function testCannotFinishNonExistingGame(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $this->expectException(\InvalidArgumentException::class);

	    $scoreBoard->finishGame(999);
	}

	public function testCannotUpdateNonExistingGame(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $this->expectException(\InvalidArgumentException::class);

	    $scoreBoard->updateScore(999, 2, 1);
	}

	public function testSummaryContainsUpdatedMatchScore(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $matchId = $scoreBoard->startGame('Poland', 'Germany');
	    $scoreBoard->updateScore($matchId, 2, 1);

	    self::assertSame(
	        [
	            [
	                'homeTeam' => 'Poland',
	                'awayTeam' => 'Germany',
	                'homeScore' => 2,
	                'awayScore' => 1,
	            ],
	        ],
	        $scoreBoard->getSummary()
	    );
	}


	public function testSummaryIsSortedByTotalScoreDescending(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $firstId = $scoreBoard->startGame('Poland', 'Germany');
	    $secondId = $scoreBoard->startGame('Brazil', 'Argentina');
	    $thirdId = $scoreBoard->startGame('Spain', 'France');

	    $scoreBoard->updateScore($firstId, 1, 0);
	    $scoreBoard->updateScore($secondId, 2, 2);
	    $scoreBoard->updateScore($thirdId, 3, 2);

	    $summary = $scoreBoard->getSummary();

	    self::assertSame('Spain', $summary[0]['homeTeam']);
	    self::assertSame('Brazil', $summary[1]['homeTeam']);
	    self::assertSame('Poland', $summary[2]['homeTeam']);
	}

	public function testSummaryPrioritizesMostRecentlyAddedMatchOnEqualScore(): void
	{
	    $scoreBoard = new ScoreBoard();

	    $scoreBoard->startGame('Poland', 'Germany');
	    $scoreBoard->startGame('Brazil', 'Argentina');
	    $scoreBoard->startGame('Spain', 'France');

	    $summary = $scoreBoard->getSummary();

	    self::assertSame('Spain', $summary[0]['homeTeam']);
	    self::assertSame('Brazil', $summary[1]['homeTeam']);
	    self::assertSame('Poland', $summary[2]['homeTeam']);
	}

	public function testSummaryMatchesWorldCupExample(): void
	{
		$scoreBoard = new ScoreBoard();

	    $match1Id = $scoreBoard->startGame('Mexico', 'Canada');
	    $match2Id = $scoreBoard->startGame('Spain', 'Brazil');
	    $match3Id = $scoreBoard->startGame('Germany', 'France');
	    $match4Id = $scoreBoard->startGame('Uruguay', 'Italy');
	    $match5Id = $scoreBoard->startGame('Argentina', 'Australia');

	    $scoreBoard->updateScore($match1Id, 0, 5);
	    $scoreBoard->updateScore($match2Id, 10, 2);
	    $scoreBoard->updateScore($match3Id, 2, 2);
	    $scoreBoard->updateScore($match4Id, 6, 6);
	    $scoreBoard->updateScore($match5Id, 3, 1);

	    $summary = $scoreBoard->getSummary();

	    self::assertSame(
		    [
		        [
		            'homeTeam' => 'Uruguay',
		            'awayTeam' => 'Italy',
		            'homeScore' => 6,
		            'awayScore' => 6,
		        ],
		        [
		            'homeTeam' => 'Spain',
		            'awayTeam' => 'Brazil',
		            'homeScore' => 10,
		            'awayScore' => 2,
		        ],
		        [
		            'homeTeam' => 'Mexico',
		            'awayTeam' => 'Canada',
		            'homeScore' => 0,
		            'awayScore' => 5,
		        ],
		        [
		            'homeTeam' => 'Argentina',
		            'awayTeam' => 'Australia',
		            'homeScore' => 3,
		            'awayScore' => 1,
		        ],
		        [
		            'homeTeam' => 'Germany',
		            'awayTeam' => 'France',
		            'homeScore' => 2,
		            'awayScore' => 2,
		        ],
		    ],
		    $summary
		);
	}

	public function testSummaryIsEmptyWhenNoGamesStarted(): void
	{
		$scoreBoard = new ScoreBoard();

		$summary = $scoreBoard->getSummary();

		self::assertSame([], $summary);
	}


	public function testFinishedGameIsNotIncludedInSummary(): void
	{
		$scoreBoard = new ScoreBoard();

		$match1Id = $scoreBoard->startGame('Poland', 'Germany');
	    $scoreBoard->startGame('Brazil', 'Argentina');

	    $scoreBoard->finishGame($match1Id);

	    $summary = $scoreBoard->getSummary();

	    self::assertCount(1, $summary);
	    self::assertSame('Brazil', $summary[0]['homeTeam']);
	}

	public function testCannotUpdateFinishedGame(): void
	{
		$scoreBoard = new ScoreBoard();

		$match1Id = $scoreBoard->startGame('Poland', 'Germany');

	    $scoreBoard->finishGame($match1Id);

	    $this->expectException(\InvalidArgumentException::class);

	    $scoreBoard->updateScore($match1Id, 2, 0);
	}

	public function testMatchIdsAreNotReused(): void
	{
		$scoreBoard = new ScoreBoard();

		$matchId = $scoreBoard->startGame('Poland', 'Germany');

	    $scoreBoard->finishGame($matchId);

	    $match2Id = $scoreBoard->startGame('France', 'Brazil');

	    self::assertSame(2, $match2Id);
	}
}