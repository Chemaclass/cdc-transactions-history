<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Transfer;

use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;

final class TransactionAggregators
{
    /** @var list<TransactionAggregatorInterface> */
    private array $aggregators = [];

    public function add(TransactionAggregatorInterface $aggregator): self
    {
        $this->aggregators[] = $aggregator;

        return $this;
    }

    /**
     * @return list<TransactionAggregatorInterface>
     */
    public function getAll(): array
    {
        return $this->aggregators;
    }
}
