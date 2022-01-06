<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Infrastructure\Command;

use App\CroExcelHistory\Domain\Service\StatisticsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Safe\json_encode;
use function Safe\sprintf;

final class StatisticsCommand extends Command
{
    private const DEFAULT_PATH = 'data/transactions.csv';

    private StatisticsService $statisticsService;

    public function __construct(
        StatisticsService $croExcelHistoryFacade
    ) {
        parent::__construct('stats');
        $this->statisticsService = $croExcelHistoryFacade;
    }

    protected function configure(): void
    {
        $this->setDescription('Get interesting data from your transactions history.')
            ->addArgument('path', InputArgument::OPTIONAL, 'The csv file path where the transactions are.', self::DEFAULT_PATH)
            ->addOption('kind', null, InputArgument::OPTIONAL, 'Filter by transaction kind')
            ->addOption('ticker', null, InputArgument::OPTIONAL, 'Filter by ticker');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $path */
        $path = $input->getArgument('path');

        $actual = $this->statisticsService->forFilepath($path);

        /** @var null|string $kind */
        $kind = $input->getOption('kind');

        if ($kind) {
            $output->writeln("$kind: ");
            /** @var null|string $ticker */
            $ticker = $input->getOption('ticker');

            if ($ticker) {
                $output->writeln(sprintf('  %s: %s', $ticker, json_encode($actual[$kind][$ticker])));
            } else {
                foreach ($actual[$kind] as $ticker => $values) {
                    $output->writeln(sprintf('  %s: %s', $ticker, json_encode($values)));
                }
            }
        } else {
            // TODO: Not implemented yet...
            dump($actual);
        }

        return self::SUCCESS;
    }
}
