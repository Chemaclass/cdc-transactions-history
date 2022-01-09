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

    /** @var list<list<string>> */
    private array $linesBuffer = [];

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
            ->addOption('kind', 'k', InputArgument::OPTIONAL, 'Filter by transaction kind')
            ->addOption('ticker', 't', InputArgument::OPTIONAL, 'Filter by ticker');
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

        /** @var null|string $kind */
        $kind = $input->getOption('kind');
        $kinds = ($kind)
            ? explode(',', $kind)
            : array_keys($transactionsGroupedByKind);

        foreach ($kinds as $transactionKind) {
            $this->addTransactionKindToBuffer($transactionsGroupedByKind, $transactionKind);
        }

        $this->renderBufferOutput();

        return self::SUCCESS;
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByKind
     *
     * @throws JsonException|StringsException
     */
    private function addTransactionKindToBuffer(array $transactionsGroupedByKind, string $transactionKind): void
    {
        /** @var null|string $ticker */
        $ticker = $this->input->getOption('ticker');

        $tickers = ($ticker)
            ? explode(',', $ticker)
            : array_keys($transactionsGroupedByKind[$transactionKind] ?? []);

        if (empty($tickers)) {
            return;
        }

        $maxTickerLength = $this->calculateMaxTickerLength($tickers);

        $lines = [];

        foreach ($tickers as $ticker) {
            $values = $transactionsGroupedByKind[$transactionKind][$ticker] ?? [];

            if (empty($values)) {
                continue;
            }
            $lines[] = sprintf(
                '  %s: %s',
                str_pad($ticker, $maxTickerLength),
                json_encode($values)
            );
        }

        if ($lines) {
            array_unshift($lines, "<comment>$transactionKind:</comment>");
            $this->linesBuffer[] = $lines;
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

    private function renderBufferOutput(): void
    {
        if (empty($this->linesBuffer)) {
            $this->renderNoTransactionsFound();
        } else {
            $this->renderLinesBuffer();
        }
    }

    private function renderNoTransactionsFound(): void
    {
        $this->output->writeln('<info>No transactions found with that criteria</info>');
        $this->output->writeln(
            sprintf(
                '  --kind:%s, --ticker=%s',
                $this->input->getOption('kind') ?: 'empty',
                $this->input->getOption('ticker') ?: 'empty'
            )
        );
    }

    private function renderLinesBuffer(): void
    {
        foreach ($this->linesBuffer as $line) {
            $this->output->writeln($line);
        }
    }
}
