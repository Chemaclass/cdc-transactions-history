<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Command;

use App\TransactionsHistory\Domain\Service\AggregateService;
use Safe\Exceptions\ArrayException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TransactionKindsCommand extends Command
{
    private const DEFAULT_PATH = 'data/transactions.csv';

    private AggregateService $statisticsService;

    public function __construct(AggregateService $statisticsService)
    {
        parent::__construct('kinds');
        $this->statisticsService = $statisticsService;
    }

    protected function configure(): void
    {
        $this->setDescription('Display all transaction kinds.')
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
        $transactionsGroupedByKind = $this->statisticsService->forFilepath($path);

        foreach (array_keys($transactionsGroupedByKind) as $transactionKind) {
            $output->writeln("<comment>$transactionKind</comment>");
        }

        return self::SUCCESS;
    }
}
