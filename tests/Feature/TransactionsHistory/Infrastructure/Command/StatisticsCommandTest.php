<?php

declare(strict_types=1);

namespace Tests\Feature\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\TransactionsHistoryFacade;
use App\TransactionsHistory\Infrastructure\Command\StatisticsCommand;
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

    public function test_stats_one_kind(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output->method('writeln')->withConsecutive(
            ['viban_purchase: '],
            ['  BCH: {"totalInEuros":203.3}'],
            ['  ADA: {"totalInEuros":202.2}'],
        );

        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats_one_kind.csv --kind=viban_purchase'),
            $output
        );

        self::assertSame(Command::SUCCESS, $actual);
    }

    public function test_stats_multiple_kinds(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output->method('writeln')->withConsecutive(
            ['crypto_withdrawal: '],
            ['  BCH: {"total":-0.5,"totalInEuros":200}'],
            ['viban_purchase: '],
            ['  BCH: {"totalInEuros":100.1}'],
        );

        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-stats_multiple_kinds.csv --kind=crypto_withdrawal,viban_purchase'),
            $output
        );

        self::assertSame(Command::SUCCESS, $actual);
    }
}
