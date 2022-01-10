<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Domain\Service\AggregateService;
use Safe\Exceptions\ArrayException;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Safe\json_encode;
use function Safe\sprintf;

final class AggregateTransactionsCommand extends Command
{
    private const DEFAULT_PATH = 'data/transactions.csv';

    private AggregateService $statisticsService;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private InputInterface $input;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private OutputInterface $output;

    /** @var list<list<string>> */
    private array $linesBuffer = [];

    public function __construct(AggregateService $statisticsService)
    {
        parent::__construct('aggregate');
        $this->statisticsService = $statisticsService;
    }

    protected function configure(): void
    {
        $this->setDescription('Aggregate transactions grouped by type.')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'The csv file path where the transactions are.',
                self::DEFAULT_PATH
            )
            ->addOption('type', 't', InputArgument::OPTIONAL, 'Filter by transaction type')
            ->addOption('currency', 'c', InputArgument::OPTIONAL, 'Filter by currency');
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
        $transactionsGroupedByType = $this->statisticsService->forFilepath($path);

        /** @var null|string $inputType */
        $inputType = $input->getOption('type');
        $types = ($inputType)
            ? explode(',', $inputType)
            : array_keys($transactionsGroupedByType);

        foreach ($types as $type) {
            $this->addTransactionTypeToBuffer($transactionsGroupedByType, $type);
        }

        $this->renderBufferOutput();

        return self::SUCCESS;
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByType
     *
     * @throws JsonException|StringsException
     */
    private function addTransactionTypeToBuffer(array $transactionsGroupedByType, string $transactionType): void
    {
        /** @var null|string $inputCurrency */
        $inputCurrency = $this->input->getOption('currency');

        $currencies = ($inputCurrency)
            ? explode(',', $inputCurrency)
            : array_keys($transactionsGroupedByType[$transactionType] ?? []);

        if (empty($currencies)) {
            return;
        }

        $maxCurrencyLength = $this->calculateMaxCurrencyLength($currencies);

        $lines = [];

        foreach ($currencies as $currency) {
            $values = $transactionsGroupedByType[$transactionType][$currency] ?? [];

            if (empty($values)) {
                continue;
            }
            $lines[] = sprintf(
                '  %s: %s',
                str_pad($currency, $maxCurrencyLength),
                json_encode($values)
            );
        }

        if ($lines) {
            array_unshift($lines, "<comment>$transactionType:</comment>");
            $this->linesBuffer[] = $lines;
        }
    }

    /**
     * @param list<string> $currencies
     */
    private function calculateMaxCurrencyLength(array $currencies): int
    {
        if (empty($currencies)) {
            return 0;
        }

        /** @var non-empty-list<int> $currenciesLengths */
        $currenciesLengths = array_map(
            static fn(string $k): int => mb_strlen($k),
            $currencies
        );

        return max($currenciesLengths);
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
                '  --type:%s, --currency=%s',
                $this->input->getOption('type') ?: 'empty',
                $this->input->getOption('currency') ?: 'empty'
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
