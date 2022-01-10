<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Domain\Service\AggregateService;
use Safe\Exceptions\ArrayException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TransactionTypesCommand extends Command
{
    private const DEFAULT_PATH = 'data/transactions.csv';

    private AggregateService $statisticsService;

    public function __construct(AggregateService $statisticsService)
    {
        parent::__construct('types');
        $this->statisticsService = $statisticsService;
    }

    protected function configure(): void
    {
        $this->setDescription('Display all transaction types.')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'The csv file path where the transactions are.',
                self::DEFAULT_PATH
            );
    }

    /**
     * @throws ArrayException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $path */
        $path = $input->getArgument('path');

        /** @var array<string,array<string,mixed>> */
        $transactionsGroupedByType = $this->statisticsService->forFilepath($path);

        foreach (array_keys($transactionsGroupedByType) as $transactionType) {
            $output->writeln("<comment>$transactionType</comment>");
        }

        return self::SUCCESS;
    }
}
