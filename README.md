# Football World Cup Score Board

A lightweight PHP library for managing live football matches.

The library supports starting matches, updating scores, finishing matches, and retrieving a scoreboard sorted by total goals scored. Matches with equal total scores are ordered by the most recently started match.

## Requirements

- PHP 8.2+
- Composer
- Docker Compose (optional)

## Installation

Using Docker:

```bash
docker compose build
docker compose run --rm php composer install
```

Alternatively, install dependencies locally:

```bash
composer install
```

## Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\ScoreBoard;

$scoreBoard = new ScoreBoard();

$matchId = $scoreBoard->startGame('Mexico', 'Canada');

$scoreBoard->updateScore($matchId, 0, 5);

$summary = $scoreBoard->getSummary();

$scoreBoard->finishGame($matchId);
```

## Tests

Run PHPUnit using Docker:

```bash
docker compose run --rm php ./vendor/bin/phpunit tests/
```

Or locally:

```bash
./vendor/bin/phpunit tests/
```

## Assumptions

- Matches are stored in memory.
- Match IDs are unique, incremental, and never reused.
- Scores can be corrected downwards but cannot be negative.
- Team names cannot be empty or identical (case-insensitive comparison).
- Finished matches are removed from the scoreboard.
- Updating or finishing a nonexistent match throws an `InvalidArgumentException`.