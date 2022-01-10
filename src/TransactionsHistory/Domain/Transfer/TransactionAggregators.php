<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Transfer;

use App\TransactionsHistory\Domain\TransactionAggregator\NullAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;

final class TransactionAggregators
{
    /** @var array<string,TransactionAggregatorInterface> */
    private array $aggregators = [];

    public function add(TransactionAggregatorInterface $aggregator): self
    {
        $this->aggregators[get_class($aggregator)] = $aggregator;

        return $this;
    }

    public function getForTransaction(Transaction $transaction): TransactionAggregatorInterface
    {
        return $this->aggregators[$transaction->getAggregatorClassName()] ?? new NullAggregator();
    }
}
