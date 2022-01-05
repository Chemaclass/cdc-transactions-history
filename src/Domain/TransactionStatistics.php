<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\TransactionManager\NullTransactionManager;
use App\Domain\TransactionManager\TransactionManagerInterface;
use JetBrains\PhpStorm\Pure;

final class TransactionStatistics
{
    /** @var array<string,TransactionManagerInterface> */
    private array $transactionManagers;

    /**
     * @param array<string,TransactionManagerInterface> $transactionManagers
     */
    public function __construct(array $transactionManagers)
    {
        $this->transactionManagers = $transactionManagers;
    }

    /**
     * @param array<string,list<Transaction>> $groupedTransactions [kind => [Transaction,...]]
     *
     * @return array<string,array> [kind => [mixed]]
     */
    public function forGroupedByKind(array $groupedTransactions): array
    {
        $result = [];
        foreach ($groupedTransactions as $kind => $transactions) {
            $manager = $this->getManagerByKind($kind);
            $result[$kind] = $manager->manageTransactions(...$transactions);
        }

        return $result;
    }

    #[Pure]
    private function getManagerByKind(string $kind): TransactionManagerInterface
    {
        return $this->transactionManagers[$kind] ?? new NullTransactionManager();
    }
}