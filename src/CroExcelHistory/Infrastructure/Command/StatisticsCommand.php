<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Infrastructure\Command;

use App\CroExcelHistory\Domain\Service\StatisticsService;
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

    private int $maxTickerLength = 3;

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

    /**
     * @throws JsonException|StringsException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $path */
        $path = $input->getArgument('path');

        /** @var array<string,array<string,mixed>> */
        $transactionsGroupedByKind = $this->statisticsService->forFilepath($path);

        /** @var null|string $transactionKind */
        $transactionKind = $input->getOption('kind');

        $this->calculateMaxTickerLength($transactionsGroupedByKind);

        if (!$transactionKind) {
            $this->renderAllTransactionKind($input, $output, $transactionsGroupedByKind);
        } else {
            $this->renderTransactionKind($input, $output, $transactionsGroupedByKind, $transactionKind);
        }

        return self::SUCCESS;
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByKind
     *
     * @throws JsonException|StringsException
     */
    private function renderAllTransactionKind(
        InputInterface  $input,
        OutputInterface $output,
        array           $transactionsGroupedByKind
    ): void {
        foreach (array_keys($transactionsGroupedByKind) as $transactionKind) {
            $this->renderTransactionKind($input, $output, $transactionsGroupedByKind, $transactionKind);
        }
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByKind
     *
     * @throws JsonException|StringsException
     */
    private function renderTransactionKind(
        InputInterface  $input,
        OutputInterface $output,
        array           $transactionsGroupedByKind,
        string          $transactionKind
    ): void {
        $output->writeln("$transactionKind: ");
        /** @var null|string $ticker */
        $ticker = $input->getOption('ticker');

        $tickers = ($ticker)
            ? explode(',', $ticker)
            : array_keys($transactionsGroupedByKind[$transactionKind]);

        foreach ($tickers as $ticker) {
            $output->writeln(sprintf(
                '  %s: %s',
                str_pad($ticker, $this->maxTickerLength),
                json_encode($transactionsGroupedByKind[$transactionKind][$ticker])
            ));
        }
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByKind
     */
    private function calculateMaxTickerLength(array $transactionsGroupedByKind): void
    {
        $firstNonEmptyGroup = array_filter($transactionsGroupedByKind);

        /** @var non-empty-list<int> $tickerLengths */
        $tickerLengths = array_map(
            static fn(string $k): int => mb_strlen($k),// @phpstan-ignore-line
            array_keys(reset($firstNonEmptyGroup)) // @phpstan-ignore-line
        );

        $this->maxTickerLength = max($tickerLengths);
    }
}
