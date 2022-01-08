<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Transfer;

use App\TransactionsHistory\Domain\TransactionAggregator\NullAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;

final class TransactionAggregators
{
    /** @var array<string,TransactionAggregatorInterface> */
    private array $aggregators = [];

    public function put(string $transactionKind, TransactionAggregatorInterface $transactionManager): self
    {
        $this->aggregators[$transactionKind] = $transactionManager;

        return $this;
    }

    public function get(string $transactionKind): TransactionAggregatorInterface
    {
        return $this->aggregators[$transactionKind] ?? new NullAggregator();
    }
}
