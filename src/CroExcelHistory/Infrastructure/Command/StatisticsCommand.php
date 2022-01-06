<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Infrastructure\Command;

use App\CroExcelHistory\Domain\Service\CsvReaderService;
use App\CroExcelHistory\Domain\Service\StatisticsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StatisticsCommand extends Command
{
    private const DEFAULT_PATH = 'data/transactions.csv';

    private CsvReaderService $csvReaderService;

    private StatisticsService $statisticsService;

    public function __construct(
        CsvReaderService  $csvReaderService,
        StatisticsService $croExcelHistoryFacade
    ) {
        parent::__construct('stats');
        $this->csvReaderService = $csvReaderService;
        $this->statisticsService = $croExcelHistoryFacade;
    }

    protected function configure(): void
    {
        $this->setDescription('Get interesting data from your transactions history.')
            ->addArgument('path', InputArgument::OPTIONAL, 'The csv file path where the transactions are.', self::DEFAULT_PATH);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $path */
        $path = $input->getArgument('path');

        $csv = $this->csvReaderService->read($path);

        $actual = $this->statisticsService->forCsv($csv);

        dump($actual);

        return self::SUCCESS;
    }
}
