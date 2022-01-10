<?php

declare(strict_types=1);

namespace Tests\Feature\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use App\TransactionsHistory\TransactionsHistoryFacade;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

final class AggregateTransactionsCommandTest extends TestCase
{
    private AggregateTransactionsCommand $command;

    public function setUp(): void
    {
        $this->command = (new TransactionsHistoryFacade())->getAggregateTransactionsCommand();
    }

    public function test_stats_existing_transaction_type(): void
    {
        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats.csv --type=viban_purchase'),
            $this->createMock(OutputInterface::class)
        );

        self::assertSame(Command::SUCCESS, $actual);
    }

    public function test_stats_tickers_not_found_when_non_existing_type(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output->method('writeln')->withConsecutive(
            ['<info>No transactions found with that criteria</info>'],
        );

        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats.csv --type=non-existing-kind'),
            $output
        );

        self::assertSame(Command::SUCCESS, $actual);
    }
}
