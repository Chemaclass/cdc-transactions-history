<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class NullAggregator implements TransactionAggregatorInterface
{
    public function aggregate(Transaction ...$transactions): array
    {
        return [];
    }
}
