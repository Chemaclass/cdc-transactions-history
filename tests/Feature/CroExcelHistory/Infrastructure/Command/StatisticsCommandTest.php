<?php

declare(strict_types=1);

namespace Tests\Feature\CroExcelHistory\Infrastructure\Command;

use App\CroExcelHistory\CroExcelHistoryFacade;
use App\CroExcelHistory\Infrastructure\Command\StatisticsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

final class StatisticsCommandTest extends TestCase
{
    private StatisticsCommand $command;

    public function setUp(): void
    {
        $this->command = (new CroExcelHistoryFacade())->getStatisticsCommand();
    }

    public function test_statistics_viban_purchase(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output->method('writeln')->withConsecutive(
            ['viban_purchase: '],
            ['  BCH: {"totalInEuros":203.3}'],
            ['  ADA: {"totalInEuros":202.2}'],
        );

        $actual = $this->command->run(
            new StringInput(__DIR__ . '/Fixtures/mock-transactions-viban-purchase.csv --kind=viban_purchase'),
            $output
        );

        self::assertSame(Command::SUCCESS, $actual);
    }
}
