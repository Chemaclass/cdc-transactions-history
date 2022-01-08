<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Service;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use App\TransactionsHistory\Domain\Transfer\TransactionManagers;
use Safe\Exceptions\ArrayException;

use function Safe\ksort;

final class StatisticsService
{
    private FileReaderServiceInterface $fileReaderService;

    private TransactionMapperInterface $transactionMapper;

    private TransactionManagers $transactionManagers;

    public function __construct(
        FileReaderServiceInterface $fileReaderService,
        TransactionMapperInterface $transactionMapper,
        TransactionManagers $transactionManagers
    ) {
        $this->fileReaderService = $fileReaderService;
        $this->transactionMapper = $transactionMapper;
        $this->transactionManagers = $transactionManagers;
    }

    /**
     * @throws ArrayException
     *
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
     * @throws ArrayException
     *
     * @return array<string,array<string,mixed>>
     */
    private function manageTransactions(array $groupedTransactions): array
    {
        $result = [];

        foreach ($groupedTransactions as $kind => $transactions) {
            $manager = $this->transactionManagers->get($kind);
            $result[$kind] = $manager->manageTransactions(...$transactions);
        }

        ksort($result);

        return $result;
    }
}
