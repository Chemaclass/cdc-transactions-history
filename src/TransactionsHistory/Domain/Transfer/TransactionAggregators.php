<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Transfer;

use App\TransactionsHistory\Domain\TransactionAggregator\NullAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;

final class TransactionAggregators
{
    /** @var array<string,TransactionAggregatorInterface> */
    private array $aggregators = [];

    public function put(string $transactionType, TransactionAggregatorInterface $aggregator): self
    {
        $this->aggregators[$transactionType] = $aggregator;

        return $this;
    }

    public function getAggregatorByType(string $transactionType): TransactionAggregatorInterface
    {
        return $this->aggregators[$transactionType] ?? new NullAggregator();
    }
}
