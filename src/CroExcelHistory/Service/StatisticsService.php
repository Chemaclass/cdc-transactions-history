<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Service;

use App\CroExcelHistory\TransactionManager\NullTransactionManager;
use App\CroExcelHistory\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Transfer\Transaction;

final class StatisticsService
{
    private GroupedTransactions $groupedTransactions;

    private TransactionMapper $transactionMapper;

    /** @var array<string,TransactionManagerInterface> */
    private array $transactionManagers;

    /**
     * @param array<string,TransactionManagerInterface> $transactionManagers
     */
    public function __construct(
        GroupedTransactions $groupedTransactions,
        TransactionMapper $transactionMapper,
        array $transactionManagers
    ) {
        $this->groupedTransactions = $groupedTransactions;
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
        $groupedTransactions = $this->generateGroupedTransactions($csv);

        return $this->loopTransactions($groupedTransactions);
    }

    /**
     * @param list<array<string,string>> $csv
     *
     * @return array<string,list<Transaction>>
     */
    private function generateGroupedTransactions(array $csv): array
    {
        $transactions = array_map(
            fn (array $row): Transaction => $this->transactionMapper->map($row),
            $csv
        );

        return $this->groupedTransactions->byKind(...$transactions);
    }

    /**
     * @param array<string,list<Transaction>> $groupedTransactions
     *
     * @return array<string,array<string,mixed>>
     */
    private function loopTransactions(array $groupedTransactions): array
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
