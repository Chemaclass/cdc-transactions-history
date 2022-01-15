<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Domain\Service\AggregateService;
use App\TransactionsHistory\Domain\Service\TransactionsFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AggregateTransactionsCommand extends Command
{
    private const DEFAULT_PATH = 'data/transactions.csv';

    private AggregateService $aggregateService;

    private TransactionsFilter $transactionsFilter;

    public function __construct(
        AggregateService $statisticsService,
        TransactionsFilter $transactionsFilter
    ) {
        parent::__construct('aggregate');
        $this->aggregateService = $statisticsService;
        $this->transactionsFilter = $transactionsFilter;
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $transactionsGroupedByType = $this->transactionsGroupedByTypeForInput($input);

        $this->renderTransactionsTypes($output, $transactionsGroupedByType);
        $this->renderAllAggregatesTogether($output, $transactionsGroupedByType);

        return self::SUCCESS;
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    private function transactionsGroupedByTypeForInput(InputInterface $input): array
    {
        /** @var string $path */
        $path = $input->getArgument('path');

        /** @var array<string,array<string,mixed>> */
        $transactionsGroupedByType = $this->aggregateService->forFilepath($path);

        /** @var null|string $inputType */
        $inputType = $input->getOption('type');

        /** @var null|string $inputCurrency */
        $inputCurrency = $input->getOption('currency');

        return $this->transactionsFilter->filterForGroupedByType(
            $transactionsGroupedByType,
            $inputType,
            $inputCurrency
        );
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByType
     */
    private function renderTransactionsTypes(OutputInterface $output, array $transactionsGroupedByType): void
    {
        foreach ($transactionsGroupedByType as $type => $transactions) {
            $output->writeln("<comment>$type:</comment>");
            $table = (new Table($output))
                ->setHeaders(array_keys(reset($transactions)))// @phpstan-ignore-line
                ->setRows($transactions);
            $table->render();
        }
    }

    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByType
     */
    private function renderAllAggregatesTogether(OutputInterface $output, array $transactionsGroupedByType): void
    {
        $groupedByCurrency = [];

        foreach ($transactionsGroupedByType as $type => $values) {
            foreach ($values as $value) {
                $groupedByCurrency[$value['currency']][] = $value;// @phpstan-ignore-line
            }
        }

        $result = [];

        foreach ($groupedByCurrency as $currency => $values) {
            $result[$currency] = [
                'currency' => $currency,
                'total' => $this->sumReduce(array_column($values, 'total')),
                'EUR' => $this->sumReduce(array_column($values, 'EUR')),
                'USD' => $this->sumReduce(array_column($values, 'USD')),
            ];
        }

        $output->writeln('<comment>================================================</comment>');
        $output->writeln('<comment>============== ALL TOGETHER ====================</comment>');
        $output->writeln('<comment>================================================</comment>');
        $table = (new Table($output))
            ->setHeaders(array_keys(reset($result)))// @phpstan-ignore-line
            ->setRows($result);
        $table->render();
    }

    /**
     * @param list<string> $list
     */
    private function sumReduce(array $list): float
    {
        return (float) array_reduce(
            $list,
            static fn($acc, $i) => bcadd($acc ?? '0', $i, 2)
        );
    }
}
