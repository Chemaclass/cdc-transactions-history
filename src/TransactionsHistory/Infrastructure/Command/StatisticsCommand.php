<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Domain\Service\StatisticsService;
use Safe\Exceptions\ArrayException;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
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

    /** @psalm-suppress PropertyNotSetInConstructor */
    private InputInterface $input;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private OutputInterface $output;

    public function __construct(StatisticsService $statisticsService)
    {
        parent::__construct('stats');
        $this->statisticsService = $statisticsService;
    }

    protected function configure(): void
    {
        $this->setDescription('Get interesting data from your transactions history group by transactions kind.')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'The csv file path where the transactions are.',
                self::DEFAULT_PATH
            )
            ->addOption('kind', null, InputArgument::OPTIONAL, 'Filter by transaction kind')
            ->addOption('ticker', null, InputArgument::OPTIONAL, 'Filter by ticker');
    }

    /**
     * @throws ArrayException|JsonException|StringsException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        /** @var string $path */
        $path = $input->getArgument('path');

        /** @var array<string,array<string,mixed>> */
        $transactionsGroupedByKind = $this->statisticsService->forFilepath($path);

        /** @var null|string $transactionKind */
        $transactionKind = $input->getOption('kind');

        if (!$transactionKind) {
            $this->renderAllTransactionKind($transactionsGroupedByKind);
        } else {
            foreach (explode(',', $transactionKind) as $kind) {
                $this->renderTransactionKind($transactionsGroupedByKind, $kind);
            }
        }

        return self::SUCCESS;
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByKind
     *
     * @throws JsonException|StringsException
     */
    private function renderAllTransactionKind(array $transactionsGroupedByKind): void
    {
        foreach (array_keys($transactionsGroupedByKind) as $transactionKind) {
            $this->renderTransactionKind($transactionsGroupedByKind, $transactionKind);
        }
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByKind
     *
     * @throws JsonException|StringsException
     */
    private function renderTransactionKind(array $transactionsGroupedByKind, string $transactionKind): void
    {
        $this->output->writeln("$transactionKind:");
        /** @var null|string $ticker */
        $ticker = $this->input->getOption('ticker');

        $tickers = ($ticker)
            ? explode(',', $ticker)
            : array_keys($transactionsGroupedByKind[$transactionKind]);

        $maxTickerLength = $this->calculateMaxTickerLength($tickers);

        foreach ($tickers as $ticker) {
            $line = sprintf(
                '  %s: %s',
                str_pad($ticker, $maxTickerLength),
                json_encode($transactionsGroupedByKind[$transactionKind][$ticker] ?? 'null')
            );

            $this->output->writeln($line);
        }
    }

    /**
     * @param list<string> $tickers
     */
    private function calculateMaxTickerLength(array $tickers): int
    {
        if (empty($tickers)) {
            return 0;
        }

        /** @var non-empty-list<int> $tickerLengths */
        $tickerLengths = array_map(
            static fn(string $k): int => mb_strlen($k),
            $tickers
        );

        return max($tickerLengths);
    }
}
