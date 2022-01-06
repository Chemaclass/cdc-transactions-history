<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Domain\Service;

use App\CroExcelHistory\Domain\Mapper\TransactionMapperInterface;
use App\CroExcelHistory\Domain\TransactionManager\NullTransactionManager;
use App\CroExcelHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Domain\Transfer\Transaction;

final class StatisticsService
{
    private TransactionMapperInterface $transactionMapper;

    /** @var array<string,TransactionManagerInterface> */
    private array $transactionManagers;

    /**
     * @param array<string,TransactionManagerInterface> $transactionManagers
     */
    public function __construct(
        TransactionMapperInterface $transactionMapper,
        array $transactionManagers
    ) {
        $this->transactionMapper = $transactionMapper;
        $this->transactionManagers = $transactionManagers;
    }

    /**
     * @param list<array<string,string>> $csv
     *
     * @return array<string,array<string,mixed>>
     */
    public function forCsv(array $csv): array
    {
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
