<?php

declare(strict_types=1);

namespace Tests\Feature\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Infrastructure\Command\StatisticsCommand;
use App\TransactionsHistory\TransactionsHistoryFacade;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

final class StatisticsCommandTest extends TestCase
{
    private StatisticsCommand $command;

    public function setUp(): void
    {
        $this->command = (new TransactionsHistoryFacade())->getStatisticsCommand();
    }

    public function test_stats_existing_transaction_kind(): void
    {
        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats.csv --kind=viban_purchase'),
            $this->createMock(OutputInterface::class)
        );

        self::assertSame(Command::SUCCESS, $actual);
    }

    public function test_stats_tickers_not_found_when_non_existing_kind(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output->method('writeln')->withConsecutive(
            ['<info>No transactions found with that criteria</info>'],
        );

        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats.csv --kind=non-existing-kind'),
            $output
        );

        self::assertSame(Command::SUCCESS, $actual);
    }
}
