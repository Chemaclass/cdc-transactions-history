<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

interface TransactionAggregatorInterface
{
    /**
     * @return array<string,mixed>
     */
    public function aggregate(Transaction ...$transactions): array;
}
