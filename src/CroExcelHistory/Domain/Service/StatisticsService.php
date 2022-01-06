<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Domain\Service;

use App\CroExcelHistory\Domain\Mapper\TransactionMapperInterface;
use App\CroExcelHistory\Domain\TransactionManager\NullTransactionManager;
use App\CroExcelHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Domain\Transfer\Transaction;
use App\CroExcelHistory\Infrastructure\IO\FileReaderServiceInterface;

final class StatisticsService
{
    private FileReaderServiceInterface $fileReaderService;

    private TransactionMapperInterface $transactionMapper;

    /** @var array<string,TransactionManagerInterface> */
    private array $transactionManagers;

    /**
     * @param array<string,TransactionManagerInterface> $transactionManagers
     */
    public function __construct(
        FileReaderServiceInterface $fileReaderService,
        TransactionMapperInterface $transactionMapper,
        array $transactionManagers
    ) {
        $this->fileReaderService = $fileReaderService;
        $this->transactionMapper = $transactionMapper;
        $this->transactionManagers = $transactionManagers;
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public function forFilepath(string $filepath): array
    {
        $csv = $this->fileReaderService->read($filepath);

        $groupedTransactions = $this->generateTransactionsGroupedByKind($csv);

        return $this->manageTransactions($groupedTransactions);
    }

    /**
     * @param list<array<string,string>> $csv
     *
     * @return array<string,list<Transaction>>
     */
    private function generateTransactionsGroupedByKind(array $csv): array
    {
        $transactions = array_map(
            fn(array $row): Transaction => $this->transactionMapper->map($row),
            $csv
        );

        $result = [];

        foreach ($transactions as $transaction) {
            $result[$transaction->getTransactionKind()] ??= [];
            $result[$transaction->getTransactionKind()][] = $transaction;
        }

        return $result;
    }

    /**
     * @param array<string,list<Transaction>> $groupedTransactions
     *
     * @return array<string,array<string,mixed>>
     */
    private function manageTransactions(array $groupedTransactions): array
    {
        $result = [];

        foreach ($groupedTransactions as $kind => $transactions) {
            $manager = $this->getManagerByKind($kind);
            $result[$kind] = $manager->manageTransactions(...$transactions);
        }

        return $result;
    }

    private function getManagerByKind(string $kind): TransactionManagerInterface
    {
        return $this->transactionManagers[$kind] ?? new NullTransactionManager();
    }
}
