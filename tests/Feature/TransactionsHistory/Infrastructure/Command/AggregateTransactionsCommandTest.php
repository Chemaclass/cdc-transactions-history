<?php

declare(strict_types=1);

namespace Tests\Feature\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use App\TransactionsHistory\TransactionsHistoryFacade;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class AggregateTransactionsCommandTest extends TestCase
{
    private AggregateTransactionsCommand $command;

    public function setUp(): void
    {
        $this->command = (new TransactionsHistoryFacade())->getAggregateTransactionsCommand();
    }

    public function test_aggregate_existing_transaction_type(): void
    {
        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats.csv --type=viban_purchase'),
            new BufferedOutput()
        );

        self::assertSame(Command::SUCCESS, $actual);
    }

    public function test_aggregate_all(): void
    {
        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats.csv'),
            new BufferedOutput()
        );

        self::assertSame(Command::SUCCESS, $actual);
    }
}
